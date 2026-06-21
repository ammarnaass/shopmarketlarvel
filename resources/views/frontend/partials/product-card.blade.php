{{-- Reusable product card --}}
@php
    $image = $product->primaryImage ?? $product->images->first();
    $hasDiscount = !empty($product->compare_price) && $product->compare_price > $product->price;
    $discount = $hasDiscount ? round((1 - $product->price / $product->compare_price) * 100) : 0;
    $isNew = $product->created_at && $product->created_at->gt(now()->subDays(7));
    $isLowStock = $product->stock > 0 && $product->stock <= 5;
    $isOutOfStock = $product->stock <= 0;
    $symbol = $symbol ?? currentCurrencySymbol();
@endphp

<div class="product-card group">
    {{-- Image --}}
    <a href="{{ route('shop.show', $product->slug) }}" class="block relative overflow-hidden">
        <div class="product-card-image">
            @if($image)
                <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $image->alt ?? $product->name }}" loading="lazy">
            @else
                <div class="w-full h-full flex items-center justify-center text-gray-300">
                    <span class="material-symbols-outlined text-4xl">image</span>
                </div>
            @endif
        </div>

        {{-- Badges overlay --}}
        <div class="absolute top-2 right-2 flex flex-col gap-1.5 z-10">
            @if($hasDiscount)
                <span class="badge badge-accent shadow-md">
                    <span class="material-symbols-outlined text-[10px]">local_offer</span> -{{ $discount }}%
                </span>
            @endif
            @if($isNew)
                <span class="badge badge-info shadow-md">
                    <span class="material-symbols-outlined text-[10px]">bolt</span> جديد
                </span>
            @endif
            @if($isOutOfStock)
                <span class="badge badge-danger shadow-md">
                    <span class="material-symbols-outlined text-[10px]">close</span> نفد
                </span>
            @elseif($isLowStock)
                <span class="badge badge-warning shadow-md">
                    <span class="material-symbols-outlined text-[10px]">local_fire_department</span> {{ $product->stock }} متبقي
                </span>
            @endif
        </div>

        {{-- Quick action overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-3 gap-2">
            <a href="{{ route('shop.show', $product->slug) }}" class="w-10 h-10 rounded-full bg-white text-gray-700 flex items-center justify-center hover:bg-brand-500 hover:text-white transition shadow-lg" title="عرض سريع">
                <span class="material-symbols-outlined text-sm">visibility</span>
            </a>
            @auth
                <form action="{{ route('wishlist.store') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="w-10 h-10 rounded-full bg-white text-gray-700 flex items-center justify-center hover:bg-accent-500 hover:text-white transition shadow-lg" title="إضافة للمفضلة">
                        <span class="material-symbols-outlined text-sm">favorite</span>
                    </button>
                </form>
            @endauth
        </div>
    </a>

    {{-- Body --}}
    <div class="p-4">
        {{-- Category --}}
        @if($product->category)
            <p class="text-xs text-brand-600 font-semibold mb-1">
                <span class="material-symbols-outlined text-[10px]">local_offer</span>
                {{ $product->category->name }}
            </p>
        @endif

        {{-- Name --}}
        <h3 class="font-semibold text-sm sm:text-base text-gray-800 mb-2 line-clamp-2 min-h-[2.5rem]">
            <a href="{{ route('shop.show', $product->slug) }}" class="hover:text-brand-600 transition-colors">
                {{ $product->name }}
            </a>
        </h3>

        {{-- Rating --}}
        @if($product->reviews_count ?? 0)
            <div class="flex items-center gap-1 mb-2">
                @php
                    $avg = $product->reviews_avg_rating ?? 0;
                    $full = floor($avg);
                    $half = ($avg - $full) >= 0.5;
                @endphp
                <div class="flex text-amber-400 text-xs">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $full)
                            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1">star</span>
                        @elseif($i == $full + 1 && $half)
                            <span class="material-symbols-outlined text-sm">star_half</span>
                        @else
                            <span class="material-symbols-outlined text-sm text-gray-300">star</span>
                        @endif
                    @endfor
                </div>
                <span class="text-xs text-gray-400">({{ $product->reviews_count }})</span>
            </div>
        @endif

        {{-- Price --}}
        <div class="flex items-end justify-between gap-2 mt-2">
            <div>
                <p class="text-lg font-extrabold gradient-text">
                    {{ number_format($product->price, 2) }}
                    <span class="text-xs font-normal text-gray-500">{{ $symbol }}</span>
                </p>
                @if($hasDiscount)
                    <p class="text-xs text-gray-400 line-through">
                        {{ number_format($product->compare_price, 2) }} {{ $symbol }}
                    </p>
                @endif
            </div>

            {{-- Add to cart --}}
            <form action="{{ route('cart.add') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit"
                        class="w-10 h-10 rounded-xl {{ $isOutOfStock ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-brand-50 text-brand-600 hover:bg-brand-500 hover:text-white' }} flex items-center justify-center transition-all duration-200 shadow-sm"
                        {{ $isOutOfStock ? 'disabled' : '' }}
                        title="{{ $isOutOfStock ? 'نفد المخزون' : 'أضف للسلة' }}">
                    @if($isOutOfStock)
                        <span class="material-symbols-outlined text-sm">block</span>
                    @else
                        <span class="material-symbols-outlined text-sm">add_shopping_cart</span>
                    @endif
                </button>
            </form>
        </div>
    </div>
</div>
