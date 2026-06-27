@extends('frontend.layout')

@section('title', __t('shop.page_title') . ' - ' . site('store_name'))
@section('description', __t('shop.browse_all_products') . ' ' . site('store_name'))

@section('content')

{{-- ========== BREADCRUMBS & BANNER ========== --}}
<section class="bg-gradient-to-l from-brand-600 via-brand-500 to-accent-500 text-white py-10 md:py-14 relative overflow-hidden">
    <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
    <div class="container-app relative z-10">
        <nav class="flex items-center gap-2 text-sm text-white/80 mb-4">
            <a href="{{ route('home') }}" class="hover:text-white transition flex items-center gap-1">
                <span class="material-symbols-outlined text-xs">home</span>
                {{ __t('shop.home') }}
            </a>
            <span class="material-symbols-outlined text-[10px] text-white/50">chevron_left</span>
            <span class="text-white font-medium">{{ __t('shop.page_title') }}</span>
        </nav>
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl border border-white/30">
                <span class="material-symbols-outlined">storefront</span>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold mb-1">{{ __t('shop.page_title') }}</h1>
                <p class="text-white/90 text-base">{{ __t('shop.browse_all_products_desc') }}</p>
            </div>
        </div>
    </div>
</section>

{{-- ========== MAIN LAYOUT ========== --}}
<div class="container-app py-8 md:py-10">
    <div class="grid lg:grid-cols-4 gap-gutter">
        {{-- ============ SIDEBAR FILTERS ============ --}}
        <aside class="lg:col-span-1">
            <div class="bg-white border border-outline-variant rounded-xl p-6 h-fit sticky top-24 shadow-sm animate-fade-up">
                <form method="GET" action="{{ route('shop.index') }}" class="space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-outline-variant">
                        <h2 class="font-title-lg text-title-lg text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-brand-600">filter_list</span>
                            {{ __t('shop.filter') }}
                        </h2>
                    </div>

                    {{-- Search Input inside sidebar --}}
                    <div>
                        <h3 class="font-label-md text-label-md mb-3">{{ __t('shop.quick_search') }}</h3>
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __t('shop.search_placeholder') }}"
                                   class="form-input text-sm pr-9 pl-3 py-2">
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline text-lg">search</span>
                        </div>
                    </div>

                    {{-- Categories list --}}
                    <div>
                        <h3 class="font-label-md text-label-md mb-3">{{ __t('shop.categories') }}</h3>
                        <div class="space-y-1 pr-1">
                            <a href="{{ route('shop.index') }}"
                               class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition
                                      {{ !request('category_id') && !request('category') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                <span class="flex items-center"><span class="material-symbols-outlined text-xs ml-1.5">grid_view</span>{{ __t('shop.all_products') }}</span>
                            </a>
                            @foreach($categories as $cat)
                                <a href="{{ route('shop.category', ['slug' => $cat->slug]) }}"
                                   class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition
                                          {{ request('category') == $cat->slug ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span>{{ $cat->name }}</span>
                                    <span class="text-xs px-1.5 py-0.5 rounded {{ request('category') == $cat->slug ? 'bg-brand-100 text-brand-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $cat->products()->count() }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Price range filter --}}
                    <div x-data="{ maxPrice: {{ request('max_price', 1000) }} }">
                        <h3 class="font-label-md text-label-md mb-3 flex items-center justify-between">
                            <span>{{ __t('shop.max_price') }}</span>
                            <span class="text-xs text-brand-600 font-bold" x-text="maxPrice + ' ' + @js(currentCurrencySymbol())"></span>
                        </h3>
                        <div class="px-1">
                            <input type="range" name="max_price" min="0" max="2000" step="50" x-model="maxPrice"
                                   class="w-full h-1.5 bg-surface-container rounded-lg appearance-none cursor-pointer accent-primary">
                            <div class="flex justify-between mt-2 text-[10px] text-on-surface-variant font-medium">
                                <span>0 {{ currentCurrencySymbol() }}</span>
                                <span>2000 {{ currentCurrencySymbol() }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Color filter --}}
                    <div>
                        <h3 class="font-label-md text-label-md mb-3">{{ __t('shop.colors') }}</h3>
                        <div class="flex flex-wrap gap-2.5">
                            @foreach([
                                'أحمر' => ['class' => 'bg-red-600', 'label' => __t('shop.category.color.red')],
                                'أزرق' => ['class' => 'bg-blue-600', 'label' => __t('shop.category.color.blue')],
                                'أسود' => ['class' => 'bg-black', 'label' => __t('shop.category.color.black')],
                                'أبيض' => ['class' => 'bg-white border border-outline-variant', 'label' => __t('shop.category.color.white')],
                                'أصفر' => ['class' => 'bg-yellow-400', 'label' => __t('shop.category.color.yellow')],
                                'أخضر' => ['class' => 'bg-green-600', 'label' => __t('shop.category.color.green')]
                            ] as $colorName => $colorInfo)
                                <label class="relative cursor-pointer active:scale-95 transition-transform">
                                    <input type="radio" name="color" value="{{ $colorName }}" {{ request('color') === $colorName ? 'checked' : '' }} class="peer sr-only">
                                    <span class="inline-block w-7 h-7 rounded-full {{ $colorInfo['class'] }} shadow-sm transition-all peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-primary" title="{{ $colorInfo['label'] }}"></span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Size filter --}}
                    <div>
                        <h3 class="font-label-md text-label-md mb-3">{{ __t('shop.size') }}</h3>
                        <div class="space-y-2.5">
                            @foreach([
                                'S' => 'Small (S)',
                                'M' => 'Medium (M)',
                                'L' => 'Large (L)',
                                'XL' => 'Extra Large (XL)',
                                'XXL' => 'Double Extra Large (XXL)'
                            ] as $sizeVal => $sizeLabel)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="sizes[]" value="{{ $sizeVal }}" {{ in_array($sizeVal, (array)request('sizes')) ? 'checked' : '' }} class="form-checkbox rounded text-primary focus:ring-primary border-outline-variant">
                                    <span class="text-body-sm text-on-surface-variant group-hover:text-on-surface transition-colors">{{ $sizeLabel }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Rating filter --}}
                    <div>
                        <h3 class="font-label-md text-label-md mb-3">{{ __t('shop.rating') }}</h3>
                        <div class="space-y-2.5">
                            @foreach([5, 4, 3] as $rating)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" name="rating" value="{{ $rating }}" {{ request('rating') == $rating ? 'checked' : '' }} class="form-radio text-primary border-outline-variant">
                                    <div class="flex text-yellow-500">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' {{ $i <= $rating ? 1 : 0 }}">star</span>
                                        @endfor
                                    </div>
                                    @if($rating < 5)
                                        <span class="text-body-sm text-on-surface-variant group-hover:text-on-surface transition-colors">{{ __t('shop.and_up') }}</span>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Submit & Reset Buttons --}}
                    <div class="flex flex-col gap-2 pt-2">
                        <button type="submit" class="w-full bg-primary text-white py-2.5 rounded-lg font-label-md text-label-md hover:bg-primary-container transition-all active:scale-[0.98] shadow-sm flex items-center justify-center gap-1.5">
                            <span class="material-symbols-outlined text-sm">check</span>
                            {{ __t('shop.apply_filters') }}
                        </button>
                        <a href="{{ route('shop.index') }}" class="w-full text-center border border-outline-variant text-on-surface-variant py-2 rounded-lg font-label-md text-label-md hover:bg-surface-container transition-all block">
                            {{ __t('shop.reset') }}
                        </a>
                    </div>
                </form>
            </div>
        </aside>

        {{-- ============ PRODUCTS GRID & HEADER ============ --}}
        <div class="lg:col-span-3">
            {{-- Header Bar --}}
            <div class="bg-white border border-outline-variant rounded-xl p-4 mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 shadow-sm">
                <div>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">{{ __t('shop.all_products') }} ({{ $products->total() }} {{ __t('shop.product') }})</h2>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    <div class="flex items-center gap-2 bg-surface-container-low px-3 py-1.5 rounded-lg border border-outline-variant text-sm">
                        <span class="text-xs text-on-surface-variant font-medium">{{ __t('shop.sort_by') }}:</span>
                        <select onchange="window.location.href = this.value" class="bg-transparent border-none focus:ring-0 text-xs font-bold pr-8 cursor-pointer py-0">
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'dir' => 'desc']) }}" {{ request('sort') == 'created_at' || !request('sort') ? 'selected' : '' }}>{{ __t('shop.sort_newest') }}</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price', 'dir' => 'asc']) }}" {{ request('sort') == 'price' && request('dir') == 'asc' ? 'selected' : '' }}>{{ __t('shop.sort_price_low') }}</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price', 'dir' => 'desc']) }}" {{ request('sort') == 'price' && request('dir') == 'desc' ? 'selected' : '' }}>{{ __t('shop.sort_price_high') }}</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'name', 'dir' => 'asc']) }}" {{ request('sort') == 'name' ? 'selected' : '' }}>{{ __t('shop.name') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            @if($products->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                    @foreach($products as $product)
                        @include('frontend.partials.product-card', ['product' => $product, 'symbol' => currentCurrencySymbol()])
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @else
                <div class="card animate-fade-up">
                    <div class="card-body p-16 text-center">
                        <div class="w-24 h-24 mx-auto mb-6 rounded-2xl bg-gray-100 flex items-center justify-center">
                            <span class="material-symbols-outlined text-5xl text-gray-300">inventory_2</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">{{ __t('shop.no_products') }}</h3>
                        <p class="text-gray-500 mb-6">{{ __t('shop.no_products_desc') }}</p>
                        <a href="{{ route('shop.index') }}" class="btn-primary inline-flex">
                            <span class="material-symbols-outlined">grid_view</span>
                            {{ __t('shop.reset_filters') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
