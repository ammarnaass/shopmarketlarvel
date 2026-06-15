@extends('admin.layout')

@section('title', 'تعديل ' . $product->name)

@section('page_title', 'تعديل المنتج')

@section('content')
<!-- Page Header Actions -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-stack-lg">
    <div>
        <h2 class="font-headline-md text-headline-md font-bold text-on-surface">تعديل المنتج</h2>
        <nav class="flex text-label-sm text-on-surface-variant gap-2 mt-1">
            <a class="text-primary hover:underline" href="{{ route('admin.products.index') }}">المنتجات</a>
            <span>/</span>
            <a class="text-primary hover:underline" href="{{ route('admin.products.show', $product) }}">{{ $product->name }}</a>
            <span>/</span>
            <span class="text-outline">تعديل</span>
        </nav>
    </div>
</div>

<form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <!-- Content Grid (Bento Style) -->
    <div class="grid grid-cols-12 gap-6">
        <!-- Left Side: Main Form Data (8 columns) -->
        <div class="col-span-12 lg:col-span-8 space-y-6">
            <!-- General Info Card -->
            <section class="bg-white rounded-xl shadow-sm border border-outline-variant/30 p-6">
                <div class="flex items-center gap-2 mb-6 text-primary">
                    <span class="material-symbols-outlined">info</span>
                    <h3 class="font-title-lg text-title-lg font-bold">المعلومات العامة</h3>
                </div>
                <div class="space-y-5">
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-on-surface-variant">اسم المنتج <span class="text-error">*</span></label>
                        <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all @error('name') border-error @enderror" 
                               name="name" value="{{ old('name', $product->name) }}" placeholder="مثال: هاتف سامسونج S24 ألترا" type="text" required/>
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="flex flex-col gap-2">
                            <label class="font-label-md text-on-surface-variant">رمز SKU</label>
                            <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary outline-none transition-all font-mono @error('sku') border-error @enderror" 
                                   name="sku" value="{{ old('sku', $product->sku) }}" placeholder="SMSG-S24-U-BLK" type="text"/>
                            @error('sku')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-label-md text-on-surface-variant font-medium text-on-surface">نوع المنتج <span class="text-error">*</span></label>
                            <select name="type" required class="w-full bg-white border border-outline-variant rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary outline-none appearance-none cursor-pointer">
                                <option value="simple" {{ old('type', $product->type) === 'simple' ? 'selected' : '' }}>بسيط (Simple)</option>
                                <option value="variable" {{ old('type', $product->type) === 'variable' ? 'selected' : '' }}>متغير (Variable)</option>
                                <option value="digital" {{ old('type', $product->type) === 'digital' ? 'selected' : '' }}>رقمي (Digital)</option>
                                <option value="bundle" {{ old('type', $product->type) === 'bundle' ? 'selected' : '' }}>حزمة (Bundle)</option>
                            </select>
                            @error('type')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-on-surface-variant">الوصف القصير</label>
                        <textarea class="w-full bg-white border border-outline-variant rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary outline-none transition-all @error('short_description') border-error @enderror" 
                                  name="short_description" placeholder="اكتب وصفاً موجزاً للمنتج..." rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                        @error('short_description')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-on-surface-variant">الوصف التفصيلي</label>
                        <div class="border border-outline-variant rounded-lg overflow-hidden">
                            <div class="bg-surface-container-low px-4 py-2 border-b border-outline-variant flex gap-4">
                                <span class="material-symbols-outlined text-[20px] text-outline cursor-default">format_bold</span>
                                <span class="material-symbols-outlined text-[20px] text-outline cursor-default">format_italic</span>
                                <span class="material-symbols-outlined text-[20px] text-outline cursor-default">format_list_bulleted</span>
                                <span class="material-symbols-outlined text-[20px] text-outline cursor-default">link</span>
                            </div>
                            <textarea class="w-full border-none p-4 focus:ring-0 outline-none text-body-md bg-white @error('description') border-error @enderror" 
                                      name="description" placeholder="أدخل محتوى المنتج هنا..." rows="6">{{ old('description', $product->description) }}</textarea>
                        </div>
                        @error('description')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </section>

            <!-- Pricing Card -->
            <section class="bg-white rounded-xl shadow-sm border border-outline-variant/30 p-6">
                <div class="flex items-center gap-2 mb-6 text-primary">
                    <span class="material-symbols-outlined">payments</span>
                    <h3 class="font-title-lg text-title-lg font-bold">التسعير</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-on-surface-variant">السعر الأساسي <span class="text-error">*</span></label>
                        <div class="relative">
                            <input class="w-full bg-white border border-outline-variant rounded-lg pr-4 pl-12 py-2.5 focus:ring-2 focus:ring-primary outline-none transition-all @error('price') border-error @enderror" 
                                   name="price" value="{{ old('price', $product->price) }}" placeholder="0.00" type="number" step="0.01" min="0" required/>
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-outline font-label-sm">ر.س</span>
                        </div>
                        @error('price')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-on-surface-variant">سعر التخفيض (اختياري)</label>
                        <div class="relative">
                            <input class="w-full bg-white border border-outline-variant rounded-lg pr-4 pl-12 py-2.5 focus:ring-2 focus:ring-primary outline-none transition-all @error('sale_price') border-error @enderror" 
                                   name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" placeholder="0.00" type="number" step="0.01" min="0"/>
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-outline font-label-sm">ر.س</span>
                        </div>
                        @error('sale_price')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </section>

            <!-- Inventory Card -->
            <section class="bg-white rounded-xl shadow-sm border border-outline-variant/30 p-6">
                <div class="flex items-center gap-2 mb-6 text-primary">
                    <span class="material-symbols-outlined">inventory</span>
                    <h3 class="font-title-lg text-title-lg font-bold">إدارة المخزون</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-on-surface-variant">الكمية المتاحة <span class="text-error">*</span></label>
                        <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary outline-none transition-all @error('stock') border-error @enderror" 
                               name="stock" value="{{ old('stock', $product->stock) }}" type="number" min="0" required/>
                        @error('stock')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-on-surface-variant">الحد الأدنى للتنبيه</label>
                        <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary outline-none transition-all" 
                               type="number" value="5" readonly/>
                        <p class="text-xs text-on-surface-variant">تنبيه تلقائي عند وصول المخزون للحد الأدنى.</p>
                    </div>
                </div>
            </section>
        </div>

        <!-- Right Side: Sidebar Controls (4 columns) -->
        <div class="col-span-12 lg:col-span-4 space-y-6">
            <!-- Status Card -->
            <section class="bg-white rounded-xl shadow-sm border border-outline-variant/30 p-6">
                <div class="flex items-center gap-2 mb-6 text-on-surface">
                    <span class="material-symbols-outlined">visibility</span>
                    <h3 class="font-title-lg text-title-lg font-bold">الحالة والظهور</h3>
                </div>
                <div class="space-y-6">
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-on-surface-variant">حالة المنتج <span class="text-error">*</span></label>
                        <div class="relative">
                            <select name="status" required class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2.5 font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition-all appearance-none cursor-pointer">
                                <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>نشط (Active)</option>
                                <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>غير نشط (Inactive)</option>
                                <option value="draft" {{ old('status', $product->status) === 'draft' ? 'selected' : '' }}>مسودة (Draft)</option>
                            </select>
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">expand_more</span>
                        </div>
                        @error('status')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-3 pt-2">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="featured" value="1" {{ old('featured', $product->featured) ? 'checked' : '' }}
                                   class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary cursor-pointer"/>
                            <span class="text-body-md text-on-surface-variant group-hover:text-on-surface transition-colors">منتج مميز (Featured)</span>
                            <span class="material-symbols-outlined text-sm text-warning" style="font-variation-settings:'FILL' 1">star</span>
                        </label>
                    </div>
                </div>
            </section>

            <!-- Category Card -->
            <section class="bg-white rounded-xl shadow-sm border border-outline-variant/30 p-6">
                <div class="flex items-center gap-2 mb-6 text-on-surface">
                    <span class="material-symbols-outlined">category</span>
                    <h3 class="font-title-lg text-title-lg font-bold">التصنيف</h3>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-label-md text-on-surface-variant">التصنيف الرئيسي <span class="text-error">*</span></label>
                    <select name="category_id" required class="w-full bg-white border border-outline-variant rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary outline-none appearance-none cursor-pointer">
                        <option value="">— اختر تصنيفاً —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </section>

            <!-- Product Images Sidebar (Edit Mode) -->
            <section class="bg-white rounded-xl shadow-sm border border-outline-variant/30 p-6">
                <div class="flex items-center justify-between mb-4 border-b border-outline-variant/30 pb-2">
                    <div class="flex items-center gap-2 text-on-surface">
                        <span class="material-symbols-outlined">image</span>
                        <h3 class="font-title-lg text-title-lg font-bold">صور المنتج</h3>
                    </div>
                    <a href="{{ route('admin.products.gallery', $product) }}" class="text-xs text-primary hover:underline flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">photo_library</span>
                        إدارة المعرض ({{ $product->images->count() }})
                    </a>
                </div>
                @if($product->images->count() > 0)
                    <div class="grid grid-cols-4 gap-2 mb-4">
                        @foreach($product->images->take(8) as $img)
                            <div class="relative rounded-lg overflow-hidden border border-outline-variant/50 aspect-square {{ $img->is_primary ? 'ring-2 ring-primary' : '' }}">
                                <img src="{{ asset('storage/' . $img->image) }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="flex flex-col gap-2">
                    <label class="font-label-md text-on-surface-variant">إضافة صور جديدة المعرض</label>
                    <div class="border-2 border-dashed border-outline-variant rounded-lg p-4 text-center hover:border-primary transition-colors cursor-pointer relative bg-surface-container-low">
                        <input type="file" name="images[]" multiple accept="image/jpeg,image/jpg,image/png,image/webp,image/gif"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <span class="material-symbols-outlined text-outline group-hover:text-primary mb-1">add_photo_alternate</span>
                        <p class="text-xs text-outline font-medium">انقر للرفع</p>
                    </div>
                    <p class="text-xs text-on-surface-variant">حتى 10 صور، 2MB لكل صورة</p>
                </div>
            </section>
        </div>
    </div>

    <!-- Form Footer Actions -->
    <div class="mt-6 flex items-center gap-3 pt-4 border-t border-outline-variant">
        <button type="submit" class="px-8 py-2.5 rounded-xl bg-primary text-white font-label-md font-bold shadow-md hover:shadow-lg active:scale-95 transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">save</span>
            تحديث وحفظ التغييرات
        </button>
        <a href="{{ route('admin.products.show', $product) }}" class="px-6 py-2.5 rounded-xl bg-white text-on-surface-variant border border-outline-variant font-label-md hover:bg-surface-variant transition-colors">إلغاء</a>
    </div>
</form>
@endsection
