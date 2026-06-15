<?php $__env->startSection('title', $product->name); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold"><?php echo e($product->name); ?></h1>
        <p class="text-gray-600 text-sm mt-1">
            <a href="<?php echo e(route('admin.products.index')); ?>" class="text-blue-600 hover:underline">المنتجات</a>
            <span class="mx-1">/</span>
            <span><?php echo e($product->name); ?></span>
        </p>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?php echo e(route('admin.products.gallery', $product)); ?>" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm">
            <i class="fas fa-images ml-1"></i>إدارة المعرض (<?php echo e($product->images->count()); ?>)
        </a>
        <a href="<?php echo e(route('shop.show', $product->slug)); ?>" target="_blank" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm">
            <i class="fas fa-external-link-alt ml-1"></i>عرض في المتجر
        </a>
        <a href="<?php echo e(route('admin.products.edit', $product)); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
            <i class="fas fa-edit ml-1"></i>تعديل
        </a>
        <form action="<?php echo e(route('admin.products.destroy', $product)); ?>" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                <i class="fas fa-trash ml-1"></i>حذف
            </button>
        </form>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-bold text-lg mb-4"><i class="fas fa-image text-blue-600 ml-2"></i>الصور</h2>
            <?php if($product->images->count() > 0): ?>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="relative">
                            <img src="<?php echo e(asset('storage/' . $img->image)); ?>" alt="" class="w-full h-32 object-cover rounded-lg border <?php echo e($img->is_primary ? 'ring-2 ring-blue-500' : ''); ?>">
                            <?php if($img->is_primary): ?>
                                <span class="absolute top-1 right-1 bg-blue-600 text-white text-xs px-2 py-0.5 rounded">رئيسية</span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-12 text-gray-400">
                    <i class="fas fa-image text-4xl mb-2"></i>
                    <p>لا توجد صور</p>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-bold text-lg mb-4"><i class="fas fa-align-right text-blue-600 ml-2"></i>الوصف</h2>
            <?php if($product->short_description): ?>
                <p class="text-gray-700 mb-3 font-semibold"><?php echo e($product->short_description); ?></p>
            <?php endif; ?>
            <?php if($product->description): ?>
                <p class="text-gray-700 whitespace-pre-line"><?php echo e($product->description); ?></p>
            <?php else: ?>
                <p class="text-gray-400 text-center py-6">لا يوجد وصف</p>
            <?php endif; ?>
        </div>

        
        <?php if($product->variants->count() > 0): ?>
            <div class="bg-white rounded-xl shadow-sm p-5">
                <h2 class="font-bold text-lg mb-4"><i class="fas fa-layer-group text-blue-600 ml-2"></i>المتغيرات (<?php echo e($product->variants->count()); ?>)</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600 text-xs">
                            <tr>
                                <th class="px-3 py-2 text-right">الاسم</th>
                                <th class="px-3 py-2 text-right">SKU</th>
                                <th class="px-3 py-2 text-right">السعر</th>
                                <th class="px-3 py-2 text-right">المخزون</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $product->variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="border-t">
                                    <td class="px-3 py-2"><?php echo e($v->name); ?></td>
                                    <td class="px-3 py-2 font-mono text-xs"><?php echo e($v->sku ?? '—'); ?></td>
                                    <td class="px-3 py-2"><?php echo e(number_format($v->price ?? 0, 0)); ?></td>
                                    <td class="px-3 py-2"><?php echo e($v->stock ?? 0); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="space-y-6">
        
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-bold text-lg mb-4"><i class="fas fa-info-circle text-blue-600 ml-2"></i>معلومات سريعة</h2>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">السعر:</dt><dd class="font-bold"><?php echo e(number_format($product->price, 0)); ?></dd></div>
                <?php if($product->sale_price): ?>
                    <div class="flex justify-between"><dt class="text-gray-500">سعر التخفيض:</dt><dd class="font-bold text-red-600"><?php echo e(number_format($product->sale_price, 0)); ?></dd></div>
                <?php endif; ?>
                <div class="flex justify-between"><dt class="text-gray-500">SKU:</dt><dd class="font-mono text-xs"><?php echo e($product->sku ?? '—'); ?></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">المخزون:</dt><dd><span class="px-2 py-0.5 rounded text-xs <?php echo e($product->stock < 10 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'); ?>"><?php echo e($product->stock); ?> قطعة</span></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">التصنيف:</dt><dd><?php echo e($product->category->name ?? '—'); ?></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">النوع:</dt><dd>
                    <?php switch($product->type):
                        case ('simple'): ?> بسيط <?php break; ?>
                        <?php case ('variable'): ?> متغير <?php break; ?>
                        <?php case ('digital'): ?> رقمي <?php break; ?>
                        <?php case ('bundle'): ?> حزمة <?php break; ?>
                    <?php endswitch; ?>
                </dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">الحالة:</dt><dd>
                    <span class="px-2 py-0.5 rounded text-xs
                        <?php switch($product->status):
                            case ('active'): ?> bg-green-100 text-green-700 <?php break; ?>
                            <?php case ('inactive'): ?> bg-gray-100 text-gray-700 <?php break; ?>
                            <?php case ('draft'): ?> bg-yellow-100 text-yellow-700 <?php break; ?>
                        <?php endswitch; ?>">
                        <?php switch($product->status):
                            case ('active'): ?> نشط <?php break; ?>
                            <?php case ('inactive'): ?> غير نشط <?php break; ?>
                            <?php case ('draft'): ?> مسودة <?php break; ?>
                        <?php endswitch; ?>
                    </span>
                </dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">مميز:</dt><dd><?php echo $product->featured ? '<i class="fas fa-star text-yellow-500"></i> نعم' : 'لا'; ?></dd></div>
            </dl>
        </div>

        
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-bold text-lg mb-4"><i class="fas fa-clock text-blue-600 ml-2"></i>التواريخ</h2>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">أُنشئ:</dt><dd><?php echo e($product->created_at->format('Y-m-d H:i')); ?></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">آخر تحديث:</dt><dd><?php echo e($product->updated_at->format('Y-m-d H:i')); ?></dd></div>
            </dl>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\amarn\ecommerce\resources\views/admin/products/show.blade.php ENDPATH**/ ?>