@extends('admin.layout')

@section('title', 'إضافة منتج جديد')

@section('page_title', 'إضافة منتج جديد')

@section('content')
<!-- Page Header Actions -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-stack-lg">
    <div>
        <h2 class="font-headline-md text-headline-md font-bold text-on-surface">إضافة منتج جديد</h2>
        <nav class="flex text-label-sm text-on-surface-variant gap-2 mt-1">
            <a class="text-primary hover:underline" href="{{ route('admin.products.index') }}">المنتجات</a>
            <span>/</span>
            <span class="text-outline">منتج جديد</span>
        </nav>
    </div>
</div>

<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf
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
                               name="name" value="{{ old('name') }}" placeholder="مثال: هاتف سامسونج S24 ألترا" type="text" required/>
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="flex flex-col gap-2">
                            <label class="font-label-md text-on-surface-variant">رمز SKU</label>
                            <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary outline-none transition-all font-mono @error('sku') border-error @enderror" 
                                   name="sku" value="{{ old('sku') }}" placeholder="SMSG-S24-U-BLK" type="text"/>
                            @error('sku')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-label-md text-on-surface-variant font-medium text-on-surface">نوع المنتج <span class="text-error">*</span></label>
                            <select name="type" required class="w-full bg-white border border-outline-variant rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary outline-none appearance-none cursor-pointer">
                                <option value="simple" {{ old('type') === 'simple' ? 'selected' : '' }}>بسيط (Simple)</option>
                                <option value="variable" {{ old('type') === 'variable' ? 'selected' : '' }}>متغير (Variable)</option>
                                <option value="digital" {{ old('type') === 'digital' ? 'selected' : '' }}>رقمي (Digital)</option>
                                <option value="bundle" {{ old('type') === 'bundle' ? 'selected' : '' }}>حزمة (Bundle)</option>
                            </select>
                            @error('type')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-on-surface-variant">الوصف القصير</label>
                        <textarea class="w-full bg-white border border-outline-variant rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary outline-none transition-all @error('short_description') border-error @enderror" 
                                  name="short_description" placeholder="اكتب وصفاً موجزاً للمنتج..." rows="2">{{ old('short_description') }}</textarea>
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
                                      name="description" placeholder="أدخل محتوى المنتج هنا..." rows="6">{{ old('description') }}</textarea>
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
                                   name="price" value="{{ old('price') }}" placeholder="0.00" type="number" step="0.01" min="0" required/>
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-outline font-label-sm">ر.س</span>
                        </div>
                        @error('price')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-on-surface-variant">سعر التخفيض (اختياري)</label>
                        <div class="relative">
                            <input class="w-full bg-white border border-outline-variant rounded-lg pr-4 pl-12 py-2.5 focus:ring-2 focus:ring-primary outline-none transition-all @error('sale_price') border-error @enderror" 
                                   name="sale_price" value="{{ old('sale_price') }}" placeholder="0.00" type="number" step="0.01" min="0"/>
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
                               name="stock" value="{{ old('stock', 0) }}" type="number" min="0" required/>
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
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>نشط (Active)</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>غير نشط (Inactive)</option>
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>مسودة (Draft)</option>
                            </select>
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">expand_more</span>
                        </div>
                        @error('status')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-3 pt-2">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }}
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
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </section>

            <!-- Product Images Sidebar -->
            <section class="bg-white rounded-xl shadow-sm border border-outline-variant/30 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-2 text-on-surface">
                        <span class="material-symbols-outlined">image</span>
                        <h3 class="font-title-lg text-title-lg font-bold">صور المعرض</h3>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <div class="border-2 border-dashed border-outline-variant rounded-lg p-6 text-center hover:border-primary transition-colors cursor-pointer relative bg-surface-container-low">
                        <input type="file" name="images[]" multiple accept="image/jpeg,image/jpg,image/png,image/webp,image/gif"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <span class="material-symbols-outlined text-outline group-hover:text-primary mb-1">add_photo_alternate</span>
                        <p class="text-xs text-outline font-medium">اسحب الصور هنا أو انقر للرفع</p>
                    </div>
                    <div class="bg-surface-container-lowest p-3 rounded-lg border border-outline-variant flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary">lightbulb</span>
                        <p class="text-[11px] text-on-surface-variant leading-relaxed">
                            JPEG, PNG, WEBP — حتى 10 صور. الحد الأقصى: 2MB لكل صورة. يفضل استخدام صور مربعة بدقة 1200×1200 بكسل.
                        </p>
                    </div>
                    @error('images')<p class="form-error mt-2">{{ $message }}</p>@enderror
                    @error('images.*')<p class="form-error mt-2">{{ $message }}</p>@enderror
                </div>
            </section>
        </div>
    </div>

    <!-- Form Footer Actions -->
    <div class="mt-6 flex items-center gap-3 pt-4 border-t border-outline-variant">
        <button type="submit" class="px-8 py-2.5 rounded-xl bg-primary text-white font-label-md font-bold shadow-md hover:shadow-lg active:scale-95 transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">publish</span>
            حفظ ونشر المنتج
        </button>
        <a href="{{ route('admin.products.index') }}" class="px-6 py-2.5 rounded-xl bg-white text-on-surface-variant border border-outline-variant font-label-md hover:bg-surface-variant transition-colors">إلغاء</a>
    </div>
</form>
@endsection
