@extends('frontend.layout')

@section('title', __t('home.title', ['store' => config('app.name')]))
@section('description', __t('home.description'))

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
                    {{ site('hero_badge', __t('home.hero_badge')) }}
                </span>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight mb-6 text-balance">
                    {{ site('hero_title', __t('home.hero_title')) }}
                </h1>

                <p class="text-lg sm:text-xl mb-8 text-white/90 max-w-xl text-pretty">
                    {{ site('hero_subtitle', __t('home.hero_subtitle', ['amount' => config('ecommerce.shipping.free_threshold', 500), 'symbol' => currentCurrencySymbol()])) }}
                </p>

                <div class="flex gap-3 flex-wrap mb-10">
                    <a href="{{ route('shop.index') }}" class="btn-accent btn-lg shadow-accent">
                        <span class="material-symbols-outlined">shopping_cart</span>
                        {{ __t('nav.shop_now') }}
                    </a>
                    <a href="{{ route('shop.index') }}?featured=1" class="btn btn-lg bg-white/15 backdrop-blur-md border border-white/30 text-white hover:bg-white/25">
                        <span class="material-symbols-outlined">star</span>
                        {{ __t('nav.featured') }}
                    </a>
                </div>

                {{-- Trust badges --}}
                <div class="grid grid-cols-3 gap-4 max-w-lg">
                    <div class="flex flex-col items-center text-center gap-2 bg-white/10 backdrop-blur-md rounded-xl p-3">
                        <span class="material-symbols-outlined text-2xl text-accent-300">local_shipping</span>
                        <span class="text-xs font-medium">{{ __t('home.hero_trust_1') }}</span>
                    </div>
                    <div class="flex flex-col items-center text-center gap-2 bg-white/10 backdrop-blur-md rounded-xl p-3">
                        <span class="material-symbols-outlined text-2xl text-accent-300">shield</span>
                        <span class="text-xs font-medium">{{ __t('home.hero_trust_2') }}</span>
                    </div>
                    <div class="flex flex-col items-center text-center gap-2 bg-white/10 backdrop-blur-md rounded-xl p-3">
                        <span class="material-symbols-outlined text-2xl text-accent-300">undo</span>
                        <span class="text-xs font-medium">{{ __t('home.hero_trust_3') }}</span>
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
                                    <p class="text-xs font-bold text-brand-600">{{ number_format(convertPrice($p->price), 0) }} {{ currentCurrencySymbol() }}</p>
                                </div>
                            @empty
                                <div class="col-span-2 text-center py-8">
                                    <span class="material-symbols-outlined text-6xl text-white/30 mb-3">shopping_cart</span>
                                    <p class="text-white/70 text-sm">{{ __t('home.featured_empty') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Floating notification badges --}}
                    <div class="absolute -top-4 -right-4 bg-accent-500 text-white px-4 py-2 rounded-full shadow-accent text-sm font-bold animate-bounce-slow">
                        <span class="material-symbols-outlined">local_fire_department</span> {{ __t('home.best_seller') }}
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
                        <p class="font-semibold text-sm">{{ __t('home.free_shipping') }}</p>
                        <p class="text-xs text-gray-500">{{ __t('home.free_shipping_desc', ['amount' => config('ecommerce.shipping.free_threshold', 500)]) }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-accent-50 flex items-center justify-center text-accent-600 flex-shrink-0">
                    <span class="material-symbols-outlined text-lg">payments</span>
                </div>
                <div>
                    <p class="font-semibold text-sm">{{ __t('home.cod') }}</p>
                    <p class="text-xs text-gray-500">{{ __t('home.cod_desc') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-green-600 flex-shrink-0">
                    <span class="material-symbols-outlined text-lg">headphones</span>
                </div>
                <div>
                    <p class="font-semibold text-sm">{{ __t('home.support') }}</p>
                    <p class="text-xs text-gray-500">{{ __t('home.support_desc') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 flex-shrink-0">
                    <span class="material-symbols-outlined text-lg">workspace_premium</span>
                </div>
                <div>
                    <p class="font-semibold text-sm">{{ __t('home.authentic') }}</p>
                    <p class="text-xs text-gray-500">{{ __t('home.authentic_desc') }}</p>
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
                <span class="material-symbols-outlined">grid_view</span> {{ __t('home.browse_categories') }}
            </span>
            <h2 class="heading-2 mb-2">{{ __t('home.all_categories') }}</h2>
            <p class="text-gray-500">{{ __t('home.categories_count', ['count' => $categories->count()]) }}</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('shop.category', ['slug' => $category->slug]) }}"
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
                        <p class="text-xs text-gray-400 mt-1">{{ __t('home.products_count', ['count' => $category->products_count ?? $category->products()->count()]) }}</p>
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
                    <span class="material-symbols-outlined">local_fire_department</span> {{ __t('home.most_requested') }}
                </span>
                <h2 class="heading-2">{{ __t('home.featured_products') }}</h2>
                <p class="text-gray-500 mt-1">{{ __t('home.featured_subtitle') }}</p>
            </div>
            <a href="{{ route('shop.index') }}?featured=1" class="btn btn-secondary">
                {{ __t('shop.view_all') }}
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
                    <span class="material-symbols-outlined">bolt</span> {{ __t('home.just_arrived') }}
                </span>
                <h2 class="heading-2">{{ __t('home.latest_products') }}</h2>
                <p class="text-gray-500 mt-1">{{ __t('home.latest_subtitle') }}</p>
            </div>
            <a href="{{ route('shop.index') }}" class="btn btn-secondary">
                {{ __t('nav.browse_all') }}
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
        <div class="relative bg-gradient-to-l from-brand-700 via-accent-500 to-amber-500 rounded-3xl p-8 md:p-14 text-white overflow-hidden shadow-2xl transition-all duration-300 hover:shadow-3xl">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-white/10 rounded-full blur-xl"></div>
            <div class="absolute -bottom-24 -right-24 w-[30rem] h-[30rem] bg-white/5 rounded-full blur-xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>

            <div class="relative z-10 grid md:grid-cols-2 gap-10 items-center">
                <div>
                    <span class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-md px-4 py-2 rounded-full text-sm font-bold mb-4 shadow-lg animate-pulse">
                        <span class="material-symbols-outlined text-base">local_shipping</span> {{ __t('home.fast_shipping') }}
                    </span>
                    <h2 class="text-4xl md:text-5xl font-extrabold mb-4 text-balance leading-tight">
                        @if(site('banner_1_title'))
                            {{ site('banner_1_title') }}
                        @else
                            {{ __t('home.banner_1_title') }}
                        @endif
                    </h2>
                    <p class="text-white/90 text-lg md:text-xl mb-8 text-pretty leading-relaxed">
                        {{ site('banner_1_subtitle', __t('home.banner_1_subtitle')) }}
                    </p>
                    <a href="{{ site('banner_1_link') ?: route('shop.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-brand-700 font-bold text-base rounded-2xl shadow-xl hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300">
                        <span class="material-symbols-outlined">add_shopping_cart</span>
                        {{ __t('nav.shop_now') }}
                    </a>
                </div>
                <div class="hidden md:flex justify-center">
                    @if(site('banner_1_image'))
                        <img src="{{ site('banner_1_image') }}" alt="" class="rounded-2xl shadow-2xl max-w-md w-full object-cover hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="relative">
                            <div class="w-48 h-48 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center">
                                <span class="material-symbols-outlined text-8xl text-white/60">local_shipping</span>
                            </div>
                            <div class="absolute -top-4 -right-4 w-16 h-16 bg-accent-400 rounded-full flex items-center justify-center shadow-lg animate-bounce-slow">
                                <span class="material-symbols-outlined text-2xl text-white">local_fire_department</span>
                            </div>
                        </div>
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
                            <span class="material-symbols-outlined">arrow_back</span> {{ __t('nav.discover_more') }}
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
