<?php $__env->startSection('title', 'المنتجات'); ?>

<?php $__env->startSection('content'); ?>
<?php
    use App\Models\Product;
    $totalProducts = Product::count();
    $activeCount = Product::where('status', 'active')->count();
    $lowStockCount = Product::where('stock', '<', 10)->count();
    $totalStockValue = Product::sum(DB::raw('stock * price'));
?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold">المنتجات</h1>
        <p class="text-gray-600 text-sm mt-1">إدارة جميع المنتجات في المتجر</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?php echo e(route('admin.products.export')); ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">
            <i class="fas fa-download ml-1"></i> تصدير CSV
        </a>
        <a href="<?php echo e(route('admin.products.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <i class="fas fa-plus"></i>
            إضافة منتج جديد
        </a>
    </div>
</div>


<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">إجمالي المنتجات</p>
                <p class="text-2xl font-bold mt-1"><?php echo e(number_format($totalProducts)); ?></p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                <i class="fas fa-cube"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">منتجات نشطة</p>
                <p class="text-2xl font-bold mt-1 text-green-600"><?php echo e(number_format($activeCount)); ?></p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">مخزون منخفض</p>
                <p class="text-2xl font-bold mt-1 text-red-600"><?php echo e(number_format($lowStockCount)); ?></p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">قيمة المخزون</p>
                <p class="text-2xl font-bold mt-1"><?php echo e(number_format($totalStockValue, 0)); ?></p>
            </div>
            <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                <i class="fas fa-coins"></i>
            </div>
        </div>
    </div>
</div>


<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    <form method="GET" action="<?php echo e(route('admin.products.index')); ?>" class="flex flex-wrap items-end gap-3">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-semibold mb-1 text-gray-600">بحث</label>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="اسم، SKU..." class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
        </div>
        <div>
            <label class="block text-xs font-semibold mb-1 text-gray-600">الحالة</label>
            <select name="status" class="px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="">الكل</option>
                <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>نشط</option>
                <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>غير نشط</option>
                <option value="draft" <?php echo e(request('status') === 'draft' ? 'selected' : ''); ?>>مسودة</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold mb-1 text-gray-600">التصنيف</label>
            <select name="category_id" class="px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="">الكل</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category_id') == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
            <i class="fas fa-search ml-1"></i> تطبيق
        </button>
        <?php if(request()->hasAny(['search', 'status', 'category_id'])): ?>
            <a href="<?php echo e(route('admin.products.index')); ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm">إعادة تعيين</a>
        <?php endif; ?>
    </form>
</div>


<form method="POST" action="<?php echo e(route('admin.products.bulkAction')); ?>" id="bulkForm">
    <?php echo csrf_field(); ?>
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        
        <div class="bg-gray-50 px-4 py-3 border-b hidden" id="bulkBar">
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-600"><span id="selectedCount">0</span> منتج محدد</span>
                <select name="action" class="px-3 py-1.5 border rounded text-sm" required>
                    <option value="">اختر إجراء...</option>
                    <option value="activate">تفعيل</option>
                    <option value="deactivate">تعطيل</option>
                    <option value="feature">تمييز</option>
                    <option value="unfeature">إلغاء التمييز</option>
                    <option value="delete">حذف</option>
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-sm" onclick="return confirm('هل أنت متأكد من تنفيذ هذا الإجراء؟')">
                    <i class="fas fa-check ml-1"></i> تطبيق
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 text-xs">
                    <tr>
                        <th class="px-4 py-3 text-right">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300">
                        </th>
                        <th class="px-4 py-3 text-right">#</th>
                        <th class="px-4 py-3 text-right">المنتج</th>
                        <th class="px-4 py-3 text-right">SKU</th>
                        <th class="px-4 py-3 text-right">التصنيف</th>
                        <th class="px-4 py-3 text-right">السعر</th>
                        <th class="px-4 py-3 text-right">المخزون</th>
                        <th class="px-4 py-3 text-right">الحالة</th>
                        <th class="px-4 py-3 text-right">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <input type="checkbox" name="product_ids[]" value="<?php echo e($product->id); ?>" class="product-checkbox rounded border-gray-300">
                            </td>
                            <td class="px-4 py-3 text-gray-500"><?php echo e($product->id); ?></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <?php if($product->primaryImage): ?>
                                        <img src="<?php echo e(asset('storage/' . $product->primaryImage->image)); ?>" alt="" class="w-10 h-10 rounded object-cover">
                                    <?php else: ?>
                                        <div class="w-10 h-10 rounded bg-gray-100 flex items-center justify-center text-gray-400">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <a href="<?php echo e(route('admin.products.show', $product)); ?>" class="font-semibold text-blue-600 hover:underline line-clamp-1"><?php echo e($product->name); ?></a>
                                        <?php if($product->featured): ?>
                                            <i class="fas fa-star text-yellow-500 text-xs" title="منتج مميز"></i>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-600"><?php echo e($product->sku ?? '—'); ?></td>
                            <td class="px-4 py-3 text-gray-600"><?php echo e($product->category->name ?? '—'); ?></td>
                            <td class="px-4 py-3">
                                <?php if($product->sale_price): ?>
                                    <div>
                                        <span class="font-bold text-red-600"><?php echo e(number_format($product->sale_price, 0)); ?></span>
                                        <span class="text-xs text-gray-400 line-through"><?php echo e(number_format($product->price, 0)); ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="font-bold"><?php echo e(number_format($product->price, 0)); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs font-semibold <?php echo e($product->stock < 10 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'); ?>">
                                    <?php echo e($product->stock); ?>

                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs
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
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="<?php echo e(route('admin.products.gallery', $product)); ?>" class="text-purple-600 hover:text-purple-800" title="المعرض">
                                        <i class="fas fa-images"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.products.show', $product)); ?>" class="text-blue-600 hover:text-blue-800" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.products.edit', $product)); ?>" class="text-green-600 hover:text-green-800" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin.products.destroy', $product)); ?>" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                                <p>لا توجد منتجات</p>
                                <a href="<?php echo e(route('admin.products.create')); ?>" class="text-blue-600 hover:underline text-sm mt-2 inline-block">إضافة أول منتج</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($products->hasPages()): ?>
            <div class="p-4 border-t">
                <?php echo e($products->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</form>

<?php $__env->startPush('scripts'); ?>
<script>
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const bulkBar = document.getElementById('bulkBar');
    const selectedCount = document.getElementById('selectedCount');

    selectAll?.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateBulkBar();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkBar);
    });

    function updateBulkBar() {
        const count = document.querySelectorAll('.product-checkbox:checked').length;
        selectedCount.textContent = count;
        bulkBar.classList.toggle('hidden', count === 0);
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\amarn\ecommerce\resources\views/admin/products/index.blade.php ENDPATH**/ ?>