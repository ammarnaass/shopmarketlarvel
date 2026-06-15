@extends('frontend.layout')

@section('title', 'المتجر - ' . site('store_name'))
@section('description', 'تصفح جميع المنتجات في ' . site('store_name'))

@section('content')

<div class="bg-gradient-to-l from-brand-600 via-brand-500 to-accent-500 text-white py-10 md:py-14">
    <div class="container-app">
        <nav class="flex items-center gap-2 text-sm text-white/80 mb-4">
            <a href="{{ route('home') }}" class="hover:text-white transition flex items-center gap-1">
                <span class="material-symbols-outlined text-xs">home</span>
                الرئيسية
            </a>
            <span class="material-symbols-outlined text-[10px] text-white/50">chevron_right</span>
            <span class="text-white font-medium">المتجر</span>
        </nav>
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl border border-white/30">
                <span class="material-symbols-outlined">storefront</span>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold mb-1">المتجر</h1>
                <p class="text-white/90">جميع المنتجات <span class="opacity-75">({{ $products->total() }})</span></p>
            </div>
        </div>
    </div>
</div>

<div class="container-app py-8 md:py-10">
    <div class="grid lg:grid-cols-4 gap-6">
        {{-- ============ SIDEBAR FILTERS ============ --}}
        <aside class="lg:col-span-1">
            <div class="card sticky top-24 animate-fade-up">
                <div class="card-body p-5">
                    <h3 class="font-bold text-base mb-4 flex items-center gap-2 text-gray-800">
                        <span class="material-symbols-outlined text-brand-600">filter_list</span>
                        تصفية المنتجات
                    </h3>

                    <div class="mb-6">
                        <h4 class="font-semibold text-sm text-gray-700 mb-3 flex items-center gap-2">
                            <span class="material-symbols-outlined text-xs text-gray-400">local_offer</span>
                            التصنيفات
                        </h4>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('shop.index') }}"
                                   class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition
                                          {{ !request('category_id') && !request('category') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span><span class="material-symbols-outlined text-xs ml-1.5">grid_view</span>جميع المنتجات</span>
                                </a>
                            </li>
                            @foreach($categories as $cat)
                                <li>
                                    <a href="{{ route('shop.category', $cat->slug) }}"
                                       class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition
                                              {{ request('category') == $cat->slug ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>{{ $cat->name }}</span>
                                        <span class="text-xs px-1.5 py-0.5 rounded {{ request('category') == $cat->slug ? 'bg-brand-100 text-brand-700' : 'bg-gray-100 text-gray-500' }}">
                                            {{ $cat->products()->count() }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="border-t border-gray-100 pt-5">
                        <h4 class="font-semibold text-sm text-gray-700 mb-3 flex items-center gap-2">
                            <span class="material-symbols-outlined text-xs text-gray-400">payments</span>
                            السعر ({{ currentCurrencySymbol() }})
                        </h4>
                        <form method="GET">
                            <div class="flex gap-2 mb-3">
                                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="من"
                                       class="form-input text-sm flex-1">
                                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="إلى"
                                       class="form-input text-sm flex-1">
                            </div>
                            <button type="submit" class="btn-primary btn-sm btn-block">
                                <span class="material-symbols-outlined">check</span>
                                تطبيق
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        {{-- ============ PRODUCTS GRID ============ --}}
        <div class="lg:col-span-3">
            <div class="flex items-center justify-between mb-6 bg-white rounded-xl p-3 shadow-sm flex-wrap gap-3">
                <p class="text-sm text-gray-600">
                    <span class="font-semibold text-gray-800">{{ $products->total() }}</span> منتج
                </p>
                <select onchange="window.location.href = this.value"
                        class="form-select text-sm py-2 pr-4 pl-9 max-w-xs">
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'dir' => 'desc']) }}" {{ request('sort') == 'created_at' ? 'selected' : '' }}>الأحدث</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price', 'dir' => 'asc']) }}" {{ request('sort') == 'price' && request('dir') == 'asc' ? 'selected' : '' }}>السعر: الأقل</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price', 'dir' => 'desc']) }}" {{ request('sort') == 'price' && request('dir') == 'desc' ? 'selected' : '' }}>السعر: الأعلى</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'name', 'dir' => 'asc']) }}" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم</option>
                </select>
            </div>

            @if($products->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-5">
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
                        <h3 class="text-2xl font-bold mb-2">لا توجد منتجات</h3>
                        <p class="text-gray-500 mb-6">جرب تغيير معايير البحث أو التصنيف</p>
                        <a href="{{ route('shop.index') }}" class="btn-primary inline-flex">
                            <span class="material-symbols-outlined">grid_view</span>
                            عرض جميع المنتجات
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
