@extends('admin.layout')

@section('title', __t('admin.products.edit_product') . ' ' . $product->name)

@section('page_title', __t('admin.products.edit_product'))

@section('content')
<!-- Page Header Actions -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-stack-lg">
    <div>
        <h2 class="font-headline-md text-headline-md font-bold text-on-surface">{{ __t('admin.products.edit_product') }}</h2>
        <nav class="flex text-label-sm text-on-surface-variant gap-2 mt-1">
            <a class="text-primary hover:underline" href="{{ route('admin.products.index') }}">{{ __t('admin.products.page_title') }}</a>
            <span>/</span>
            <a class="text-primary hover:underline" href="{{ route('admin.products.show', $product) }}">{{ $product->name }}</a>
            <span>/</span>
            <span class="text-outline">{{ __t('admin.common.edit') }}</span>
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
                    <h3 class="font-title-lg text-title-lg font-bold">{{ __t('admin.products.general_info') }}</h3>
                </div>
                <div class="space-y-5">
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-on-surface-variant">{{ __t('admin.products.product_name') }} <span class="text-error">*</span></label>
                        <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all @error('name') border-error @enderror" 
                               name="name" value="{{ old('name', $product->name) }}" placeholder="مثال: هاتف سامسونج S24 ألترا" type="text" required/>
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="flex flex-col gap-2">
                            <label class="font-label-md text-on-surface-variant">{{ __t('admin.products.sku_label') }}</label>
                            <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary outline-none transition-all font-mono @error('sku') border-error @enderror" 
                                   name="sku" value="{{ old('sku', $product->sku) }}" placeholder="SMSG-S24-U-BLK" type="text"/>
                            @error('sku')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-label-md text-on-surface-variant font-medium text-on-surface">{{ __t('admin.products.type_label') }} <span class="text-error">*</span></label>
                            <select name="type" required class="w-full bg-white border border-outline-variant rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary outline-none appearance-none cursor-pointer">
                                <option value="simple" {{ old('type', $product->type) === 'simple' ? 'selected' : '' }}>{{ __t('admin.products.simple') }}</option>
                                <option value="variable" {{ old('type', $product->type) === 'variable' ? 'selected' : '' }}>{{ __t('admin.products.variable') }}</option>
                                <option value="digital" {{ old('type', $product->type) === 'digital' ? 'selected' : '' }}>{{ __t('admin.products.digital') }}</option>
                                <option value="bundle" {{ old('type', $product->type) === 'bundle' ? 'selected' : '' }}>{{ __t('admin.products.bundle') }}</option>
                            </select>
                            @error('type')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-on-surface-variant">{{ __t('admin.products.short_description') }}</label>
                        <textarea class="w-full bg-white border border-outline-variant rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary outline-none transition-all @error('short_description') border-error @enderror" 
                                  name="short_description" placeholder="اكتب وصفاً موجزاً للمنتج..." rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                        @error('short_description')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-on-surface-variant">{{ __t('admin.products.description_label') }}</label>
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
                    <h3 class="font-title-lg text-title-lg font-bold">{{ __t('admin.products.pricing') }}</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-on-surface-variant">{{ __t('admin.products.base_price') }} <span class="text-error">*</span></label>
                        <div class="relative">
                            <input class="w-full bg-white border border-outline-variant rounded-lg pr-4 pl-12 py-2.5 focus:ring-2 focus:ring-primary outline-none transition-all @error('price') border-error @enderror" 
                                   name="price" value="{{ old('price', $product->price) }}" placeholder="0.00" type="number" step="0.01" min="0" required/>
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-outline font-label-sm">{{ currentCurrencySymbol() }}</span>
                        </div>
                        @error('price')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-on-surface-variant">{{ __t('admin.products.sale_price_label') }}</label>
                        <div class="relative">
                            <input class="w-full bg-white border border-outline-variant rounded-lg pr-4 pl-12 py-2.5 focus:ring-2 focus:ring-primary outline-none transition-all @error('sale_price') border-error @enderror" 
                                   name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" placeholder="0.00" type="number" step="0.01" min="0"/>
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-outline font-label-sm">{{ currentCurrencySymbol() }}</span>
                        </div>
                        @error('sale_price')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </section>

            <!-- Inventory Card -->
            <section class="bg-white rounded-xl shadow-sm border border-outline-variant/30 p-6">
                <div class="flex items-center gap-2 mb-6 text-primary">
                    <span class="material-symbols-outlined">inventory</span>
                    <h3 class="font-title-lg text-title-lg font-bold">{{ __t('admin.products.inventory') }}</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-on-surface-variant">{{ __t('admin.products.stock_label') }} <span class="text-error">*</span></label>
                        <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary outline-none transition-all @error('stock') border-error @enderror" 
                               name="stock" value="{{ old('stock', $product->stock) }}" type="number" min="0" required/>
                        @error('stock')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-on-surface-variant">{{ __t('admin.products.min_alert') }}</label>
                        <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary outline-none transition-all" 
                               type="number" value="5" readonly/>
                        <p class="text-xs text-on-surface-variant">{{ __t('admin.products.min_alert_hint') }}</p>
                    </div>
                </div>
            </section>

            <!-- Product Options & Specifications Card -->
            <section class="bg-white rounded-xl shadow-sm border border-outline-variant/30 p-6 space-y-6" x-data="productOptionsManager()">
                <div class="flex items-center justify-between text-primary">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined">tune</span>
                        <h3 class="font-title-lg text-title-lg font-bold">{{ __t('admin.products.options_specs') }}</h3>
                    </div>
                    <button type="button" @click="addOption()" class="text-xs bg-primary/10 hover:bg-primary/20 text-primary px-3 py-1.5 rounded-lg font-bold transition flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">add</span>{{ __t('admin.products.add_option') }}
                    </button>
                </div>

                <div class="space-y-6">
                    <template x-for="(option, optIndex) in options" :key="option.id">
                        <div class="border border-outline-variant/50 rounded-xl p-4 bg-surface-container-lowest relative space-y-4 shadow-sm">
                            <!-- Option Header Control -->
                            <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-outline-variant/30">
                                <div class="flex items-center gap-3 flex-1 min-w-[200px]">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-between font-bold text-sm text-gray-500">
                                        <span class="mx-auto" x-text="optIndex + 1"></span>
                                    </div>
                                    <input type="text" :name="`options[${optIndex}][name]`" x-model="option.name" required placeholder="اسم الخيار (مثال: اللون، المقاس)" class="flex-1 min-w-[150px] bg-white border border-outline-variant rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-primary outline-none">
                                </div>
                                <div class="flex items-center gap-3">
                                    <select :name="`options[${optIndex}][type]`" x-model="option.type" class="bg-white border border-outline-variant rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-primary outline-none cursor-pointer">
                                        <option value="select">{{ __t('admin.products.dropdown') }}</option>
                                        <option value="radio">{{ __t('admin.products.radio') }}</option>
                                        <option value="color">{{ __t('admin.products.color_picker') }}</option>
                                        <option value="text">{{ __t('admin.products.text_input') }}</option>
                                        <option value="file">{{ __t('admin.products.file_upload') }}</option>
                                    </select>
                                    <label class="flex items-center gap-1.5 cursor-pointer text-xs font-semibold text-on-surface-variant">
                                        <input type="checkbox" :name="`options[${optIndex}][required]`" x-model="option.required" class="w-4 h-4 text-primary rounded">
                                        <span>{{ __t('admin.products.required') }}</span>
                                    </label>
                                    <button type="button" @click="removeOption(optIndex)" class="text-error hover:bg-error/10 p-1.5 rounded-lg transition" title="{{ __t('admin.products.delete_option') }}">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Option Values Section (Only for selectable types) -->
                            <div x-show="['select', 'radio', 'color'].includes(option.type)" class="space-y-3">
                                <div class="flex items-center justify-between text-xs font-bold text-on-surface-variant">
                                    <span>{{ __t('admin.products.option_values') }}</span>
                                    <button type="button" @click="addValue(optIndex)" class="text-primary hover:underline flex items-center gap-0.5">
                                        <span class="material-symbols-outlined text-xs">add_circle</span>{{ __t('admin.products.add_value') }}
                                    </button>
                                </div>

                                <div class="space-y-2">
                                    <template x-for="(val, valIndex) in option.values" :key="val.id">
                                        <div class="grid grid-cols-12 gap-2 items-center bg-gray-50/50 p-2 rounded-lg border border-gray-100">
                                            <!-- Value Input -->
                                            <div class="col-span-12 md:col-span-4 flex items-center gap-2">
                                                <input type="text" :name="`options[${optIndex}][values][${valIndex}][value]`" x-model="val.value" required placeholder="القيمة (مثال: أحمر، XL)" class="w-full bg-white border border-outline-variant rounded-lg px-2.5 py-1.5 text-xs focus:ring-1 focus:ring-primary outline-none">
                                            </div>
                                            <!-- Color Code (Only if type is color) -->
                                            <div class="col-span-12 md:col-span-3 flex items-center gap-1.5" x-show="option.type === 'color'">
                                                <input type="color" x-model="val.color_code" class="w-7 h-7 rounded border cursor-pointer p-0">
                                                <input type="text" :name="`options[${optIndex}][values][${valIndex}][color_code]`" x-model="val.color_code" placeholder="#FFFFFF" class="flex-1 bg-white border border-outline-variant rounded-lg px-2 py-1.5 text-xs font-mono focus:ring-1 focus:ring-primary outline-none" maxlength="7">
                                            </div>
                                            <!-- Price Adjustment -->
                                            <div class="col-span-6 md:col-span-2 relative font-mono">
                                                <input type="number" :name="`options[${optIndex}][values][${valIndex}][price_adjustment]`" x-model="val.price_adjustment" step="0.01" placeholder="فرق السعر" class="w-full bg-white border border-outline-variant rounded-lg pl-6 pr-2 py-1.5 text-xs text-left focus:ring-1 focus:ring-primary outline-none">
                                                <span class="absolute left-2 top-1/2 -translate-y-1/2 text-[9px] text-outline">{{ currentCurrencySymbol() }}</span>
                                            </div>
                                            <!-- Option Stock -->
                                            <div class="col-span-4 md:col-span-2 font-mono">
                                                <input type="number" :name="`options[${optIndex}][values][${valIndex}][stock]`" x-model="val.stock" placeholder="المخزون" class="w-full bg-white border border-outline-variant rounded-lg px-2 py-1.5 text-xs text-center focus:ring-1 focus:ring-primary outline-none">
                                            </div>
                                            <!-- Delete Button -->
                                            <div class="col-span-2 md:col-span-1 text-left">
                                                <button type="button" @click="removeValue(optIndex, valIndex)" class="text-gray-400 hover:text-error p-1 rounded transition">
                                                    <span class="material-symbols-outlined text-[18px]">close</span>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                    <div x-show="option.values.length === 0" class="text-center py-4 bg-gray-50/30 border border-dashed rounded-lg text-xs text-on-surface-variant">
                                        {{ __t('admin.products.no_values') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div x-show="options.length === 0" class="text-center py-8 border border-dashed border-outline-variant/60 rounded-xl text-sm text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl text-outline-variant mb-2">tune</span>
                        <p>{{ __t('admin.products.no_options') }}</p>
                        <p class="text-xs text-outline mt-1">{{ __t('admin.products.no_options_hint') }}</p>
                    </div>
                </div>
            </section>

            <!-- Custom Input Fields Card -->
            <section class="bg-white rounded-xl shadow-sm border border-outline-variant/30 p-6 space-y-6" x-data="productCustomFieldsManager()">
                <div class="flex items-center justify-between text-primary">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined">edit_note</span>
                        <h3 class="font-title-lg text-title-lg font-bold">{{ __t('admin.products.custom_fields') }}</h3>
                    </div>
                    <button type="button" @click="addField()" class="text-xs bg-primary/10 hover:bg-primary/20 text-primary px-3 py-1.5 rounded-lg font-bold transition flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">add</span>{{ __t('admin.products.add_field') }}
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="(field, fIndex) in fields" :key="field.id">
                        <div class="grid grid-cols-12 gap-3 items-center bg-surface-container-lowest p-4 rounded-xl border border-outline-variant/50 shadow-sm relative">
                            <!-- Field Label -->
                            <div class="col-span-12 md:col-span-4 flex flex-col gap-1">
                                <label class="text-xs text-on-surface-variant font-bold">{{ __t('admin.products.field_name') }}</label>
                                <input type="text" :name="`custom_fields[${fIndex}][label]`" x-model="field.label" required placeholder="{{ __t('admin.products.field_name_placeholder') }}" class="w-full bg-white border border-outline-variant rounded-lg px-3 py-1.5 text-xs focus:ring-1 focus:ring-primary outline-none">
                            </div>
                            <!-- Field Type -->
                            <div class="col-span-6 md:col-span-3 flex flex-col gap-1">
                                <label class="text-xs text-on-surface-variant font-bold">{{ __t('admin.products.field_type') }}</label>
                                <select :name="`custom_fields[${fIndex}][type]`" x-model="field.type" class="w-full bg-white border border-outline-variant rounded-lg px-3 py-1.5 text-xs focus:ring-1 focus:ring-primary outline-none cursor-pointer">
                                    <option value="text">{{ __t('admin.products.text_field') }}</option>
                                    <option value="textarea">{{ __t('admin.products.textarea_field') }}</option>
                                    <option value="number">{{ __t('admin.products.number_field') }}</option>
                                    <option value="file">{{ __t('admin.products.file_field') }}</option>
                                </select>
                            </div>
                            <!-- Price Effect -->
                            <div class="col-span-6 md:col-span-3 flex flex-col gap-1">
                                <label class="text-xs text-on-surface-variant font-bold">{{ __t('admin.products.extra_cost') }}</label>
                                <div class="relative font-mono">
                                    <input type="number" :name="`custom_fields[${fIndex}][price_effect]`" x-model="field.price_effect" step="0.01" placeholder="مثال: 15.00" class="w-full bg-white border border-outline-variant rounded-lg pl-6 pr-3 py-1.5 text-xs focus:ring-1 focus:ring-primary outline-none">
                                    <span class="absolute left-2 top-1/2 -translate-y-1/2 text-[9px] text-outline">{{ currentCurrencySymbol() }}</span>
                                </div>
                            </div>
                            <!-- Required Toggle & Remove -->
                            <div class="col-span-12 md:col-span-2 flex items-center justify-between md:justify-end gap-4 mt-2 md:mt-0 pt-2 md:pt-0 border-t md:border-t-0 border-gray-100">
                                <label class="flex items-center gap-1.5 cursor-pointer text-xs font-semibold text-on-surface-variant">
                                    <input type="checkbox" :name="`custom_fields[${fIndex}][required]`" x-model="field.required" class="w-4 h-4 text-primary rounded">
                                    <span>{{ __t('admin.products.required') }}</span>
                                </label>
                                <button type="button" @click="removeField(fIndex)" class="text-error hover:bg-error/10 p-1.5 rounded-lg transition" title="{{ __t('admin.products.delete_field') }}">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
                        </div>
                    </template>

                    <div x-show="fields.length === 0" class="text-center py-6 border border-dashed border-outline-variant/60 rounded-xl text-sm text-on-surface-variant">
                        <span class="material-symbols-outlined text-3xl text-outline-variant mb-2">edit_note</span>
                        <p>{{ __t('admin.products.no_custom_fields') }}</p>
                        <p class="text-xs text-outline mt-1">{{ __t('admin.products.no_custom_fields_hint') }}</p>
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
                    <h3 class="font-title-lg text-title-lg font-bold">{{ __t('admin.products.status_visibility') }}</h3>
                </div>
                <div class="space-y-6">
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-on-surface-variant">{{ __t('admin.products.status_label') }} <span class="text-error">*</span></label>
                        <div class="relative">
                            <select name="status" required class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2.5 font-bold text-primary focus:ring-2 focus:ring-primary outline-none transition-all appearance-none cursor-pointer">
                                <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>{{ __t('admin.common.active') }}</option>
                                <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>{{ __t('admin.common.inactive') }}</option>
                                <option value="draft" {{ old('status', $product->status) === 'draft' ? 'selected' : '' }}>{{ __t('admin.products.draft_status') }}</option>
                            </select>
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">expand_more</span>
                        </div>
                        @error('status')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-3 pt-2">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="featured" value="1" {{ old('featured', $product->featured) ? 'checked' : '' }}
                                   class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary cursor-pointer"/>
                            <span class="text-body-md text-on-surface-variant group-hover:text-on-surface transition-colors">{{ __t('admin.products.featured_label') }}</span>
                            <span class="material-symbols-outlined text-sm text-warning" style="font-variation-settings:'FILL' 1">star</span>
                        </label>
                    </div>
                </div>
            </section>

            <!-- Category Card -->
            <section class="bg-white rounded-xl shadow-sm border border-outline-variant/30 p-6">
                <div class="flex items-center gap-2 mb-6 text-on-surface">
                    <span class="material-symbols-outlined">category</span>
                    <h3 class="font-title-lg text-title-lg font-bold">{{ __t('admin.products.main_category') }}</h3>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-label-md text-on-surface-variant">{{ __t('admin.products.category_label') }} <span class="text-error">*</span></label>
                    <select name="category_id" required class="w-full bg-white border border-outline-variant rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary outline-none appearance-none cursor-pointer">
                        <option value="">— {{ __t('admin.products.select_category') }} —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </section>

            <!-- Shipping Company Card -->
            @if(isset($shippingCompanies) && $shippingCompanies->count() > 0)
            <section class="bg-white rounded-xl shadow-sm border border-outline-variant/30 p-6">
                <div class="flex items-center gap-2 mb-6 text-on-surface">
                    <span class="material-symbols-outlined">local_shipping</span>
                    <h3 class="font-title-lg text-title-lg font-bold">شركة الشحن الافتراضية</h3>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-label-md text-on-surface-variant">شركة الشحن المفضلة لهذا المنتج (اختياري)</label>
                    <select name="shipping_company_id" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary outline-none appearance-none cursor-pointer">
                        <option value="">— تلقائي (اختيار النظام) —</option>
                        @foreach($shippingCompanies as $company)
                            <option value="{{ $company->id }}" {{ old('shipping_company_id', $product->shipping_company_id) == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-on-surface-variant mt-1">عند تعيين شركة شحن، ستظهر طرق الشحن الخاصة بها فقط للعميل عند الطلب الفوري.</p>
                </div>
            </section>
            @endif

            @php $sr = $product->shippingRule; @endphp
            <section class="bg-white rounded-xl shadow-sm border border-outline-variant/30 p-6">
                <div class="flex items-center gap-2 mb-4 text-on-surface">
                    <span class="material-symbols-outlined">package_2</span>
                    <h3 class="font-title-lg text-title-lg font-bold">إعدادات الشحن</h3>
                </div>
                <div class="space-y-3">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="font-label-md text-on-surface-variant text-xs">الوزن (كجم)</label>
                            <input type="number" name="weight" step="0.001" min="0"
                                   value="{{ old('weight', $product->weight ?? '') }}"
                                   class="w-full bg-white border border-outline-variant rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary outline-none">
                        </div>
                        <div>
                            <label class="font-label-md text-on-surface-variant text-xs">الحد الأقصى للوزن</label>
                            <input type="number" name="product_shipping_rules[max_weight]" step="0.01" min="0"
                                   value="{{ old('product_shipping_rules.max_weight', $sr->max_weight ?? '') }}"
                                   class="w-full bg-white border border-outline-variant rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="font-label-md text-on-surface-variant text-xs">الأولوية</label>
                        <input type="number" name="product_shipping_rules[priority]" min="0" max="999"
                               value="{{ old('product_shipping_rules.priority', $sr->priority ?? '0') }}"
                               class="w-full bg-white border border-outline-variant rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary outline-none">
                    </div>
                    <div class="space-y-2 pt-2 border-t border-outline-variant/30">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="product_shipping_rules[fragile]" value="1" {{ old('product_shipping_rules.fragile', $sr->fragile ?? false) ? 'checked' : '' }}
                                   class="w-4 h-4 text-primary rounded">
                            <span class="text-sm text-on-surface-variant">قابل للكسر (Fragile)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="product_shipping_rules[hazardous]" value="1" {{ old('product_shipping_rules.hazardous', $sr->hazardous ?? false) ? 'checked' : '' }}
                                   class="w-4 h-4 text-primary rounded">
                            <span class="text-sm text-on-surface-variant">مواد خطرة (Hazardous)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="product_shipping_rules[requires_signature]" value="1" {{ old('product_shipping_rules.requires_signature', $sr->requires_signature ?? false) ? 'checked' : '' }}
                                   class="w-4 h-4 text-primary rounded">
                            <span class="text-sm text-on-surface-variant">توقيع عند الاستلام</span>
                        </label>
                    </div>
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

@push('scripts')
<script>
    function productOptionsManager() {
        return {
            options: {!! json_encode(old('options', $product->options->map(function ($opt) {
                return [
                    'name' => $opt->name,
                    'type' => $opt->type,
                    'required' => $opt->required,
                    'values' => $opt->values->map(function ($val) {
                        return [
                            'value' => $val->value,
                            'color_code' => $val->color_code,
                            'price_adjustment' => $val->price_adjustment,
                            'stock' => $val->stock,
                        ];
                    })->toArray()
                ];
            })->toArray())) !!}.map((opt, idx) => {
                return {
                    id: 'opt_' + idx,
                    name: opt.name || '',
                    type: opt.type || 'select',
                    required: opt.required == 1,
                    values: (opt.values || []).map((val, vidx) => ({
                        id: 'val_' + idx + '_' + vidx,
                        value: val.value || '',
                        color_code: val.color_code || '#3b82f6',
                        price_adjustment: val.price_adjustment || '',
                        stock: val.stock || ''
                    }))
                };
            }) || [],
            optCounter: 100,
            valCounter: 1000,
            
            init() {
                // Done via expression
            },
            
            addOption() {
                this.optCounter++;
                this.options.push({
                    id: 'opt_' + this.optCounter,
                    name: '',
                    type: 'select',
                    required: false,
                    values: []
                });
            },
            
            removeOption(index) {
                this.options.splice(index, 1);
            },
            
            addValue(optIndex) {
                this.valCounter++;
                this.options[optIndex].values.push({
                    id: 'val_' + this.valCounter,
                    value: '',
                    color_code: '#3b82f6',
                    price_adjustment: '',
                    stock: ''
                });
            },
            
            removeValue(optIndex, valIndex) {
                this.options[optIndex].values.splice(valIndex, 1);
            }
        };
    }

    function productCustomFieldsManager() {
        return {
            fields: {!! json_encode(old('custom_fields', $product->customFields->map(function ($cf) {
                return [
                    'label' => $cf->label,
                    'type' => $cf->type,
                    'required' => $cf->required,
                    'price_effect' => $cf->price_effect,
                ];
            })->toArray())) !!}.map((cf, idx) => {
                return {
                    id: 'field_' + idx,
                    label: cf.label || '',
                    type: cf.type || 'text',
                    required: cf.required == 1,
                    price_effect: cf.price_effect || ''
                };
            }) || [],
            fieldCounter: 100,
            
            init() {
                // Done via expression
            },
            
            addField() {
                this.fieldCounter++;
                this.fields.push({
                    id: 'field_' + this.fieldCounter,
                    label: '',
                    type: 'text',
                    required: false,
                    price_effect: ''
                });
            },
            
            removeField(index) {
                this.fields.splice(index, 1);
            }
        };
    }
</script>
@endpush
