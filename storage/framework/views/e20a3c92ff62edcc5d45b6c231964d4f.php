

<?php $__env->startSection('title', 'المتجر - ' . site('store_name')); ?>
<?php $__env->startSection('description', 'تصفح جميع المنتجات في ' . site('store_name')); ?>

<?php $__env->startSection('content'); ?>

<div class="bg-gradient-to-l from-brand-600 via-brand-500 to-accent-500 text-white py-10 md:py-14">
    <div class="container-app">
        <nav class="flex items-center gap-2 text-sm text-white/80 mb-4">
            <a href="<?php echo e(route('home')); ?>" class="hover:text-white transition flex items-center gap-1">
                <i class="fas fa-home text-xs"></i>
                الرئيسية
            </a>
            <i class="fas fa-chevron-left text-[10px] text-white/50"></i>
            <span class="text-white font-medium">المتجر</span>
        </nav>
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl border border-white/30">
                <i class="fas fa-store"></i>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold mb-1">المتجر</h1>
                <p class="text-white/90">جميع المنتجات <span class="opacity-75">(<?php echo e($products->total()); ?>)</span></p>
            </div>
        </div>
    </div>
</div>

<div class="container-app py-8 md:py-10">
    <div class="grid lg:grid-cols-4 gap-6">
        
        <aside class="lg:col-span-1">
            <div class="card sticky top-24 animate-fade-up">
                <div class="card-body p-5">
                    <h3 class="font-bold text-base mb-4 flex items-center gap-2 text-gray-800">
                        <i class="fas fa-filter text-brand-600"></i>
                        تصفية المنتجات
                    </h3>

                    <div class="mb-6">
                        <h4 class="font-semibold text-sm text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-tags text-xs text-gray-400"></i>
                            التصنيفات
                        </h4>
                        <ul class="space-y-1">
                            <li>
                                <a href="<?php echo e(route('shop.index')); ?>"
                                   class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition
                                          <?php echo e(!request('category_id') && !request('category') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-700 hover:bg-gray-50'); ?>">
                                    <span><i class="fas fa-th text-xs ml-1.5"></i>جميع المنتجات</span>
                                </a>
                            </li>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <a href="<?php echo e(route('shop.category', $cat->slug)); ?>"
                                       class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition
                                              <?php echo e(request('category') == $cat->slug ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-700 hover:bg-gray-50'); ?>">
                                        <span><?php echo e($cat->name); ?></span>
                                        <span class="text-xs px-1.5 py-0.5 rounded <?php echo e(request('category') == $cat->slug ? 'bg-brand-100 text-brand-700' : 'bg-gray-100 text-gray-500'); ?>">
                                            <?php echo e($cat->products()->count()); ?>

                                        </span>
                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>

                    <div class="border-t border-gray-100 pt-5">
                        <h4 class="font-semibold text-sm text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-coins text-xs text-gray-400"></i>
                            السعر (<?php echo e(currentCurrencySymbol()); ?>)
                        </h4>
                        <form method="GET">
                            <div class="flex gap-2 mb-3">
                                <input type="number" name="min_price" value="<?php echo e(request('min_price')); ?>" placeholder="من"
                                       class="form-input text-sm flex-1">
                                <input type="number" name="max_price" value="<?php echo e(request('max_price')); ?>" placeholder="إلى"
                                       class="form-input text-sm flex-1">
                            </div>
                            <button type="submit" class="btn-primary btn-sm btn-block">
                                <i class="fas fa-check"></i>
                                تطبيق
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        
        <div class="lg:col-span-3">
            <div class="flex items-center justify-between mb-6 bg-white rounded-xl p-3 shadow-sm flex-wrap gap-3">
                <p class="text-sm text-gray-600">
                    <span class="font-semibold text-gray-800"><?php echo e($products->total()); ?></span> منتج
                </p>
                <select onchange="window.location.href = this.value"
                        class="form-select text-sm py-2 pr-4 pl-9 max-w-xs">
                    <option value="<?php echo e(request()->fullUrlWithQuery(['sort' => 'created_at', 'dir' => 'desc'])); ?>" <?php echo e(request('sort') == 'created_at' ? 'selected' : ''); ?>>الأحدث</option>
                    <option value="<?php echo e(request()->fullUrlWithQuery(['sort' => 'price', 'dir' => 'asc'])); ?>" <?php echo e(request('sort') == 'price' && request('dir') == 'asc' ? 'selected' : ''); ?>>السعر: الأقل</option>
                    <option value="<?php echo e(request()->fullUrlWithQuery(['sort' => 'price', 'dir' => 'desc'])); ?>" <?php echo e(request('sort') == 'price' && request('dir') == 'desc' ? 'selected' : ''); ?>>السعر: الأعلى</option>
                    <option value="<?php echo e(request()->fullUrlWithQuery(['sort' => 'name', 'dir' => 'asc'])); ?>" <?php echo e(request('sort') == 'name' ? 'selected' : ''); ?>>الاسم</option>
                </select>
            </div>

            <?php if($products->count() > 0): ?>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-5">
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('frontend.partials.product-card', ['product' => $product, 'symbol' => currentCurrencySymbol()], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="mt-8">
                    <?php echo e($products->links()); ?>

                </div>
            <?php else: ?>
                <div class="card animate-fade-up">
                    <div class="card-body p-16 text-center">
                        <div class="w-24 h-24 mx-auto mb-6 rounded-2xl bg-gray-100 flex items-center justify-center">
                            <i class="fas fa-box-open text-5xl text-gray-300"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">لا توجد منتجات</h3>
                        <p class="text-gray-500 mb-6">جرب تغيير معايير البحث أو التصنيف</p>
                        <a href="<?php echo e(route('shop.index')); ?>" class="btn-primary inline-flex">
                            <i class="fas fa-th"></i>
                            عرض جميع المنتجات
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\amarn\ecommerce\resources\views/frontend/shop/index.blade.php ENDPATH**/ ?>