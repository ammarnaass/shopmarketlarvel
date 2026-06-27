@extends('frontend.layout')

@section('title', __t('wishlist.title') . ' - ' . site('store_name'))

@section('content')

<div class="container-app py-6">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-body-sm text-on-surface-variant mb-stack-lg">
        <a href="{{ route('home') }}" class="hover:text-primary transition-colors flex items-center gap-1">
            <span class="material-symbols-outlined text-xs">home</span>
            {{ __t('nav.home') }}
        </a>
        <span class="material-symbols-outlined text-[16px]">chevron_left</span>
        <span class="text-primary font-bold">{{ __t('wishlist.title') }}</span>
    </nav>

    {{-- Page Title --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10 border-b border-outline-variant pb-6">
        <div>
            <div class="flex items-center gap-3 text-primary mb-2">
                <span class="material-symbols-outlined text-headline-md" style="font-variation-settings: 'FILL' 1;">favorite</span>
                <h2 class="font-headline-md text-headline-md">{{ __t('wishlist.title') }}</h2>
            </div>
            <p class="text-on-surface-variant font-body-md">{{ __t('wishlist.you_have') }} <span class="font-bold text-on-surface">{{ $wishlists->count() }} @choice(__t('wishlist.product_singular') . '|' . __t('wishlist.product_plural'), $wishlists->count())</span> {{ __t('wishlist.in_wishlist') }}</p>
        </div>
        <div class="flex gap-3">
            <button class="flex items-center gap-2 px-6 py-2.5 bg-error-container text-on-error-container rounded-lg font-label-md hover:bg-error hover:text-on-error transition-all active:scale-95 shadow-sm" disabled>
                <span class="material-symbols-outlined text-[20px]">delete_sweep</span>
                {{ __t('wishlist.clear_all') }}
            </button>
            <a href="{{ route('shop.index') }}" class="flex items-center gap-2 px-6 py-2.5 bg-primary text-on-primary rounded-lg font-label-md hover:opacity-90 transition-all active:scale-95 shadow-md">
                <span class="material-symbols-outlined text-[20px]">bolt</span>
                {{ __t('wishlist.buy_all') }}
            </a>
        </div>
    </div>

    {{-- Wishlist Items --}}
    @if($wishlists->count() > 0)
        @php $symbol = currentCurrencySymbol(); @endphp

        <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-outline-variant">
            {{-- Table Header --}}
            <div class="hidden md:grid grid-cols-12 bg-surface-container-low px-6 py-4 font-label-md text-on-surface-variant border-b border-outline-variant">
                <div class="col-span-6">{{ __t('product.name') }}</div>
                <div class="col-span-2 text-center">{{ __t('product.price') }}</div>
                <div class="col-span-4 text-left">{{ __t('common.actions') }}</div>
            </div>

            {{-- Items --}}
            <div class="divide-y divide-outline-variant">
                @foreach($wishlists as $wishlist)
                    @php
                        $product = $wishlist->product;
                        $image = $product->primaryImage ?? $product->images->first();
                        $isOutOfStock = $product->stock <= 0;
                        $isLowStock = $product->stock > 0 && $product->stock <= 5;
                        $hasDiscount = !empty($product->compare_price) && $product->compare_price > $product->price;
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-12 items-center px-6 py-6 gap-4 hover:bg-surface-container-low transition-colors group">
                        {{-- Product Info --}}
                        <div class="col-span-1 md:col-span-6 flex items-center gap-4">
                            <div class="w-24 h-24 flex-shrink-0 bg-surface-container rounded-lg overflow-hidden border border-outline-variant">
                                @if($image)
                                    <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $image->alt ?? $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-on-surface-variant/30">
                                        <span class="material-symbols-outlined text-3xl">image</span>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-title-lg text-title-lg text-on-surface group-hover:text-primary transition-colors">
                                    <a href="{{ route('shop.show', ['slug' => $product->slug]) }}">{{ $product->name }}</a>
                                </h3>
                                <p class="text-body-sm text-on-surface-variant line-clamp-2 mt-1">{{ $product->short_description ?? ($product->description ? Str::limit(strip_tags($product->description), 80) : '') }}</p>
                                @if($isOutOfStock)
                                    <span class="inline-block mt-2 px-2 py-0.5 bg-error-container text-on-error-container text-[10px] font-bold rounded">{{ __t('common.out_of_stock') }}</span>
                                @elseif($isLowStock)
                                    <span class="inline-block mt-2 px-2 py-0.5 bg-warning-bg text-warning text-[10px] font-bold rounded">{{ __t('common.last_pieces', ['count' => $product->stock]) }}</span>
                                @else
                                    <span class="inline-block mt-2 px-2 py-0.5 bg-secondary-container text-on-secondary-fixed-variant text-[10px] font-bold rounded">{{ __t('common.in_stock') }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Price --}}
                        <div class="col-span-1 md:col-span-2 text-right md:text-center">
                            <span class="font-headline-sm text-headline-sm text-primary">{{ number_format(convertPrice($product->price), 2) }} {{ $symbol }}</span>
                            @if($hasDiscount)
                                <div class="text-body-sm text-on-surface-variant line-through">{{ number_format(convertPrice($product->compare_price), 2) }} {{ $symbol }}</div>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="col-span-1 md:col-span-4 flex items-center justify-end gap-3">
                            <form action="{{ route('wishlist.destroy', $product) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-on-surface-variant hover:text-error transition-colors rounded-full hover:bg-error-container/30" title="{{ __t('wishlist.remove') }}">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </form>
                            <a href="{{ route('shop.show', ['slug' => $product->slug]) }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-8 py-3 bg-primary text-on-primary rounded-full font-label-md hover:bg-on-primary-fixed-variant transition-all active:scale-95 shadow-md">
                                <span class="material-symbols-outlined text-[20px]">bolt</span>
                                {{ __t('wishlist.quick_buy') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $wishlists->links() }}
        </div>

    @else
        {{-- Empty State --}}
        <div class="card max-w-lg mx-auto animate-fade-up">
            <div class="card-body py-16 text-center">
                <div class="w-24 h-24 mx-auto mb-6 rounded-2xl bg-error-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-5xl text-on-error-container">favorite</span>
                </div>
                <h2 class="font-headline-md text-headline-md text-on-surface mb-2">{{ __t('wishlist.empty') }}</h2>
                <p class="text-on-surface-variant font-body-md mb-8">{{ __t('wishlist.empty_desc') }}</p>
                <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg inline-flex">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    {{ __t('wishlist.discover_products') }}
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
