<?php $__env->startSection('title', 'الطلبات'); ?>

<?php $__env->startSection('page_title', 'الطلبات'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-2">
    <p class="text-sm text-on-surface-variant">إدارة طلبات العملاء</p>
</div>


<form method="POST" action="<?php echo e(route('admin.orders.bulkAction')); ?>" id="bulkForm">
    <?php echo csrf_field(); ?>
    <div class="card p-4 mb-4 hidden" id="bulkBar">
        <div class="flex items-center gap-3 flex-wrap">
            <span class="text-sm text-on-surface-variant"><span id="selectedCount">0</span> طلب محدد</span>
            <select name="action" class="px-3 py-1.5 border border-outline-variant rounded-lg text-sm bg-surface-container-lowest" required>
                <option value="">اختر إجراء...</option>
                <option value="update_status">تحديث الحالة</option>
                <option value="delete">حذف</option>
                <option value="print_labels">طباعة بوليصات</option>
            </select>
            <div id="statusSelect" class="hidden">
                <select name="status" class="px-3 py-1.5 border border-outline-variant rounded-lg text-sm bg-surface-container-lowest" required>
                    <option value="pending">قيد الانتظار</option>
                    <option value="confirmed">مؤكد</option>
                    <option value="processing">قيد المعالجة</option>
                    <option value="shipped">تم الشحن</option>
                    <option value="delivered">تم التسليم</option>
                    <option value="cancelled">ملغي</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('هل أنت متأكد من تنفيذ هذا الإجراء على الطلبات المحددة؟')">
                <span class="material-symbols-outlined">check</span> تطبيق
            </button>
        </div>
    </div>
</form>


<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="kpi-card">
        <p class="text-xs font-medium text-on-surface-variant">إجمالي الطلبات</p>
        <p class="text-2xl font-bold mt-1 text-on-surface"><?php echo e(number_format($stats['total'])); ?></p>
    </div>
    <div class="kpi-card">
        <p class="text-xs font-medium text-on-surface-variant">في الانتظار</p>
        <p class="text-2xl font-bold mt-1 text-warning"><?php echo e(number_format($stats['pending'])); ?></p>
    </div>
    <div class="kpi-card">
        <p class="text-xs font-medium text-on-surface-variant">قيد المعالجة</p>
        <p class="text-2xl font-bold mt-1 text-primary"><?php echo e(number_format($stats['processing'])); ?></p>
    </div>
    <div class="kpi-card">
        <p class="text-xs font-medium text-on-surface-variant">إيرادات اليوم</p>
        <p class="text-2xl font-bold mt-1 text-emerald-600"><?php echo e(number_format($stats['today_revenue'], 0)); ?></p>
    </div>
</div>


<div class="card p-4 mb-6">
    <form method="GET" action="<?php echo e(route('admin.orders.index')); ?>" class="space-y-3">
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="form-label">بحث</label>
                <div class="relative">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="رقم الطلب، اسم العميل، البريد، الهاتف..."
                           class="form-input pr-10">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
                </div>
            </div>
            <div>
                <label class="form-label">حالة الطلب</label>
                <select name="status" class="form-select">
                    <option value="">الكل</option>
                    <?php $__currentLoopData = \App\Models\Order::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(request('status') === $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="form-label">حالة الدفع</label>
                <select name="payment_status" class="form-select">
                    <option value="">الكل</option>
                    <?php $__currentLoopData = \App\Models\Order::PAYMENT_STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(request('payment_status') === $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="form-label">من تاريخ</label>
                <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" class="form-input">
            </div>
            <div>
                <label class="form-label">إلى تاريخ</label>
                <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" class="form-input">
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
                <span class="material-symbols-outlined">filter_alt</span> تطبيق
            </button>
            <?php if(request()->hasAny(['search', 'status', 'payment_status', 'date_from', 'date_to'])): ?>
                <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-ghost btn-sm">إعادة تعيين</a>
            <?php endif; ?>
        </div>
    </form>
</div>


<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table-wrap">
            <thead>
                <tr>
                    <th class="w-10">
                        <input type="checkbox" id="selectAll" class="form-checkbox">
                    </th>
                    <th>رقم الطلب</th>
                    <th>العميل</th>
                    <th>النوع</th>
                    <th>المنتجات</th>
                    <th>الإجمالي</th>
                    <th>حالة الطلب</th>
                    <th>الدفع</th>
                    <th>التاريخ</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="order_ids[]" value="<?php echo e($order->id); ?>" class="product-checkbox form-checkbox">
                        </td>
                        <td>
                            <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="font-mono font-semibold text-primary hover:underline">
                                <?php echo e($order->order_number); ?>

                            </a>
                        </td>
                        <td>
                            <div class="font-medium text-on-surface"><?php echo e($order->user?->name ?? $order->shippingAddress?->name ?? 'زائر'); ?></div>
                            <div class="text-xs text-on-surface-variant"><?php echo e($order->user?->email ?? $order->guest_email ?? '—'); ?></div>
                        </td>
                        <td>
                            <?php if($order->is_instant_buy): ?>
                                <span class="badge badge-primary">
                                    <span class="material-symbols-outlined text-sm">bolt</span> فوري
                                </span>
                            <?php else: ?>
                                <span class="text-on-surface-variant text-xs">عادي</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($order->items->count()); ?></td>
                        <td class="font-bold text-on-surface"><?php echo e(number_format($order->grand_total, 0)); ?></td>
                        <td>
                            <span class="badge
                                <?php switch($order->status):
                                    case ('pending'): ?> badge-warning <?php break; ?>
                                    <?php case ('confirmed'): ?> badge-info <?php break; ?>
                                    <?php case ('processing'): ?> badge-primary <?php break; ?>
                                    <?php case ('shipped'): ?> badge-primary <?php break; ?>
                                    <?php case ('delivered'): ?> badge-success <?php break; ?>
                                    <?php case ('cancelled'): ?> badge-danger <?php break; ?>
                                <?php endswitch; ?>">
                                <?php echo e($order->status_name); ?>

                            </span>
                        </td>
                        <td>
                            <span class="badge <?php echo e($order->payment_status === 'paid' ? 'badge-success' : 'badge-danger'); ?>">
                                <?php echo e($order->payment_status === 'paid' ? 'مدفوع' : 'غير مدفوع'); ?>

                            </span>
                        </td>
                        <td class="text-xs text-on-surface-variant"><?php echo e($order->created_at->format('Y-m-d H:i')); ?></td>
                        <td>
                            <div class="flex items-center gap-2">
                                <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="p-1.5 text-primary hover:bg-primary-fixed rounded-lg transition-all" title="عرض">
                                    <span class="material-symbols-outlined">visibility</span>
                                </a>
                                <form action="<?php echo e(route('admin.orders.destroy', $order)); ?>" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطلب؟')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="p-1.5 text-error hover:bg-error-container/30 rounded-lg transition-all" title="حذف">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="10" class="px-4 py-12 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl text-outline mb-3 block">inventory_2</span>
                            <p>لا توجد طلبات</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($orders->hasPages()): ?>
        <div class="p-4 border-t border-outline-variant/30"><?php echo e($orders->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const bulkBar = document.getElementById('bulkBar');
    const selectedCount = document.getElementById('selectedCount');
    const statusSelect = document.getElementById('statusSelect');

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
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\amarn\ecommerce\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>