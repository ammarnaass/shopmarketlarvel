<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductService
{
    public function searchProducts(array $filters = []): Builder
    {
        $query = Product::active()->with(['category', 'primaryImage']);

        if (!empty($filters['q'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['q']}%")
                  ->orWhere('description', 'like', "%{$filters['q']}%")
                  ->orWhere('sku', 'like', "%{$filters['q']}%");
            });
        }

        if (!empty($filters['category_id']) || !empty($filters['category'])) {
            $categoryId = $filters['category_id'] ?? null;
            if (!$categoryId && !empty($filters['category'])) {
                $cat = Category::where('slug', $filters['category'])->first();
                $categoryId = $cat?->id;
            }
            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (!empty($filters['featured'])) {
            $query->featured();
        }

        if (!empty($filters['in_stock'])) {
            $query->inStock();
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        $sortBy = $filters['sort'] ?? 'created_at';
        $sortDir = $filters['dir'] ?? 'desc';

        $allowedSorts = ['name', 'price', 'created_at', 'stock'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir);
        }

        return $query;
    }

    public function getProductBySlug(string $slug): Product
    {
        return Product::active()
            ->with([
                'category',
                'images',
                'options.values',
                'variants',
                'customFields',
                'reviews.user',
            ])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function getFeatured(int $limit = 8)
    {
        return Product::active()->featured()->with('primaryImage')->latest()->limit($limit)->get();
    }

    public function getRelated(Product $product, int $limit = 4)
    {
        return Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('primaryImage')
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}
