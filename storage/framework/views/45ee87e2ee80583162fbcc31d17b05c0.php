

<?php $__env->startSection('title', $category->name . ' - ' . site('store_name')); ?>
<?php $__env->startSection('description', $category->description ?? 'تصفح منتجات ' . $category->name); ?>

<?php $__env->startSection('content'); ?>

<section class="bg-gradient-to-l from-brand-600 via-brand-500 to-accent-500 text-white py-10 md:py-14 relative overflow-hidden">
    <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
    <div class="container-app relative z-10">
        <nav class="flex items-center gap-2 text-sm text-white/80 mb-4">
            <a href="<?php echo e(route('home')); ?>" class="hover:text-white transition flex items-center gap-1">
                <span class="material-symbols-outlined text-xs">home</span>
                الرئيسية
            </a>
            <span class="material-symbols-outlined text-[10px] text-white/50">chevron_right</span>
            <a href="<?php echo e(route('shop.index')); ?>" class="hover:text-white transition">المتجر</a>
            <span class="material-symbols-outlined text-[10px] text-white/50">chevron_right</span>
            <span class="text-white font-medium"><?php echo e($category->name); ?></span>
        </nav>
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl border border-white/30">
                <span class="material-symbols-outlined"><?php echo e($category->icon ?? 'local_offer'); ?></span>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold mb-1"><?php echo e($category->name); ?></h1>
                <?php if($category->description): ?>
                    <p class="text-white/90 text-base"><?php echo e($category->description); ?></p>
                <?php else: ?>
                    <p class="text-white/90 text-sm"><?php echo e($products->total()); ?> منتج في هذا التصنيف</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<div class="container-app py-8 md:py-10">
    <?php if($category->children->count() > 0): ?>
        <div class="card mb-6">
            <div class="card-body p-4">
                <div class="flex flex-wrap gap-2 items-center">
                    <span class="text-sm text-gray-600 ml-2 flex items-center gap-1">
                        <span class="material-symbols-outlined text-xs">account_tree</span>
                        التصنيفات الفرعية:
                    </span>
                    <a href="<?php echo e(route('shop.category', $category->slug)); ?>"
                       class="px-4 py-2 rounded-xl text-sm font-semibold bg-gradient-to-l from-brand-600 to-accent-500 text-white shadow-sm">
                        الكل
                    </a>
                    <?php $__currentLoopData = $category->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('shop.category', $child->slug)); ?>"
                           class="px-4 py-2 rounded-xl text-sm font-medium bg-gray-50 text-gray-700 hover:bg-gray-100 transition border border-gray-200">
                            <?php echo e($child->name); ?>

                            <span class="text-xs text-gray-400 mr-1">(<?php echo e($child->products()->count()); ?>)</span>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if($products->count() > 0): ?>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('frontend.partials.product-card', ['product' => $product, 'symbol' => currentCurrencySymbol()], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="mt-8"><?php echo e($products->links()); ?></div>
    <?php else: ?>
        <div class="card animate-fade-up">
            <div class="card-body p-16 text-center">
                <div class="w-24 h-24 mx-auto mb-6 rounded-2xl bg-gray-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-5xl text-gray-300">inventory_2</span>
                </div>
                <h3 class="text-2xl font-bold mb-2">لا توجد منتجات</h3>
                <p class="text-gray-500 mb-6">لا توجد منتجات في هذا التصنيف حالياً</p>
                <a href="<?php echo e(route('shop.index')); ?>" class="btn-primary inline-flex">
                    <span class="material-symbols-outlined">grid_view</span>
                    تصفح جميع المنتجات
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\amarn\ecommerce\resources\views/frontend/shop/category.blade.php ENDPATH**/ ?>