
<?php
    $image = $product->primaryImage ?? $product->images->first();
    $hasDiscount = !empty($product->compare_price) && $product->compare_price > $product->price;
    $discount = $hasDiscount ? round((1 - $product->price / $product->compare_price) * 100) : 0;
    $isNew = $product->created_at && $product->created_at->gt(now()->subDays(7));
    $isLowStock = $product->stock > 0 && $product->stock <= 5;
    $isOutOfStock = $product->stock <= 0;
    $symbol = $symbol ?? currentCurrencySymbol();
?>

<div class="product-card group">
    
    <a href="<?php echo e(route('shop.show', $product->slug)); ?>" class="block relative overflow-hidden">
        <div class="product-card-image">
            <?php if($image): ?>
                <img src="<?php echo e(asset('storage/' . $image->path)); ?>" alt="<?php echo e($image->alt ?? $product->name); ?>" loading="lazy">
            <?php else: ?>
                <div class="w-full h-full flex items-center justify-center text-gray-300">
                    <span class="material-symbols-outlined text-4xl">image</span>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="absolute top-2 right-2 flex flex-col gap-1.5 z-10">
            <?php if($hasDiscount): ?>
                <span class="badge badge-accent shadow-md">
                    <span class="material-symbols-outlined text-[10px]">local_offer</span> -<?php echo e($discount); ?>%
                </span>
            <?php endif; ?>
            <?php if($isNew): ?>
                <span class="badge badge-info shadow-md">
                    <span class="material-symbols-outlined text-[10px]">bolt</span> جديد
                </span>
            <?php endif; ?>
            <?php if($isOutOfStock): ?>
                <span class="badge badge-danger shadow-md">
                    <span class="material-symbols-outlined text-[10px]">close</span> نفد
                </span>
            <?php elseif($isLowStock): ?>
                <span class="badge badge-warning shadow-md">
                    <span class="material-symbols-outlined text-[10px]">local_fire_department</span> <?php echo e($product->stock); ?> متبقي
                </span>
            <?php endif; ?>
        </div>

        
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-3 gap-2">
            <a href="<?php echo e(route('shop.show', $product->slug)); ?>" class="w-10 h-10 rounded-full bg-white text-gray-700 flex items-center justify-center hover:bg-brand-500 hover:text-white transition shadow-lg" title="عرض سريع">
                <span class="material-symbols-outlined text-sm">visibility</span>
            </a>
            <?php if(auth()->guard()->check()): ?>
                <form action="<?php echo e(route('wishlist.store')); ?>" method="POST" class="inline">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                    <button type="submit" class="w-10 h-10 rounded-full bg-white text-gray-700 flex items-center justify-center hover:bg-accent-500 hover:text-white transition shadow-lg" title="إضافة للمفضلة">
                        <span class="material-symbols-outlined text-sm">favorite</span>
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </a>

    
    <div class="p-4">
        
        <?php if($product->category): ?>
            <p class="text-xs text-brand-600 font-semibold mb-1">
                <span class="material-symbols-outlined text-[10px]">local_offer</span>
                <?php echo e($product->category->name); ?>

            </p>
        <?php endif; ?>

        
        <h3 class="font-semibold text-sm sm:text-base text-gray-800 mb-2 line-clamp-2 min-h-[2.5rem]">
            <a href="<?php echo e(route('shop.show', $product->slug)); ?>" class="hover:text-brand-600 transition-colors">
                <?php echo e($product->name); ?>

            </a>
        </h3>

        
        <?php if($product->reviews_count ?? 0): ?>
            <div class="flex items-center gap-1 mb-2">
                <?php
                    $avg = $product->reviews_avg_rating ?? 0;
                    $full = floor($avg);
                    $half = ($avg - $full) >= 0.5;
                ?>
                <div class="flex text-amber-400 text-xs">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <?php if($i <= $full): ?>
                            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1">star</span>
                        <?php elseif($i == $full + 1 && $half): ?>
                            <span class="material-symbols-outlined text-sm">star_half</span>
                        <?php else: ?>
                            <span class="material-symbols-outlined text-sm text-gray-300">star</span>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
                <span class="text-xs text-gray-400">(<?php echo e($product->reviews_count); ?>)</span>
            </div>
        <?php endif; ?>

        
        <div class="flex items-end justify-between gap-2 mt-2">
            <div>
                <p class="text-lg font-extrabold gradient-text">
                    <?php echo e(number_format($product->price, 2)); ?>

                    <span class="text-xs font-normal text-gray-500"><?php echo e($symbol); ?></span>
                </p>
                <?php if($hasDiscount): ?>
                    <p class="text-xs text-gray-400 line-through">
                        <?php echo e(number_format($product->compare_price, 2)); ?> <?php echo e($symbol); ?>

                    </p>
                <?php endif; ?>
            </div>

            
            <form action="<?php echo e(route('cart.add')); ?>" method="POST" class="inline">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                <input type="hidden" name="quantity" value="1">
                <button type="submit"
                        class="w-10 h-10 rounded-xl <?php echo e($isOutOfStock ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-brand-50 text-brand-600 hover:bg-brand-500 hover:text-white'); ?> flex items-center justify-center transition-all duration-200 shadow-sm"
                        <?php echo e($isOutOfStock ? 'disabled' : ''); ?>

                        title="<?php echo e($isOutOfStock ? 'نفد المخزون' : 'أضف للسلة'); ?>">
                    <?php if($isOutOfStock): ?>
                        <span class="material-symbols-outlined text-sm">block</span>
                    <?php else: ?>
                        <span class="material-symbols-outlined text-sm">add_shopping_cart</span>
                    <?php endif; ?>
                </button>
            </form>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\amarn\ecommerce\resources\views/frontend/partials/product-card.blade.php ENDPATH**/ ?>