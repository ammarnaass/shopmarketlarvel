@extends('frontend.layout')

@section('title', config('app.name') . ' - تسوق أفضل المنتجات بأسعار مميزة')
@section('description', 'متجر إلكتروني متكامل - شحن مجاني - الدفع عند الاستلام - تشكيلة واسعة من المنتجات في 6 دول عربية')

@section('content')

{{-- ========== HERO SECTION ========== --}}
<section class="relative overflow-hidden text-white {{ site('hero_image') ? '' : 'bg-gradient-to-bl from-brand-700 via-brand-600 to-brand-500' }}" @if(site('hero_image')) style="background-image: url('{{ site('hero_image') }}'); background-size: cover; background-position: center;" @endif>
    @if(site('hero_image'))
        {{-- Dark overlay for legibility --}}
        <div class="absolute inset-0 bg-gradient-to-bl from-black/60 via-black/40 to-black/30"></div>
    @endif
    {{-- Decorative background pattern --}}
    <div class="absolute inset-0 opacity-10">
        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
            <defs>
                <pattern id="hero-pattern" x="0" y="0" width="60" height="60" patternUnits="userSpaceOnUse">
                    <circle cx="30" cy="30" r="2" fill="white"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#hero-pattern)"/>
        </svg>
    </div>

    {{-- Floating shapes --}}
    <div class="absolute top-20 right-10 w-72 h-72 bg-accent-500/20 rounded-full blur-3xl animate-bounce-slow"></div>
    <div class="absolute bottom-10 left-20 w-96 h-96 bg-brand-400/30 rounded-full blur-3xl"></div>

    <div class="container-app relative z-10 py-16 md:py-24 lg:py-32">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div data-aos="fade-up">
                <span class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-md px-4 py-1.5 rounded-full text-sm mb-6">
                    <span class="material-symbols-outlined text-accent-300">auto_awesome</span>
                    {{ site('hero_badge', 'عروض حصرية تصل إلى 50%') }}
                </span>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight mb-6 text-balance">
                    {{ site('hero_title', 'تسوق بذكاء ، عش تجربة فريدة') }}
                </h1>

                <p class="text-lg sm:text-xl mb-8 text-white/90 max-w-xl text-pretty">
                    {{ site('hero_subtitle', 'اكتشف أحدث المنتجات بأفضل الأسعار مع شحن مجاني فوق ' . config('ecommerce.shipping.free_threshold', 500) . ' ' . currentCurrencySymbol()) }}
                </p>

                <div class="flex gap-3 flex-wrap mb-10">
                    <a href="{{ route('shop.index') }}" class="btn-accent btn-lg shadow-accent">
                        <span class="material-symbols-outlined">shopping_cart</span>
                        تسوق الآن
                    </a>
                    <a href="{{ route('shop.index') }}?featured=1" class="btn btn-lg bg-white/15 backdrop-blur-md border border-white/30 text-white hover:bg-white/25">
                        <span class="material-symbols-outlined">star</span>
                        المنتجات المميزة
                    </a>
                </div>

                {{-- Trust badges --}}
                <div class="grid grid-cols-3 gap-4 max-w-lg">
                    <div class="flex flex-col items-center text-center gap-2 bg-white/10 backdrop-blur-md rounded-xl p-3">
                        <span class="material-symbols-outlined text-2xl text-accent-300">local_shipping</span>
                        <span class="text-xs font-medium">شحن سريع</span>
                    </div>
                    <div class="flex flex-col items-center text-center gap-2 bg-white/10 backdrop-blur-md rounded-xl p-3">
                        <span class="material-symbols-outlined text-2xl text-accent-300">shield</span>
                        <span class="text-xs font-medium">دفع آمن</span>
                    </div>
                    <div class="flex flex-col items-center text-center gap-2 bg-white/10 backdrop-blur-md rounded-xl p-3">
                        <span class="material-symbols-outlined text-2xl text-accent-300">undo</span>
                        <span class="text-xs font-medium">إرجاع سهل</span>
                    </div>
                </div>
            </div>

            <div class="hidden md:block relative">
                <div class="relative">
                    {{-- Floating product cards --}}
                    <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 shadow-2xl">
                        <div class="grid grid-cols-2 gap-4">
                            @forelse($featuredProducts->take(4) as $i => $p)
                                <div class="bg-white rounded-2xl p-3 shadow-soft hover:scale-105 transition-transform duration-300 {{ $i % 2 == 0 ? 'rotate-[-3deg]' : 'rotate-[3deg]' }}">
                                    <div class="aspect-square bg-gray-100 rounded-xl mb-2 overflow-hidden">
                                        @if($p->primaryImage)
                                            <img src="{{ asset('storage/' . $p->primaryImage->image) }}" alt="{{ $p->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <span class="material-symbols-outlined text-gray-300 text-3xl">image</span>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="text-xs font-semibold text-gray-800 truncate">{{ $p->name }}</p>
                                    <p class="text-xs font-bold text-brand-600">{{ number_format($p->price, 0) }} {{ currentCurrencySymbol() }}</p>
                                </div>
                            @empty
                                <div class="col-span-2 text-center py-8">
                                    <span class="material-symbols-outlined text-6xl text-white/30 mb-3">shopping_cart</span>
                                    <p class="text-white/70 text-sm">منتجات مميزة قريباً</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Floating notification badges --}}
                    <div class="absolute -top-4 -right-4 bg-accent-500 text-white px-4 py-2 rounded-full shadow-accent text-sm font-bold animate-bounce-slow">
                        <span class="material-symbols-outlined">local_fire_department</span> الأكثر مبيعاً
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== MARQUEE FEATURES ========== --}}
<section class="bg-white border-y border-gray-100">
    <div class="container-app py-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-brand-50 flex items-center justify-center text-brand-600 flex-shrink-0">
                    <span class="material-symbols-outlined text-lg">local_shipping</span>
                </div>
                <div>
                    <p class="font-semibold text-sm">شحن مجاني</p>
                    <p class="text-xs text-gray-500">للطلبات فوق {{ config('ecommerce.shipping.free_threshold', 500) }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-accent-50 flex items-center justify-center text-accent-600 flex-shrink-0">
                    <span class="material-symbols-outlined text-lg">payments</span>
                </div>
                <div>
                    <p class="font-semibold text-sm">دفع عند الاستلام</p>
                    <p class="text-xs text-gray-500">بدون أي رسوم مسبقاً</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-green-600 flex-shrink-0">
                    <span class="material-symbols-outlined text-lg">headphones</span>
                </div>
                <div>
                    <p class="font-semibold text-sm">دعم 24/7</p>
                    <p class="text-xs text-gray-500">فريق متخصص لخدمتك</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 flex-shrink-0">
                    <span class="material-symbols-outlined text-lg">workspace_premium</span>
                </div>
                <div>
                    <p class="font-semibold text-sm">منتجات أصلية</p>
                    <p class="text-xs text-gray-500">ضمان الجودة 100%</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== CATEGORIES ========== --}}
@if($categories->count() > 0 && site('show_categories', '1') === '1')
<section class="section bg-gray-50">
    <div class="container-app">
        <div class="text-center mb-10">
            <span class="inline-block badge badge-primary mb-3">
                <span class="material-symbols-outlined">grid_view</span> تصفح حسب التصنيف
            </span>
            <h2 class="heading-2 mb-2">جميع التصنيفات</h2>
            <p class="text-gray-500">اختر من بين {{ $categories->count() }} تصنيف متوفر</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('shop.category', $category->slug) }}"
                   class="group relative bg-white rounded-2xl p-5 text-center transition-all duration-300 hover:-translate-y-1 border border-gray-100 hover:border-brand-200 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-brand-50 to-accent-50 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-gradient-to-br from-brand-100 to-brand-50 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 overflow-hidden">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                            @else
                                @categoryIcon($category->icon ?? 'local_offer', 'text-2xl text-brand-600')
                            @endif
                        </div>
                        <h3 class="font-semibold text-sm text-gray-800 group-hover:text-brand-700 transition-colors">{{ $category->name }}</h3>
                        <p class="text-xs text-gray-400 mt-1">{{ $category->products_count ?? $category->products()->count() }} منتج</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ========== FEATURED PRODUCTS ========== --}}
@if($featuredProducts->count() > 0 && site('show_featured', '1') === '1')
<section class="section">
    <div class="container-app">
        <div class="flex items-end justify-between mb-8 flex-wrap gap-4">
            <div>
                <span class="inline-block badge badge-accent mb-2">
                    <span class="material-symbols-outlined">local_fire_department</span> الأكثر طلباً
                </span>
                <h2 class="heading-2">منتجات مميزة</h2>
                <p class="text-gray-500 mt-1">أفضل المنتجات المختارة خصيصاً لك</p>
            </div>
            <a href="{{ route('shop.index') }}?featured=1" class="btn btn-secondary">
                عرض الكل
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach($featuredProducts as $product)
                @include('frontend.partials.product-card', ['product' => $product, 'symbol' => currentCurrencySymbol()])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ========== LATEST PRODUCTS ========== --}}
@if($latestProducts->count() > 0 && site('show_latest', '1') === '1')
<section class="section bg-white">
    <div class="container-app">
        <div class="flex items-end justify-between mb-8 flex-wrap gap-4">
            <div>
                <span class="inline-block badge badge-info mb-2">
                    <span class="material-symbols-outlined">bolt</span> وصل حديثاً
                </span>
                <h2 class="heading-2">أحدث المنتجات</h2>
                <p class="text-gray-500 mt-1">جديدنا هذا الأسبوع</p>
            </div>
            <a href="{{ route('shop.index') }}" class="btn btn-secondary">
                تصفح الكل
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach($latestProducts as $product)
                @include('frontend.partials.product-card', ['product' => $product, 'symbol' => currentCurrencySymbol()])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ========== CTA BANNER ========== --}}
<section class="section-sm">
    <div class="container-app">
        <div class="relative bg-gradient-to-l from-accent-600 via-accent-500 to-accent-400 rounded-3xl p-8 md:p-12 text-white overflow-hidden">
            <div class="absolute -top-20 -left-20 w-80 h-80 bg-white/10 rounded-full"></div>
            <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-white/5 rounded-full"></div>

            <div class="relative z-10 grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <span class="inline-block bg-white/20 backdrop-blur px-3 py-1 rounded-full text-sm font-semibold mb-3">
                        <span class="material-symbols-outlined">local_shipping</span> شحن سريع
                    </span>
                    <h2 class="text-3xl md:text-4xl font-extrabold mb-3 text-balance">
                        @if(site('banner_1_title'))
                            {{ site('banner_1_title') }}
                        @else
                            اشترِ الآن<br>واستلم خلال 24-48 ساعة
                        @endif
                    </h2>
                    <p class="text-white/90 text-lg mb-6 text-pretty">
                        {{ site('banner_1_subtitle', 'توصيل سريع لكل المدن في 6 دول - تتبع شحنتك لحظة بلحظة') }}
                    </p>
                    <a href="{{ site('banner_1_link') ?: route('shop.index') }}" class="btn btn-lg bg-white text-accent-600 hover:bg-gray-100">
                        <span class="material-symbols-outlined">shopping_cart</span>
                        تسوق الآن
                    </a>
                </div>
                <div class="hidden md:flex justify-center">
                    @if(site('banner_1_image'))
                        <img src="{{ site('banner_1_image') }}" alt="" class="rounded-2xl shadow-2xl max-w-md w-full object-cover">
                    @else
                        <span class="material-symbols-outlined text-9xl text-white/30">local_shipping</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@if(site('banner_2_title') || site('banner_2_image'))
<section class="section">
    <div class="container-app">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-l from-purple-600 to-pink-500 text-white p-8 md:p-12">
            <div class="relative z-10 grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <h2 class="text-3xl md:text-4xl font-extrabold mb-3">{{ site('banner_2_title') }}</h2>
                    <p class="text-white/90 text-lg mb-6">{{ site('banner_2_subtitle') }}</p>
                    @if(site('banner_2_link'))
                        <a href="{{ site('banner_2_link') }}" class="btn btn-lg bg-white text-purple-600 hover:bg-gray-100">
                            <span class="material-symbols-outlined">arrow_back</span> اكتشف المزيد
                        </a>
                    @endif
                </div>
                @if(site('banner_2_image'))
                    <div class="hidden md:flex justify-center">
                        <img src="{{ site('banner_2_image') }}" alt="" class="rounded-2xl shadow-2xl max-w-md w-full object-cover">
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

@push('scripts')
<script>
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
</script>
@endpush

@endsection
