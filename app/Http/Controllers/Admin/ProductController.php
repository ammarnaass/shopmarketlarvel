<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Image upload constraints.
     * Centralised here so they appear in every form (create / edit / gallery).
     */
    public const IMAGE_MAX_SIZE_KB = 2048;   // 2 MB
    public const IMAGE_MAX_FILES   = 10;     // per upload batch
    public const IMAGE_MIMES       = 'jpeg,jpg,png,webp,gif';
    public const IMAGE_MIN_WIDTH    = 400;
    public const IMAGE_MIN_HEIGHT   = 400;
    public const IMAGE_RECOMMENDED_W = 1200;
    public const IMAGE_RECOMMENDED_H = 1200;

    public function index(Request $request): View
    {
        $query = Product::with('category', 'primaryImage');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('sku', 'like', "%{$request->search}%");
            });
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(20)->withQueryString();
        $categories = \App\Models\Category::where('status', 'active')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = \App\Models\Category::where('status', 'active')->get();
        $shippingCompanies = \App\Models\ShippingCompany::where('status', 'active')->orderBy('name')->get();
        return view('admin.products.create', compact('categories', 'shippingCompanies'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'stock' => 'required|integer|min:0',
            'type' => 'required|in:simple,variable,digital,bundle',
            'status' => 'required|in:active,inactive,draft',
            'featured' => 'boolean',
            'images' => 'nullable|array|max:' . self::IMAGE_MAX_FILES,
            'images.*' => 'image|mimes:' . self::IMAGE_MIMES . '|max:' . self::IMAGE_MAX_SIZE_KB,
            'options' => 'nullable|array',
            'options.*.name' => 'required|string|max:255',
            'options.*.type' => 'required|in:select,radio,color,text,file',
            'options.*.required' => 'boolean',
            'options.*.values' => 'nullable|array',
            'options.*.values.*.value' => 'required|string|max:255',
            'options.*.values.*.color_code' => 'nullable|string|max:20',
            'options.*.values.*.price_adjustment' => 'nullable|numeric',
            'options.*.values.*.stock' => 'nullable|integer',
            'shipping_company_id' => 'nullable|exists:shipping_companies,id',
            'custom_fields' => 'nullable|array',
            'custom_fields.*.label' => 'required|string|max:255',
            'custom_fields.*.type' => 'required|in:text,textarea,file,number,calculated',
            'custom_fields.*.required' => 'boolean',
            'custom_fields.*.price_effect' => 'nullable|numeric',
            'weight' => 'nullable|numeric|min:0',
            'product_shipping_rules' => 'nullable|array',
            'product_shipping_rules.max_weight' => 'nullable|numeric|min:0',
            'product_shipping_rules.priority' => 'nullable|integer|min:0|max:999',
            'product_shipping_rules.fragile' => 'boolean',
            'product_shipping_rules.hazardous' => 'boolean',
            'product_shipping_rules.requires_signature' => 'boolean',
        ], [
            'images.max' => 'الحد الأقصى ' . self::IMAGE_MAX_FILES . ' صور في المرة الواحدة',
            'images.*.image' => 'يجب أن يكون الملف صورة',
            'images.*.mimes' => 'الصيغ المدعومة: ' . self::IMAGE_MIMES,
            'images.*.max' => 'حجم كل صورة يجب ألا يتجاوز ' . self::IMAGE_MAX_SIZE_KB . 'KB',
        ]);

        $product = null;
        DB::transaction(function () use ($request, &$product) {
            $product = Product::create($request->only([
                'category_id', 'name', 'description', 'short_description',
                'price', 'sale_price', 'sku', 'stock', 'type', 'status', 'featured',
                'shipping_company_id', 'weight',
            ]));

            // Handle gallery images
            if ($request->hasFile('images')) {
                $this->storeImages($request->file('images'), $product);
            }

            // Sync options, custom fields, and shipping rules
            $this->syncOptionsAndCustomFields(
                $product,
                $request->input('options', []),
                $request->input('custom_fields', [])
            );
            $this->syncShippingRules($product, $request->input('product_shipping_rules', []));
        });

        return redirect()->route('admin.products.gallery', $product)
            ->with('success', 'تم إضافة المنتج. يمكنك إضافة المزيد من الصور.');
    }

    public function show(Product $product): View
    {
        $product->load('images', 'options.values', 'variants', 'category');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Image gallery manager: shows existing images + upload form.
     */
    public function gallery(Product $product): View
    {
        $product->load('images');
        return view('admin.products.gallery', compact('product'));
    }

    /**
     * Upload additional images to a product's gallery.
     */
    public function uploadImages(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'images' => 'required|array|min:1|max:' . self::IMAGE_MAX_FILES,
            'images.*' => 'image|mimes:' . self::IMAGE_MIMES . '|max:' . self::IMAGE_MAX_SIZE_KB,
            'primary' => 'nullable|integer',
        ], [
            'images.required' => 'اختر صورة واحدة على الأقل',
            'images.max' => 'الحد الأقصى ' . self::IMAGE_MAX_FILES . ' صور',
            'images.*.image' => 'يجب أن يكون الملف صورة',
            'images.*.mimes' => 'الصيغ المدعومة: ' . self::IMAGE_MIMES,
            'images.*.max' => 'حجم كل صورة يجب ألا يتجاوز ' . self::IMAGE_MAX_SIZE_KB . 'KB',
        ]);

        $stored = $this->storeImages($request->file('images'), $product);

        // If user picked a primary, mark it
        if ($request->filled('primary') && in_array((int) $request->primary, $stored)) {
            ProductImage::where('product_id', $product->id)->update(['is_primary' => false]);
            ProductImage::where('id', (int) $request->primary)->update(['is_primary' => true]);
        }

        return redirect()->route('admin.products.gallery', $product)
            ->with('success', 'تم رفع ' . count($stored) . ' صورة بنجاح');
    }

    /**
     * Set one image as primary.
     */
    public function setPrimaryImage(Product $product, ProductImage $image): RedirectResponse
    {
        if ($image->product_id !== $product->id) abort(404);
        DB::transaction(function () use ($product, $image) {
            ProductImage::where('product_id', $product->id)->update(['is_primary' => false]);
            $image->update(['is_primary' => true]);
        });
        return back()->with('success', 'تم تعيين الصورة كرئيسية');
    }

    /**
     * Reorder / update alt text for an image.
     */
    public function updateImage(Request $request, Product $product, ProductImage $image): RedirectResponse
    {
        if ($image->product_id !== $product->id) abort(404);

        $data = $request->validate([
            'alt_text' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
        ]);

        $image->update($data);
        return back()->with('success', 'تم تحديث الصورة');
    }

    /**
     * Delete a single image.
     */
    public function destroyImage(Product $product, ProductImage $image): RedirectResponse
    {
        if ($image->product_id !== $product->id) abort(404);

        DB::transaction(function () use ($image, $product) {
            // Delete the file
            if ($image->image && Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }
            $wasPrimary = $image->is_primary;
            $image->delete();

            // Promote the next image to primary if we just deleted the primary
            if ($wasPrimary) {
                $next = ProductImage::where('product_id', $product->id)
                    ->orderBy('order')->orderBy('id')
                    ->first();
                if ($next) $next->update(['is_primary' => true]);
            }
        });

        return back()->with('success', 'تم حذف الصورة');
    }

    public function edit(Product $product): View
    {
        $categories = \App\Models\Category::where('status', 'active')->get();
        $shippingCompanies = \App\Models\ShippingCompany::where('status', 'active')->orderBy('name')->get();
        $product->load('images', 'options.values', 'variants', 'customFields');
        return view('admin.products.edit', compact('product', 'categories', 'shippingCompanies'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'stock' => 'required|integer|min:0',
            'type' => 'required|in:simple,variable,digital,bundle',
            'status' => 'required|in:active,inactive,draft',
            'featured' => 'boolean',
            'options' => 'nullable|array',
            'options.*.name' => 'required|string|max:255',
            'options.*.type' => 'required|in:select,radio,color,text,file',
            'options.*.required' => 'boolean',
            'options.*.values' => 'nullable|array',
            'options.*.values.*.value' => 'required|string|max:255',
            'options.*.values.*.color_code' => 'nullable|string|max:20',
            'options.*.values.*.price_adjustment' => 'nullable|numeric',
            'options.*.values.*.stock' => 'nullable|integer',
            'shipping_company_id' => 'nullable|exists:shipping_companies,id',
            'custom_fields' => 'nullable|array',
            'custom_fields.*.label' => 'required|string|max:255',
            'custom_fields.*.type' => 'required|in:text,textarea,file,number,calculated',
            'custom_fields.*.required' => 'boolean',
            'custom_fields.*.price_effect' => 'nullable|numeric',
            'weight' => 'nullable|numeric|min:0',
            'product_shipping_rules' => 'nullable|array',
            'product_shipping_rules.max_weight' => 'nullable|numeric|min:0',
            'product_shipping_rules.priority' => 'nullable|integer|min:0|max:999',
            'product_shipping_rules.fragile' => 'boolean',
            'product_shipping_rules.hazardous' => 'boolean',
            'product_shipping_rules.requires_signature' => 'boolean',
        ]);

        DB::transaction(function () use ($request, $product) {
            $product->update($request->only([
                'category_id', 'name', 'description', 'short_description',
                'price', 'sale_price', 'sku', 'stock', 'type', 'status', 'featured',
                'shipping_company_id', 'weight',
            ]));

            $this->syncOptionsAndCustomFields(
                $product,
                $request->input('options', []),
                $request->input('custom_fields', [])
            );
            $this->syncShippingRules($product, $request->input('product_shipping_rules', []));
        });

        return redirect()->route('admin.products.gallery', $product)
            ->with('success', 'تم تحديث المنتج');
    }

    public function destroy(Product $product): RedirectResponse
    {
        foreach ($product->images as $img) {
            if ($img->image && Storage::disk('public')->exists($img->image)) {
                Storage::disk('public')->delete($img->image);
            }
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'تم حذف المنتج');
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,feature,unfeature',
            'product_ids' => 'required|array|min:1',
        ]);

        $action = $request->action;
        $ids = $request->product_ids;

        switch ($action) {
            case 'activate':
                Product::whereIn('id', $ids)->update(['status' => 'active']);
                $msg = 'تم تفعيل ' . count($ids) . ' منتج';
                break;
            case 'deactivate':
                Product::whereIn('id', $ids)->update(['status' => 'inactive']);
                $msg = 'تم تعطيل ' . count($ids) . ' منتج';
                break;
            case 'delete':
                Product::whereIn('id', $ids)->each(function ($product) {
                    foreach ($product->images as $img) {
                        if ($img->image && Storage::disk('public')->exists($img->image)) {
                            Storage::disk('public')->delete($img->image);
                        }
                    }
                    $product->delete();
                });
                $msg = 'تم حذف ' . count($ids) . ' منتج';
                break;
            case 'feature':
                Product::whereIn('id', $ids)->update(['featured' => true]);
                $msg = 'تم تمييز ' . count($ids) . ' منتج';
                break;
            case 'unfeature':
                Product::whereIn('id', $ids)->update(['featured' => false]);
                $msg = 'تم إلغاء تمييز ' . count($ids) . ' منتج';
                break;
        }

        return redirect()->route('admin.products.index')->with('success', $msg);
    }

    public function export(Request $request)
    {
        $query = Product::with('category');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $products = $query->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');
            // Header
            fputcsv($file, ['ID', 'الاسم', 'SKU', 'السعر', 'سعر الخصم', 'المخزون', 'التصنيف', 'الحالة', 'مميز', 'الوزن']);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->sku,
                    $product->price,
                    $product->sale_price ?? '',
                    $product->stock,
                    $product->category?->name ?? '',
                    $product->status,
                    $product->featured ? 'نعم' : 'لا',
                    $product->weight ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Persist uploaded files and return an array of created image ids.
     * First uploaded image becomes primary if the product has none yet.
     */
    private function storeImages(array $files, Product $product): array
    {
        $hasPrimary = ProductImage::where('product_id', $product->id)
            ->where('is_primary', true)->exists();
        $currentOrder = (int) ProductImage::where('product_id', $product->id)
            ->max('order') + 1;

        $created = [];
        foreach ($files as $i => $file) {
            // Generate unique filename
            $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
            $filename = 'products/' . $product->id . '/' . Str::random(20) . '.' . $ext;
            $file->storeAs(dirname($filename), basename($filename), 'public');

            $img = ProductImage::create([
                'product_id' => $product->id,
                'image' => $filename,
                'is_primary' => !$hasPrimary && $i === 0,
                'order' => $currentOrder++,
            ]);
            $created[] = $img->id;
        }

        return $created;
    }

    /**
     * Sync product options and custom fields from input array.
     */
    protected function syncShippingRules(Product $product, array $input = [])
    {
        if (empty($input)) {
            $product->shippingRule()?->delete();
            return;
        }

        $product->shippingRule()->updateOrCreate(
            ['product_id' => $product->id],
            [
                'max_weight' => !empty($input['max_weight']) ? $input['max_weight'] : null,
                'priority' => (int)($input['priority'] ?? 0),
                'fragile' => filter_var($input['fragile'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'hazardous' => filter_var($input['hazardous'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'requires_signature' => filter_var($input['requires_signature'] ?? false, FILTER_VALIDATE_BOOLEAN),
            ]
        );
    }

    protected function syncOptionsAndCustomFields(Product $product, array $optionsInput = [], array $customFieldsInput = [])
    {
        // Delete previous options (option values will be cascadingly deleted)
        $product->options()->delete();

        foreach ($optionsInput as $index => $opt) {
            if (empty($opt['name'])) continue;

            $option = $product->options()->create([
                'name' => $opt['name'],
                'type' => $opt['type'] ?? 'select',
                'required' => filter_var($opt['required'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'order' => $index,
            ]);

            if (!empty($opt['values']) && is_array($opt['values'])) {
                foreach ($opt['values'] as $val) {
                    if (!isset($val['value']) || $val['value'] === '') continue;
                    $option->values()->create([
                        'value' => $val['value'],
                        'color_code' => $val['color_code'] ?? null,
                        'price_adjustment' => (float)($val['price_adjustment'] ?? 0),
                        'stock' => (int)($val['stock'] ?? 0),
                    ]);
                }
            }
        }

        // Sync custom fields
        $product->customFields()->delete();

        foreach ($customFieldsInput as $cf) {
            if (empty($cf['label'])) continue;

            $product->customFields()->create([
                'label' => $cf['label'],
                'type' => $cf['type'] ?? 'text',
                'required' => filter_var($cf['required'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'price_effect' => (float)($cf['price_effect'] ?? 0),
            ]);
        }
    }
}
