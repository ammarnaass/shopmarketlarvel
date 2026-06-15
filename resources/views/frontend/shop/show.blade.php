@extends('frontend.layout')

@section('title', $product->seo_title ?? $product->name . ' - ' . site('store_name'))
@section('description', $product->seo_description ?? $product->short_description ?? Str::limit(strip_tags($product->description), 160))
@section('og_type', 'product')

@php
    use Illuminate\Support\Str;
    $countries = config('ecommerce.countries', []);
    $defaultCountry = old('country_code', auth()->user()->country_code ?? session('selected_country', config('ecommerce.store.default_country', 'SD')));
    $defaultState = old('state_code', auth()->user()->defaultAddress?->state_code ?? '');
    $countrySymbol = $countries[$defaultCountry]['currency_symbol'] ?? config('ecommerce.store.currency_symbol', 'ج.س');
    $hasOptions = $product->options->count() > 0;
    $hasCustomFields = $product->customFields->count() > 0;
    $mainImage = $product->images->first();
    $imageList = $product->images->map(fn($i) => asset('storage/' . $i->image))->values();
    $authUserData = auth()->user() ? auth()->user()->only(['name', 'email', 'phone', 'country_code', 'state_code']) : [];
@endphp

@push('styles')
<style>
    .product-detail input[type="text"],
    .product-detail input[type="email"],
    .product-detail input[type="tel"],
    .product-detail input[type="number"],
    .product-detail select,
    .product-detail textarea {
        transition: border-color 0.15s, box-shadow 0.15s;
    }
    .product-detail input:focus,
    .product-detail select:focus,
    .product-detail textarea:focus {
        border-color: var(--color-primary, #3B82F6);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }
    .product-detail .summary-card {
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfeff 100%);
    }
    [x-cloak] { display: none !important; }
    .thumb-active { border-color: rgb(59 130 246) !important; }
    .price-pulse { animation: pricePulse 0.3s ease; }
    @keyframes pricePulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
</style>
@endpush

<div class="bg-gray-50 product-detail"
     x-data="instantBuyForm()"
     x-init='init(@json($product->id), @json($product->name), @json($product->price), @json($product->final_price), @json($product->sale_price), @json($imageList), @json($product->stock), @json($product->sku), @json($product->weight ?? 0), @json($countries), @json($defaultCountry), @json($defaultState), @json($countrySymbol), @json($authUserData))'>

    {{-- ============ BREADCRUMB ============ --}}
    <div class="bg-white border-b">
        <div class="container-app py-3">
            <nav class="flex items-center gap-2 text-sm text-gray-500 flex-wrap">
                <a href="{{ route('home') }}" class="hover:text-blue-600 flex items-center gap-1">
                    <span class="material-symbols-outlined ml-1">home</span>الرئيسية
                </a>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <a href="{{ route('shop.index') }}" class="hover:text-blue-600">المتجر</a>
                @if($product->category)
                    <span class="material-symbols-outlined text-xs">chevron_right</span>
                    <a href="{{ route('shop.category', $product->category->slug) }}" class="hover:text-blue-600">
                        {{ $product->category->name }}
                    </a>
                @endif
                <span class="material-symbols-outlined text-xs">chevron_right</span>
                <span class="text-gray-700 font-semibold truncate max-w-[200px]">{{ $product->name }}</span>
            </nav>
        </div>
    </div>

    <div class="container-app py-6 md:py-8">
        <form method="POST" action="{{ route('instant.submit') }}" @submit.prevent="submitForm($event)" id="instant-buy-form" class="grid lg:grid-cols-3 gap-6">
            @csrf
            <input type="hidden" name="product_id" :value="product.id">
            <input type="hidden" name="weight" :value="weight">

            {{-- ============ LEFT COLUMN: PRODUCT + OPTIONS ============ --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Product card --}}
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="grid md:grid-cols-2 gap-0">
                        {{-- Images --}}
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-4 md:p-6">
                            <div class="aspect-square bg-white rounded-xl overflow-hidden mb-3 relative border">
                                <template x-for="(img, i) in images" :key="i">
                                    <img :src="img" :alt="product.name"
                                         x-show="activeImage === i"
                                         x-transition.opacity.duration.300ms
                                         class="w-full h-full object-cover absolute inset-0"
                                         loading="lazy">
                                </template>
                                <template x-if="images.length === 0">
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <span class="material-symbols-outlined text-6xl">image</span>
                                    </div>
                                </template>
                                <template x-if="product.discountPercent > 0">
                                    <span class="absolute top-3 left-3 bg-gradient-to-l from-red-500 to-rose-600 text-white px-3 py-1.5 rounded-full text-sm font-extrabold shadow-lg z-10">
                                        -<span x-text="product.discountPercent"></span>%
                                    </span>
                                </template>
                            </div>
                            <template x-if="images.length > 1">
                                <div class="grid grid-cols-4 gap-2">
                                    <template x-for="(img, i) in images" :key="i">
                                        <button type="button" @click="activeImage = i"
                                                :class="activeImage === i ? 'thumb-active ring-2 ring-blue-200' : 'border-gray-200 hover:border-gray-400'"
                                                class="aspect-square rounded-lg overflow-hidden border-2 transition">
                                            <img :src="img" :alt="product.name" class="w-full h-full object-cover" loading="lazy">
                                        </button>
                                    </template>
                                </div>
                            </template>
                        </div>

                        {{-- Info --}}
                        <div class="p-5 md:p-6 flex flex-col">
                            <template x-if="product.categoryName">
                                <a :href="'/category/' + product.categorySlug" class="text-xs text-blue-600 font-bold uppercase tracking-wide mb-2 hover:underline">
                                    <span class="material-symbols-outlined ml-1">local_offer</span><span x-text="product.categoryName"></span>
                                </a>
                            </template>
                            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 leading-tight mb-3">{{ $product->name }}</h1>

                            {{-- Rating --}}
                            <div class="flex items-center gap-2 mb-4">
                                <div class="flex text-yellow-400 text-sm gap-0.5">
                                    <template x-for="i in 5" :key="i">
                                        <span class="material-symbols-outlined text-sm">star</span>
                                    </template>
                                </div>
                                <span class="text-xs text-gray-500">({{ $product->reviews->count() ?? 0 }} تقييم)</span>
                            </div>

                            {{-- Price (live) --}}
                            <div class="flex items-baseline gap-3 mb-4 flex-wrap">
                                <span class="text-3xl font-extrabold text-blue-600 price-pulse" :key="grandTotal">
                                    <span x-text="formatMoney(displayPrice)"></span>
                                    <span x-text="currencySymbol"></span>
                                </span>
                                <template x-if="product.salePrice && product.salePrice > 0 && product.salePrice < product.basePrice">
                                    <span class="text-lg text-gray-400 line-through" x-text="formatMoney(product.basePrice) + ' ' + currencySymbol"></span>
                                </template>
                                <template x-if="product.discountPercent > 0">
                                    <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-1 rounded-full">
                                        -<span x-text="product.discountPercent"></span>%
                                    </span>
                                </template>
                            </div>

                            <template x-if="product.shortDescription">
                                <p class="text-gray-600 text-sm leading-relaxed mb-4" x-text="product.shortDescription"></p>
                            </template>

                            {{-- Stock --}}
                            <div class="flex items-center gap-2 mb-4 text-sm">
                                <span class="material-symbols-outlined text-green-500">check_circle</span>
                                <span class="text-gray-700">متوفر (<span x-text="product.stock"></span> قطعة)</span>
                            </div>

                            {{-- Quantity --}}
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-sm font-semibold text-gray-700">الكمية:</span>
                                <div class="flex items-center border-2 border-gray-200 rounded-lg overflow-hidden">
                                    <button type="button" @click="quantity = Math.max(1, quantity - 1); recalculate()"
                                            class="w-10 h-10 hover:bg-gray-100 transition disabled:opacity-30"
                                            :disabled="quantity <= 1">−</button>
                                    <input type="number" name="quantity" x-model.number="quantity" min="1" :max="product.stock"
                                           @input="quantity = Math.max(1, Math.min(product.stock, quantity)); recalculate()"
                                           class="w-14 h-10 text-center font-bold border-0 focus:outline-none">
                                    <button type="button" @click="quantity = Math.min(product.stock, quantity + 1); recalculate()"
                                            class="w-10 h-10 hover:bg-gray-100 transition disabled:opacity-30"
                                            :disabled="quantity >= product.stock">+</button>
                                </div>
                            </div>

                            <template x-if="product.sku">
                                <div class="text-xs text-gray-500 mt-auto pt-3 border-t">
                                    <span class="font-semibold">SKU:</span> <span x-text="product.sku"></span>
                                    <span class="mx-2">•</span>
                                    <span class="font-semibold">الوزن:</span> <span x-text="product.weight + ' كجم'"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Options --}}
                @if($hasOptions)
                    <div class="bg-white rounded-2xl shadow-sm p-5">
                        <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-600">tune</span>
                            اختر المواصفات
                        </h2>
                        @foreach($product->options as $option)
                            <div class="mb-4 last:mb-0">
                                <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    {{ $option->name }}
                                    <span class="text-xs text-gray-500 font-normal"
                                          x-show="selectedOptions[{{ $option->id }}] && getOptionAdjustment({{ $option->id }}) !== 0"
                                          x-transition>
                                        (<span x-text="(getOptionAdjustment({{ $option->id }}) > 0 ? '+' : '') + formatMoney(getOptionAdjustment({{ $option->id }}))"></span>)
                                    </span>
                                </label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($option->values as $value)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="options[{{ $option->id }}]" value="{{ $value->id }}"
                                                   @change="selectedOptions[{{ $option->id }}] = $event.target.value; recalculate()"
                                                   :checked="String(selectedOptions[{{ $option->id }}]) === '{{ $value->id }}'"
                                                   class="peer sr-only">
                                            <span class="inline-block px-4 py-2 border-2 border-gray-200 rounded-lg text-sm font-semibold transition
                                                       peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700
                                                       hover:border-gray-400">
                                                {{ $value->value }}
                                                @if($value->price_adjustment > 0)
                                                    <span class="text-xs text-gray-500">(+{{ number_format($value->price_adjustment, 0) }})</span>
                                                @endif
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Custom Fields --}}
                @if($hasCustomFields)
                    <div class="bg-white rounded-2xl shadow-sm p-5">
                        <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-600">edit_note</span>
                            معلومات إضافية
                        </h2>
                        @foreach($product->customFields as $field)
                            <div class="mb-4 last:mb-0">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ $field->name }}
                                    @if($field->required)<span class="text-red-500">*</span>@endif
                                    <span class="text-xs text-gray-500 font-normal"
                                          x-show="{{ (float) $field->price_effect }} > 0">
                                        (+<span x-text="formatMoney({{ (float) $field->price_effect }})"></span>)
                                    </span>
                                </label>
                                @if($field->type === 'text')
                                    <input type="text" name="custom_text" x-model="customText" @input="recalculate()"
                                           class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg"
                                           placeholder="{{ $field->placeholder ?? 'أدخل ' . $field->name }}">
                                @elseif($field->type === 'textarea')
                                    <textarea name="custom_text" x-model="customText" @input="recalculate()" rows="3"
                                              class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg"
                                              placeholder="{{ $field->placeholder ?? '' }}"></textarea>
                                @elseif($field->type === 'file')
                                    <input type="file" name="custom_file" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg text-sm">
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Description Tabs --}}
                <div class="bg-white rounded-2xl shadow-sm p-5" x-data="{ tab: 'description' }">
                    <div class="flex border-b mb-4">
                        <button type="button" @click="tab = 'description'"
                                :class="tab === 'description' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'"
                                class="px-4 py-2 font-semibold border-b-2 transition flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">notes</span> الوصف
                        </button>
                        <button type="button" @click="tab = 'reviews'"
                                :class="tab === 'reviews' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'"
                                class="px-4 py-2 font-semibold border-b-2 transition flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">star</span> التقييمات ({{ $product->reviews->count() ?? 0 }})
                        </button>
                        <button type="button" @click="tab = 'shipping'"
                                :class="tab === 'shipping' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'"
                                class="px-4 py-2 font-semibold border-b-2 transition flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">local_shipping</span> الشحن
                        </button>
                    </div>
                    <div x-show="tab === 'description'" class="prose prose-sm max-w-none text-gray-700 leading-loose">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                    <div x-show="tab === 'reviews'" x-cloak>
                        <p class="text-gray-500 text-center py-8">لا توجد تقييمات بعد. كن أول من يقيم هذا المنتج!</p>
                    </div>
                    <div x-show="tab === 'shipping'" x-cloak>
                        <div class="space-y-3 text-sm text-gray-700">
                            <p class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-blue-600 mt-1">local_shipping</span>
                                <span>شحن سريع وآمن لكل المدن عبر شركات شحن معتمدة (أرامكس، سمسا، البريد السوداني، نوست إكسبرس، ياليدين).</span>
                            </p>
                            <p class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-green-600 mt-1">shield</span>
                                <span>الدفع عند الاستلام متاح في كل المدن بدون رسوم مسبقاً.</span>
                            </p>
                            <p class="flex items-start gap-2">
                                <span class="material-symbols-outlined text-amber-600 mt-1">schedule</span>
                                <span>الشحن العادي: 3-5 أيام. الشحن السريع: 1-2 يوم.</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ RIGHT COLUMN: ORDER FORM (sticky) ============ --}}
            <div class="lg:col-span-1">
                <div class="sticky top-4 space-y-4">

                    {{-- ===== Customer Info ===== --}}
                    <div class="bg-white rounded-2xl shadow-sm p-5">
                        <h2 class="text-base font-bold mb-4 flex items-center gap-2 text-gray-800">
                            <span class="material-symbols-outlined text-blue-600">account_circle</span>
                            بياناتك
                        </h2>
                        <div class="space-y-3">
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">الاسم الأول *</label>
                                    <input type="text" name="first_name" required x-model="form.first_name"
                                           class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm"
                                           placeholder="أحمد">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">اللقب *</label>
                                    <input type="text" name="last_name" required x-model="form.last_name"
                                           class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm"
                                           placeholder="محمد">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">البريد الإلكتروني</label>
                                <input type="email" name="email" x-model="form.email"
                                       class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm"
                                       placeholder="example@mail.com">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">رقم الهاتف *</label>
                                <div class="flex gap-1" dir="ltr">
                                    <input type="text" :value="dialCode" readonly
                                           class="w-16 px-2 py-2 border-2 border-gray-200 rounded-lg bg-gray-50 text-center font-semibold text-xs">
                                    <input type="tel" name="phone" required x-model="form.phone"
                                           class="flex-1 px-3 py-2 border-2 border-gray-200 rounded-lg text-sm"
                                           placeholder="5XXXXXXXX">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ===== Shipping Address ===== --}}
                    <div class="bg-white rounded-2xl shadow-sm p-5">
                        <h2 class="text-base font-bold mb-4 flex items-center gap-2 text-gray-800">
                            <span class="material-symbols-outlined text-blue-600">local_shipping</span>
                            عنوان الشحن
                        </h2>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">الدولة *</label>
                                <select name="country_code" required x-model="countryCode" @change="onCountryChange()"
                                        class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm bg-white">
                                    @foreach($countries as $code => $info)
                                        <option value="{{ $code }}">{{ $info['flag'] ?? '' }} {{ $info['name'] }} - {{ $info['name_en'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">الولاية / المحافظة</label>
                                <select name="state_code" x-model="stateCode"
                                        class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm bg-white">
                                    <option value="">— اختر —</option>
                                    <template x-for="(name, code) in currentStates" :key="code">
                                        <option :value="code" x-text="name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">المدينة *</label>
                                <input type="text" name="city" required x-model="city" @input.debounce.400ms="recalculate()"
                                       class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm"
                                       placeholder="الخرطوم">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">الحي / المنطقة</label>
                                <input type="text" name="district" x-model="form.district"
                                       class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm"
                                       placeholder="الرياض">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">العنوان التفصيلي *</label>
                                <textarea name="address" required x-model="form.address" rows="2"
                                          class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm"
                                          placeholder="الشارع، رقم المبنى، علامة مميزة..."></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">الرمز البريدي</label>
                                <input type="text" name="zip" x-model="form.zip"
                                       class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm"
                                       placeholder="11111">
                            </div>
                        </div>
                    </div>

                    {{-- ===== Delivery Type ===== --}}
                    <div class="bg-white rounded-2xl shadow-sm p-5">
                        <h2 class="text-base font-bold mb-3 flex items-center gap-2 text-gray-800">
                            <span class="material-symbols-outlined text-blue-600">route</span>
                            نوع التوصيل
                        </h2>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition"
                                   :class="deliveryType === 'home' ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:border-gray-400'">
                                <input type="radio" name="delivery_type" value="home" x-model="deliveryType" @change="recalculate()"
                                       class="w-4 h-4 text-blue-600">
                                <div class="flex-1">
                                    <div class="font-semibold text-sm flex items-center gap-2">
                                        <span class="material-symbols-outlined text-blue-600">home</span>
                                        توصيل للمنزل
                                    </div>
                                    <div class="text-xs text-gray-500">إلى عنوانك مباشرة</div>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition"
                                   :class="deliveryType === 'office' ? 'border-purple-600 bg-purple-50' : 'border-gray-200 hover:border-gray-400'">
                                <input type="radio" name="delivery_type" value="office" x-model="deliveryType" @change="recalculate()"
                                       class="w-4 h-4 text-purple-600">
                                <div class="flex-1">
                                    <div class="font-semibold text-sm flex items-center gap-2">
                                        <span class="material-symbols-outlined text-purple-600">business</span>
                                        استلام من مكتب الشركة
                                    </div>
                                    <div class="text-xs text-gray-500">من أقرب مكتب شحن إليك</div>
                                </div>
                                <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full font-bold">أرخص</span>
                            </label>
                        </div>
                    </div>

                    {{-- ===== Shipping Company ===== --}}
                    @if(isset($shippingCompanies) && $shippingCompanies->count() > 0)
                        <div class="bg-white rounded-2xl shadow-sm p-5">
                            <h2 class="text-base font-bold mb-3 flex items-center gap-2 text-gray-800">
                                <i class="fas fa-truck text-blue-600"></i>
                                شركة الشحن (اختياري)
                            </h2>
                            <select name="shipping_company_id" x-model="shippingCompanyId" @change="recalculate()"
                                    class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                <option value="">— تلقائي (أرخص/أسرع) —</option>
                                @foreach($shippingCompanies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">اتركه فارغاً للسماح للنظام باختيار الأنسب</p>
                        </div>
                    @endif

                    {{-- ===== Shipping Method ===== --}}
                    <div class="bg-white rounded-2xl shadow-sm p-5">
                        <h2 class="text-base font-bold mb-3 flex items-center gap-2 text-gray-800">
                            <i class="fas fa-shipping-fast text-blue-600"></i>
                            طريقة الشحن
                        </h2>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition"
                                   :class="shippingMethod === 'standard' ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:border-gray-400'">
                                <input type="radio" name="shipping_method" value="standard" x-model="shippingMethod" @change="recalculate()"
                                       class="w-4 h-4 text-blue-600">
                                <div class="flex-1">
                                    <div class="font-semibold text-sm">عادي (3-5 أيام)</div>
                                    <div class="text-xs text-gray-500">شحن قياسي</div>
                                </div>
                                <span class="text-sm font-bold"
                                      :class="shippingCost === 0 ? 'text-green-600' : 'text-gray-700'"
                                      x-text="shippingMethod === 'standard' ? (shippingCost === 0 ? 'مجاناً' : formatMoney(shippingCost) + ' ' + currencySymbol) : ''">
                                </span>
                            </label>
                            <label class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition"
                                   :class="shippingMethod === 'express' ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:border-gray-400'">
                                <input type="radio" name="shipping_method" value="express" x-model="shippingMethod" @change="recalculate()"
                                       class="w-4 h-4 text-blue-600">
                                <div class="flex-1">
                                    <div class="font-semibold text-sm">سريع (1-2 يوم)</div>
                                    <div class="text-xs text-gray-500">أولوية قصوى</div>
                                </div>
                                <span class="text-sm font-bold text-amber-600"
                                      x-text="shippingMethod === 'express' ? (shippingCost === 0 ? 'مجاناً' : formatMoney(shippingCost) + ' ' + currencySymbol) : (expressCost === 0 ? 'مجاناً' : formatMoney(expressCost) + ' ' + currencySymbol)">
                                </span>
                            </label>
                        </div>
                        <template x-if="shippingFree && subtotal > 0">
                            <p class="text-xs text-green-600 mt-2 flex items-center gap-1">
                                <i class="fas fa-gift"></i>
                                <span>مبروك! حصلت على شحن مجاني.</span>
                            </p>
                        </template>
                    </div>

                    {{-- ===== Payment Method ===== --}}
                    <div class="bg-white rounded-2xl shadow-sm p-5">
                        <h2 class="text-base font-bold mb-3 flex items-center gap-2 text-gray-800">
                            <i class="fas fa-credit-card text-blue-600"></i>
                            طريقة الدفع
                        </h2>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition"
                                   :class="paymentMethod === 'cod' ? 'border-green-600 bg-green-50' : 'border-gray-200 hover:border-gray-400'">
                                <input type="radio" name="payment_method" value="cod" x-model="paymentMethod" @change="recalculate()"
                                       class="w-4 h-4 text-green-600">
                                <div class="flex-1">
                                    <div class="font-semibold text-sm">الدفع عند الاستلام</div>
                                    <div class="text-xs text-gray-500">ادفع نقداً عند الاستلام</div>
                                </div>
                                <i class="fas fa-money-bill-wave text-green-600"></i>
                            </label>
                            <label class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition"
                                   :class="paymentMethod === 'bank' ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:border-gray-400'">
                                <input type="radio" name="payment_method" value="bank" x-model="paymentMethod" @change="recalculate()"
                                       class="w-4 h-4 text-blue-600">
                                <div class="flex-1">
                                    <div class="font-semibold text-sm">تحويل بنكي</div>
                                    <div class="text-xs text-gray-500">سيتم إرسال التفاصيل</div>
                                </div>
                                <i class="fas fa-university text-blue-600"></i>
                            </label>
                        </div>
                    </div>

                    {{-- ===== Notes ===== --}}
                    <div class="bg-white rounded-2xl shadow-sm p-5">
                        <h2 class="text-base font-bold mb-3 flex items-center gap-2 text-gray-800">
                            <i class="fas fa-note-sticky text-blue-600"></i>
                            ملاحظات (اختياري)
                        </h2>
                        <textarea name="notes" rows="2" x-model="form.notes"
                                  class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg text-sm"
                                  placeholder="أي ملاحظات خاصة بالطلب..."></textarea>
                    </div>

                    {{-- ===== Coupon ===== --}}
                    <div class="bg-white rounded-2xl shadow-sm p-5">
                        <h2 class="text-base font-bold mb-3 flex items-center gap-2 text-gray-800">
                            <i class="fas fa-ticket text-blue-600"></i>
                            كوبون خصم
                        </h2>
                        <div class="flex gap-2">
                            <input type="text" x-model="couponCode" placeholder="أدخل كود الكوبون"
                                   class="flex-1 px-3 py-2 border-2 border-gray-200 rounded-lg text-sm font-mono uppercase">
                            <button type="button" @click="applyCoupon()" :disabled="couponLoading || !couponCode"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold disabled:opacity-50">
                                <i x-show="!couponLoading" class="fas fa-check"></i>
                                <i x-show="couponLoading" class="fas fa-spinner fa-spin"></i>
                            </button>
                        </div>
                        <template x-if="couponMessage">
                            <p class="text-xs text-green-600 mt-2 flex items-center gap-1">
                                <i class="fas fa-check-circle"></i>
                                <span x-text="couponMessage"></span>
                            </p>
                        </template>
                        <template x-if="couponError">
                            <p class="text-xs text-red-600 mt-2 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>
                                <span x-text="couponError"></span>
                            </p>
                        </template>
                    </div>

                    {{-- ===== Order Summary (live) ===== --}}
                    <div class="summary-card border-2 border-green-200 rounded-2xl p-5">
                        <h2 class="text-base font-bold mb-3 flex items-center gap-2 text-gray-800">
                            <i class="fas fa-receipt text-green-600"></i>
                            ملخص الطلب
                            <span x-show="loading" class="text-xs text-gray-400 mr-auto">
                                <i class="fas fa-spinner fa-spin"></i> جاري الحساب...
                            </span>
                        </h2>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">المنتج (<span x-text="quantity"></span>×)</span>
                                <span class="font-semibold" x-text="formatMoney(subtotal) + ' ' + currencySymbol"></span>
                            </div>
                            <template x-if="discount > 0">
                                <div class="flex justify-between text-red-600">
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-tag text-xs"></i> الخصم
                                        <template x-if="appliedCoupon">
                                            <span class="badge bg-red-100 text-red-600 text-[10px] px-1.5 py-0 rounded mr-1" x-text="appliedCoupon.code"></span>
                                        </template>
                                    </span>
                                    <span class="font-semibold" x-text="'- ' + formatMoney(discount) + ' ' + currencySymbol"></span>
                                </div>
                            </template>
                            <div class="flex justify-between">
                                <span class="text-gray-600">الشحن (<span x-text="shippingMethod === 'express' ? 'سريع' : 'عادي'"></span>)</span>
                                <span class="font-semibold" :class="shippingCost === 0 ? 'text-green-600' : ''"
                                      x-text="shippingCost === 0 ? 'مجاناً' : formatMoney(shippingCost) + ' ' + currencySymbol"></span>
                            </div>
                            <template x-if="paymentMethod === 'cod' && codFee > 0">
                                <div class="flex justify-between text-amber-600">
                                    <span><i class="fas fa-money-bill text-xs ml-1"></i>رسوم الدفع عند الاستلام</span>
                                    <span class="font-semibold" x-text="formatMoney(codFee) + ' ' + currencySymbol"></span>
                                </div>
                            </template>
                            <hr class="border-green-200 my-2">
                            <div class="flex justify-between items-center">
                                <span class="text-base font-bold text-gray-800">الإجمالي</span>
                                <span class="text-2xl font-extrabold text-green-600 price-pulse" :key="grandTotal"
                                      x-text="formatMoney(grandTotal) + ' ' + currencySymbol"></span>
                            </div>
                        </div>
                    </div>

                    {{-- ===== Trust & Submit ===== --}}
                    <div class="space-y-3">
                        <div class="flex items-start gap-2 text-xs text-gray-500 px-2">
                            <i class="fas fa-shield-halved text-green-600 mt-0.5"></i>
                            <span>طلبك آمن ومُشفر. الدفع عند الاستلام متاح في كل المدن.</span>
                        </div>

                        <button type="submit" :disabled="submitting || !canSubmit"
                                class="w-full py-4 rounded-xl font-bold text-lg text-white shadow-lg transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                                :class="canSubmit ? 'bg-gradient-to-l from-blue-600 via-indigo-600 to-purple-600 hover:shadow-2xl hover:-translate-y-0.5' : 'bg-gray-400'">
                            <span x-show="!submitting">
                                <i class="fas fa-check-circle"></i>
                                <span>تأكيد الطلب — <span x-text="formatMoney(grandTotal) + ' ' + currencySymbol"></span></span>
                            </span>
                            <span x-show="submitting" class="flex items-center gap-2">
                                <i class="fas fa-spinner fa-spin"></i>
                                جاري الإرسال...
                            </span>
                        </button>

                        <div class="grid grid-cols-2 gap-2 text-xs text-gray-500">
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-shield-halved text-green-500"></i>
                                <span>دفع آمن</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-truck-fast text-blue-500"></i>
                                <span>شحن سريع</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-rotate-left text-purple-500"></i>
                                <span>إرجاع مضمون</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-headset text-amber-500"></i>
                                <span>دعم 24/7</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- ============ RELATED PRODUCTS ============ --}}
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <div class="container-app py-8 border-t">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <i class="fas fa-thumbs-up text-blue-600"></i>
                منتجات مشابهة
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($relatedProducts as $rel)
                    @include('frontend.partials.product-card', ['product' => $rel, 'symbol' => currentCurrencySymbol()])
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function instantBuyForm() {
    return {
        // === Product Data ===
        product: {
            id: null,
            name: '',
            basePrice: 0,
            salePrice: 0,
            finalPrice: 0,
            discountPercent: 0,
            stock: 0,
            sku: '',
            weight: 0,
            categoryName: '',
            categorySlug: '',
        },
        images: [],
        activeImage: 0,

        // === Options ===
        @if($hasOptions)
        selectedOptions: {!! json_encode($product->options->mapWithKeys(fn($o) => [$o->id => null])->toArray()) !!},
        optionsAdjustments: {!! json_encode($product->options->reduce(function ($carry, $o) { foreach ($o->values as $v) { $carry[(int) $v->id] = (float) $v->price_adjustment; } return $carry; }, [])) !!},
        @else
        selectedOptions: {},
        optionsAdjustments: {},
        @endif
        @if($hasCustomFields)
        customFieldPrice: {{ (float) ($product->customFields->whereIn('type', ['text', 'textarea'])->first()?->price_effect ?? 0) }},
        @else
        customFieldPrice: 0,
        @endif
        customText: '',

        // === Quantity & Location ===
        quantity: 1,
        countryCode: 'SD',
        stateCode: '',
        city: '',
        countries: {},
        currentStates: {},
        dialCode: '',
        weight: 0,

        // === Shipping & Payment ===
        shippingMethod: 'standard',
        deliveryType: 'home',
        shippingCompanyId: '',
        paymentMethod: 'cod',
        currencySymbol: 'ج.س',

        // === Pricing (live) ===
        subtotal: 0,
        shippingCost: 0,
        expressCost: 0,
        shippingFree: false,
        discount: 0,
        codFee: 0,
        grandTotal: 0,
        appliedCoupon: null,

        // === Form data ===
        form: {
            first_name: '',
            last_name: '',
            email: '',
            phone: '',
            address: '',
            district: '',
            zip: '',
            notes: '',
        },

        // === UI State ===
        submitting: false,
        loading: false,
        couponCode: '',
        couponLoading: false,
        couponMessage: '',
        couponError: '',
        calcTimer: null,
        authUser: null,

        get displayPrice() {
            // The unit price (per piece) for display in the product info
            return (this.product.finalPrice + this.getOptionsAdjustmentTotal() + (this.customText && this.customFieldPrice ? this.customFieldPrice : 0)) * this.quantity;
        },

        get canSubmit() {
            return this.city && this.form.first_name && this.form.last_name && this.form.phone && this.form.address;
        },

        init(id, name, basePrice, finalPrice, salePrice, images, stock, sku, weight, countries, defaultCountry, defaultState, defaultSymbol, authUser) {
            this.product.id = id;
            this.product.name = name;
            this.product.basePrice = parseFloat(basePrice) || 0;
            this.product.finalPrice = parseFloat(finalPrice) || parseFloat(basePrice) || 0;
            this.product.salePrice = parseFloat(salePrice) || 0;
            this.product.stock = parseInt(stock) || 0;
            this.product.sku = sku || '';
            this.product.weight = parseFloat(weight) || 0;
            this.product.discountPercent = this.product.basePrice > 0 && this.product.salePrice > 0 && this.product.salePrice < this.product.basePrice
                ? Math.round(100 - (this.product.salePrice / this.product.basePrice) * 100)
                : 0;
            this.images = images || [];
            this.countries = countries || {};
            this.countryCode = defaultCountry || 'SD';
            this.stateCode = defaultState || '';
            this.currencySymbol = countries[defaultCountry]?.currency_symbol || defaultSymbol || 'ج.س';
            this.dialCode = countries[defaultCountry]?.dial_code || '+249';
            this.authUser = authUser || null;
            @if($product->category)
            this.product.categoryName = @json($product->category->name);
            this.product.categorySlug = @json($product->category->slug);
            @endif
            // Pre-fill user data
            if (authUser && authUser.name) {
                const parts = authUser.name.split(' ');
                this.form.first_name = parts[0] || '';
                this.form.last_name = parts.slice(1).join(' ') || '';
                this.form.email = authUser.email || '';
                this.form.phone = authUser.phone || '';
            }

            // Initial calculation (without city)
            this.recalculate();
        },

        onCountryChange() {
            const info = this.countries[this.countryCode] || {};
            const dial = String(info.dial_code || '+249').replace(/^\+/, '');
            this.dialCode = '+' + dial;
            this.currencySymbol = info.currency_symbol || this.currencySymbol;
            this.stateCode = '';
            this.currentStates = info.states || {};
            this.recalculate();
        },

        getOptionAdjustment(optionId) {
            const valueId = this.selectedOptions[optionId];
            if (!valueId) return 0;
            return parseFloat(this.optionsAdjustments[valueId] || 0);
        },

        getOptionsAdjustmentTotal() {
            let total = 0;
            for (const [optionId, valueId] of Object.entries(this.selectedOptions)) {
                if (valueId) {
                    total += parseFloat(this.optionsAdjustments[valueId] || 0);
                }
            }
            return total;
        },

        recalculate() {
            clearTimeout(this.calcTimer);
            this.calcTimer = setTimeout(() => this.sendCalculate(), 300);
        },

        async sendCalculate() {
            this.loading = true;

            // Always show optimistic local calc
            this.subtotal = (this.product.finalPrice + this.getOptionsAdjustmentTotal()) * this.quantity
                + (this.customText && this.customFieldPrice ? this.customFieldPrice : 0);

            if (!this.city || !this.countryCode) {
                this.shippingCost = 0;
                this.expressCost = 0;
                this.grandTotal = this.subtotal - this.discount + this.codFee;
                this.loading = false;
                return;
            }

            try {
                const payload = {
                    product_id: this.product.id,
                    quantity: this.quantity,
                    country_code: this.countryCode,
                    city: this.city,
                    state_code: this.stateCode,
                    shipping_method: this.shippingMethod,
                    delivery_type: this.deliveryType,
                    shipping_company_id: this.shippingCompanyId || null,
                    coupon_code: this.appliedCoupon?.code || null,
                    custom_text: this.customText || null,
                };

                // Add options
                const opts = {};
                let hasOptions = false;
                for (const [k, v] of Object.entries(this.selectedOptions)) {
                    if (v) { opts[k] = v; hasOptions = true; }
                }
                if (hasOptions) payload.options = opts;

                const res = await fetch('{{ route('instant.calculate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify(payload),
                });

                const data = await res.json();
                if (data.success) {
                    this.subtotal = data.subtotal;
                    this.shippingCost = data.shipping_cost;
                    this.shippingFree = data.shipping_free;
                    this.discount = data.discount;
                    this.weight = data.weight;
                    this.currencySymbol = data.currency_symbol || this.currencySymbol;
                    this.grandTotal = data.total + (this.paymentMethod === 'cod' ? this.codFee : 0);

                    // Fetch the OTHER method's cost to show in the radio
                    if (this.shippingMethod === 'standard') {
                        const expressPayload = { ...payload, shipping_method: 'express' };
                        const expressRes = await fetch('{{ route('instant.calculate') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            body: JSON.stringify(expressPayload),
                        });
                        const expressData = await expressRes.json();
                        if (expressData.success) this.expressCost = expressData.shipping_cost;
                    } else {
                        const standardPayload = { ...payload, shipping_method: 'standard' };
                        const standardRes = await fetch('{{ route('instant.calculate') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            body: JSON.stringify(standardPayload),
                        });
                        const standardData = await standardRes.json();
                        if (standardData.success) this.expressCost = standardData.shipping_cost;
                    }
                }
            } catch (e) {
                console.warn('Calculate error:', e);
            } finally {
                this.loading = false;
            }
        },

        async applyCoupon() {
            if (!this.couponCode) return;
            this.couponLoading = true;
            this.couponError = '';
            this.couponMessage = '';

            try {
                const res = await fetch('{{ route('instant.coupon') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ code: this.couponCode, subtotal: this.subtotal }),
                });
                const data = await res.json();
                if (data.success && data.coupon) {
                    this.appliedCoupon = data.coupon;
                    this.couponMessage = data.coupon.description;
                    this.couponError = '';
                    this.recalculate();
                } else {
                    this.couponError = data.message || 'كود غير صالح';
                    this.appliedCoupon = null;
                }
            } catch (e) {
                this.couponError = 'حدث خطأ. حاول مرة أخرى.';
            } finally {
                this.couponLoading = false;
            }
        },

        removeCoupon() {
            this.appliedCoupon = null;
            this.couponCode = '';
            this.couponMessage = '';
            this.couponError = '';
            this.recalculate();
        },

        formatMoney(amount) {
            if (isNaN(amount) || amount === null || amount === undefined) amount = 0;
            return new Intl.NumberFormat('en-US', { maximumFractionDigits: 2, minimumFractionDigits: 0 }).format(Math.round(amount * 100) / 100);
        },

        async submitForm(event) {
            if (this.submitting) {
                event.preventDefault();
                return;
            }
            if (!this.canSubmit) {
                event.preventDefault();
                alert('يرجى إكمال جميع الحقول المطلوبة');
                return;
            }
            event.preventDefault();
            this.submitting = true;
            document.documentElement.classList.add('is-loading');

            try {
                const fd = new FormData(event.target);

                // Ensure radio buttons are set
                if (!fd.has('delivery_type')) fd.append('delivery_type', this.deliveryType);
                if (!fd.has('shipping_method')) fd.append('shipping_method', this.shippingMethod);
                if (!fd.has('payment_method')) fd.append('payment_method', this.paymentMethod);
                if (!fd.has('quantity')) fd.append('quantity', this.quantity);
                if (this.shippingCompanyId) fd.set('shipping_company_id', this.shippingCompanyId);

                const res = await fetch(event.target.action, {
                    method: 'POST',
                    body: fd,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                const contentType = res.headers.get('content-type') || '';
                if (contentType.includes('application/json')) {
                    const data = await res.json();
                    if (data.success) {
                        window.location.href = data.redirect || ('/order/' + data.order_number + '/thanks');
                    } else if (data.errors) {
                        const firstError = Object.values(data.errors)[0];
                        alert(firstError[0] || 'حدث خطأ في الطلب');
                        this.submitting = false;
                        document.documentElement.classList.remove('is-loading');
                    } else {
                        alert(data.message || 'حدث خطأ');
                        this.submitting = false;
                        document.documentElement.classList.remove('is-loading');
                    }
                } else if (res.redirected) {
                    window.location.href = res.url;
                } else {
                    window.location.href = event.target.action;
                }
            } catch (e) {
                console.error('Submit error:', e);
                alert('تعذّر إرسال الطلب. حاول مرة أخرى.');
                this.submitting = false;
                document.documentElement.classList.remove('is-loading');
            }
        },
    };
}
</script>
@endpush
