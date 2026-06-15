<?php $__env->startSection('title', 'لوحة التحكم'); ?>

<?php $__env->startSection('page_title', 'لوحة القيادة'); ?>

<?php $__env->startSection('content'); ?>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="kpi-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-on-surface-variant">إجمالي المبيعات</p>
                <p class="text-2xl font-bold mt-1 text-on-surface"><?php echo e(number_format($stats['total_revenue'], 0)); ?></p>
                <p class="text-xs mt-1 <?php echo e($stats['revenue_growth'] >= 0 ? 'text-emerald-600' : 'text-error'); ?>">
                    <span class="material-symbols-outlined text-sm align-text-bottom"><?php echo e($stats['revenue_growth'] >= 0 ? 'trending_up' : 'trending_down'); ?></span>
                    <?php echo e($stats['revenue_growth'] >= 0 ? '+' : ''); ?><?php echo e($stats['revenue_growth']); ?>% عن الشهر السابق
                </p>
            </div>
            <div class="kpi-icon">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">payments</span>
            </div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-on-surface-variant">إجمالي الطلبات</p>
                <p class="text-2xl font-bold mt-1 text-on-surface"><?php echo e(number_format($stats['total_orders'])); ?></p>
                <p class="text-xs mt-1 <?php echo e($stats['orders_growth'] >= 0 ? 'text-emerald-600' : 'text-error'); ?>">
                    <span class="material-symbols-outlined text-sm align-text-bottom"><?php echo e($stats['orders_growth'] >= 0 ? 'trending_up' : 'trending_down'); ?></span>
                    <?php echo e($stats['orders_growth'] >= 0 ? '+' : ''); ?><?php echo e($stats['orders_growth']); ?>%
                </p>
            </div>
            <div class="kpi-icon">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">shopping_cart</span>
            </div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-on-surface-variant">العملاء</p>
                <p class="text-2xl font-bold mt-1 text-on-surface"><?php echo e(number_format($stats['total_customers'])); ?></p>
                <p class="text-xs mt-1 text-on-surface-variant">+<?php echo e($stats['new_customers_this_month']); ?> هذا الشهر</p>
            </div>
            <div class="kpi-icon">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">group</span>
            </div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-on-surface-variant">المنتجات</p>
                <p class="text-2xl font-bold mt-1 text-on-surface"><?php echo e(number_format($stats['total_products'])); ?></p>
                <p class="text-xs mt-1 <?php echo e($stats['low_stock'] > 0 ? 'text-warning' : 'text-emerald-600'); ?>">
                    <?php if($stats['low_stock'] > 0): ?>
                        <span class="material-symbols-outlined text-sm align-text-bottom">warning</span> <?php echo e($stats['low_stock']); ?> مخزون منخفض
                    <?php else: ?>
                        <span class="material-symbols-outlined text-sm align-text-bottom">check_circle</span> كل المخازن جيدة
                    <?php endif; ?>
                </p>
            </div>
            <div class="kpi-icon">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">inventory_2</span>
            </div>
        </div>
    </div>
</div>


<div class="grid lg:grid-cols-3 gap-6">
    
    <div class="lg:col-span-2 card">
        <div class="card-header flex items-center justify-between">
            <h3 class="font-bold text-lg flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">trending_up</span>
                المبيعات الأسبوعية
            </h3>
            <span class="text-xs text-on-surface-variant">آخر 7 أيام</span>
        </div>
        <div class="card-body">
            <div class="flex items-end gap-2 h-48">
                <?php $maxRev = max(array_column($weeklyChart, 'revenue')) ?: 1; ?>
                <?php $__currentLoopData = $weeklyChart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $h = $maxRev > 0 ? max(8, ($day['revenue'] / $maxRev) * 100) : 8; ?>
                    <div class="flex-1 flex flex-col items-center gap-1" title="<?php echo e(number_format($day['revenue'], 0)); ?>">
                        <div class="w-full bg-gradient-to-t from-primary to-primary-container rounded-t-lg transition-all hover:from-primary-container hover:to-primary" style="height: <?php echo e($h); ?>%"></div>
                        <div class="text-xs text-on-surface-variant"><?php echo e($day['day']); ?></div>
                        <div class="text-xs font-semibold text-on-surface"><?php echo e($day['orders']); ?></div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <h3 class="font-bold text-lg flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">donut_large</span>
                حالات الطلبات
            </h3>
        </div>
        <div class="card-body space-y-4">
            <?php $totalOrders = max(1, array_sum(array_column($statusDistribution, 'count'))); ?>
            <?php $__currentLoopData = $statusDistribution; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $percent = round(($status['count'] / $totalOrders) * 100, 1); ?>
                <div>
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="font-medium"><?php echo e($status['label']); ?></span>
                        <span class="text-on-surface-variant"><?php echo e($status['count']); ?> (<?php echo e($percent); ?>%)</span>
                    </div>
                    <div class="h-2 bg-surface-container-highest rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all" style="width: <?php echo e($percent); ?>%; background: <?php echo e($status['color']); ?>"></div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header">
        <h3 class="font-bold text-lg flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">tune</span>
            إعدادات سريعة
        </h3>
    </div>
    <div class="card-body">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="border border-outline-variant/50 rounded-lg p-3">
                <div class="text-xs text-on-surface-variant mb-1">العملة</div>
                <div class="font-bold text-sm mb-2 text-on-surface"><?php echo e($settings['currency']); ?></div>
                <a href="#" class="text-primary text-xs hover:underline">تغيير</a>
            </div>
            <div class="border border-outline-variant/50 rounded-lg p-3">
                <div class="text-xs text-on-surface-variant mb-1">شركة الشحن</div>
                <div class="font-bold text-sm mb-2 text-on-surface"><?php echo e($settings['shipping_company']); ?></div>
                <button onclick="changeQuickSetting('shipping_company')" class="text-primary text-xs hover:underline">تغيير</button>
            </div>
            <div class="border border-outline-variant/50 rounded-lg p-3">
                <div class="text-xs text-on-surface-variant mb-1">الدفع</div>
                <div class="font-bold text-sm mb-2 text-on-surface"><?php echo e($settings['payment_method']); ?></div>
                <a href="<?php echo e(route('admin.coupons.index')); ?>" class="text-primary text-xs hover:underline">إعدادات</a>
            </div>
            <div class="border border-outline-variant/50 rounded-lg p-3">
                <div class="text-xs text-on-surface-variant mb-1">الثيم</div>
                <div class="font-bold text-sm mb-2 text-on-surface"><?php echo e($settings['theme']); ?></div>
                <a href="#" class="text-primary text-xs hover:underline">تغيير</a>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-outline-variant/30 flex flex-wrap gap-3 text-sm">
            <span class="text-on-surface-variant">تقارير سريعة:</span>
            <a href="#" class="text-primary hover:underline">مبيعات اليوم</a>
            <a href="#" class="text-primary hover:underline">مبيعات الأسبوع</a>
            <a href="#" class="text-primary hover:underline">مبيعات الشهر</a>
            <?php if($stats['instant_buy_orders'] > 0): ?>
                <span class="badge badge-primary">
                    <span class="material-symbols-outlined text-sm">bolt</span>
                    <?php echo e($stats['instant_buy_orders']); ?> طلب فوري
                </span>
            <?php endif; ?>
        </div>
    </div>
</div>


<div class="grid lg:grid-cols-3 gap-6">
    
    <div class="lg:col-span-2 card">
        <div class="card-header flex items-center justify-between">
            <h3 class="font-bold text-lg flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">receipt_long</span>
                الطلبات الحديثة
            </h3>
            <a href="<?php echo e(route('admin.orders.index')); ?>" class="text-primary text-sm hover:underline">عرض الكل</a>
        </div>
        <div class="overflow-x-auto">
            <table class="table-wrap">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>العميل</th>
                        <th>المنتج</th>
                        <th>المبلغ</th>
                        <th>الحالة</th>
                        <?php if($stats['instant_buy_orders'] > 0): ?><th>النوع</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="font-semibold">
                                <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="text-primary hover:underline">
                                    <?php echo e($order->order_number); ?>

                                </a>
                            </td>
                            <td>
                                <?php echo e($order->user?->name ?? $order->shippingAddress?->name ?? 'زائر'); ?>

                                <?php if($order->is_instant_buy): ?>
                                    <span class="material-symbols-outlined text-sm align-text-bottom text-tertiary" title="طلب فوري">bolt</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="line-clamp-1"><?php echo e($order->items->first()?->product_name ?? '—'); ?></div>
                                <?php if($order->items->count() > 1): ?>
                                    <div class="text-xs text-on-surface-variant">+<?php echo e($order->items->count() - 1); ?> منتج</div>
                                <?php endif; ?>
                            </td>
                            <td class="font-bold"><?php echo e(number_format($order->grand_total, 0)); ?></td>
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
                            <?php if($stats['instant_buy_orders'] > 0): ?>
                                <td>
                                    <?php if($order->is_instant_buy): ?>
                                        <span class="badge badge-primary">فوري</span>
                                    <?php else: ?>
                                        <span class="text-on-surface-variant text-xs">عادي</span>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="px-4 py-8 text-center text-on-surface-variant">لا توجد طلبات</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="space-y-6">
        
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h3 class="font-bold text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-error" style="font-variation-settings:'FILL' 1">report</span>
                    مخزون منخفض
                </h3>
                <a href="<?php echo e(route('admin.products.index')); ?>" class="text-primary text-xs hover:underline">الكل</a>
            </div>
            <div class="divide-y divide-outline-variant/30">
                <?php $__empty_1 = true; $__currentLoopData = $lowStockProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-3 flex items-center gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-sm line-clamp-1 text-on-surface"><?php echo e($p->name); ?></div>
                            <div class="text-xs text-on-surface-variant"><?php echo e($p->category?->name ?? '—'); ?></div>
                        </div>
                        <div class="text-left">
                            <span class="badge badge-danger"><?php echo e($p->stock); ?> قطع</span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-4 text-center text-sm text-on-surface-variant">المخزون جيد ✓</div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <h3 class="font-bold text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-tertiary" style="font-variation-settings:'FILL' 1">local_fire_department</span>
                    أفضل المنتجات مبيعاً
                </h3>
            </div>
            <div class="divide-y divide-outline-variant/30">
                <?php $__empty_1 = true; $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $tp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-3 flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-primary-fixed text-primary flex items-center justify-center font-bold text-xs"><?php echo e($i + 1); ?></div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-sm line-clamp-1 text-on-surface"><?php echo e($tp->product_name); ?></div>
                            <div class="text-xs text-on-surface-variant"><?php echo e($tp->total_qty); ?> قطعة مباعة</div>
                        </div>
                        <div class="text-left text-xs">
                            <div class="font-bold text-emerald-600"><?php echo e(number_format($tp->total_revenue, 0)); ?></div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-4 text-center text-sm text-on-surface-variant">لا توجد مبيعات بعد</div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h3 class="font-bold text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-tertiary" style="font-variation-settings:'FILL' 1">confirmation_number</span>
                    كوبونات نشطة
                </h3>
                <a href="<?php echo e(route('admin.coupons.index')); ?>" class="text-primary text-xs hover:underline">إدارة</a>
            </div>
            <div class="divide-y divide-outline-variant/30">
                <?php $__empty_1 = true; $__currentLoopData = $activeCoupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-3 flex items-center gap-3">
                        <div class="font-mono font-bold text-sm bg-primary-fixed text-primary px-2 py-1 rounded"><?php echo e($c->code); ?></div>
                        <div class="flex-1 text-xs text-on-surface-variant">
                            <?php if($c->type === 'percent'): ?>
                                <?php echo e($c->value); ?>%
                            <?php else: ?>
                                <?php echo e(number_format($c->value, 0)); ?> ثابت
                            <?php endif; ?>
                        </div>
                        <div class="text-xs text-on-surface-variant">
                            <?php echo e($c->used_count); ?><?php echo e($c->usage_limit ? '/' . $c->usage_limit : ''); ?>

                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-4 text-center text-sm text-on-surface-variant">لا توجد كوبونات نشطة</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function changeQuickSetting(key) {
    const value = prompt('القيمة الجديدة لـ ' + key + ':');
    if (!value) return;

    fetch('<?php echo e(route("admin.quickSetting")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ key, value }),
    })
    .then(r => r.json())
    .then(data => {
        alert(data.message || (data.success ? 'تم التحديث' : 'حدث خطأ'));
        if (data.success) location.reload();
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\amarn\ecommerce\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>