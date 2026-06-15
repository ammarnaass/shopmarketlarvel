<?php $__env->startSection('title', 'طلب ' . $order->order_number); ?>

<?php $__env->startSection('page_title', 'طلب #' . $order->order_number); ?>

<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-between mb-2">
    <div class="flex items-center gap-3">
        <?php if($order->is_instant_buy): ?>
            <span class="badge badge-primary">
                <span class="material-symbols-outlined text-sm">bolt</span> طلب فوري
            </span>
        <?php endif; ?>
        <span class="text-sm text-on-surface-variant">
            <a href="<?php echo e(route('admin.orders.index')); ?>" class="text-primary hover:underline">الطلبات</a>
            <span class="mx-1">/</span>
            <span><?php echo e($order->order_number); ?></span>
        </span>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-ghost btn-sm">
            <span class="material-symbols-outlined">arrow_forward</span> العودة
        </a>
        <form action="<?php echo e(route('admin.orders.destroy', $order)); ?>" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطلب؟')">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="btn btn-danger btn-sm">
                <span class="material-symbols-outlined">delete</span> حذف
            </button>
        </form>
    </div>
</div>


<div class="card p-5 mb-6">
    <form method="POST" action="<?php echo e(route('admin.orders.updateStatus', $order)); ?>" class="flex flex-wrap items-end gap-3">
        <?php echo csrf_field(); ?>
        <div>
            <label class="form-label">تحديث الحالة</label>
            <div class="flex items-center gap-2">
                <span class="badge
                    <?php switch($order->status):
                        case ('pending'): ?> badge-warning <?php break; ?>
                        <?php case ('confirmed'): ?> badge-info <?php break; ?>
                        <?php case ('processing'): ?> badge-primary <?php break; ?>
                        <?php case ('shipped'): ?> badge-primary <?php break; ?>
                        <?php case ('delivered'): ?> badge-success <?php break; ?>
                        <?php case ('cancelled'): ?> badge-danger <?php break; ?>
                    <?php endswitch; ?>"><?php echo e($order->status_name); ?></span>
                <span class="material-symbols-outlined text-outline">arrow_back</span>
                <select name="status" required class="form-select w-auto">
                    <?php $__currentLoopData = \App\Models\Order::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e($order->status === $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <div class="flex-1 min-w-[200px]">
            <label class="form-label">ملاحظة (اختياري)</label>
            <input type="text" name="note" class="form-input" placeholder="سبب التغيير...">
        </div>
        <button type="submit" class="btn btn-primary">
            <span class="material-symbols-outlined">sync</span> تحديث
        </button>
    </form>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        
        <div class="card p-5">
            <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">inventory_2</span>
                منتجات الطلب
            </h3>
            <div class="overflow-x-auto">
                <table class="table-wrap">
                    <thead>
                        <tr>
                            <th>المنتج</th>
                            <th>SKU</th>
                            <th>السعر</th>
                            <th>الكمية</th>
                            <th>الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <div class="font-medium text-on-surface"><?php echo e($item->product_name); ?></div>
                                    <?php if($item->variant_name): ?><div class="text-xs text-on-surface-variant"><?php echo e($item->variant_name); ?></div><?php endif; ?>
                                </td>
                                <td class="font-mono text-xs"><?php echo e($item->sku ?? '—'); ?></td>
                                <td><?php echo e(number_format($item->price, 0)); ?></td>
                                <td><?php echo e($item->quantity); ?></td>
                                <td class="font-bold text-on-surface"><?php echo e(number_format($item->total, 0)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="card p-5">
            <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">sticky_note_2</span>
                ملاحظات داخلية
            </h3>

            
            <form method="POST" action="<?php echo e(route('admin.orders.notes.store', $order)); ?>" class="mb-4">
                <?php echo csrf_field(); ?>
                <div class="flex gap-2">
                    <input type="text" name="note" required class="form-input" placeholder="أضف ملاحظة...">
                    <label class="flex items-center gap-1 text-xs text-on-surface-variant whitespace-nowrap">
                        <input type="checkbox" name="is_customer_note" value="1" class="form-checkbox">
                        مرئي للعميل
                    </label>
                    <button type="submit" class="btn btn-primary btn-sm whitespace-nowrap">
                        <span class="material-symbols-outlined">add</span> إضافة
                    </button>
                </div>
            </form>

            
            <div class="space-y-2">
                <?php $__empty_1 = true; $__currentLoopData = $notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-start gap-3 p-3 rounded-lg <?php echo e($note->is_customer_note ? 'bg-primary-fixed/30 border border-primary-fixed-dim/50' : 'bg-surface-container-low'); ?>">
                        <div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-on-surface-variant">person</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-semibold text-xs text-on-surface"><?php echo e($note->user?->name ?? 'النظام'); ?></span>
                                <?php if($note->is_customer_note): ?>
                                    <span class="badge badge-info">مرئي للعميل</span>
                                <?php endif; ?>
                                <span class="text-xs text-on-surface-variant"><?php echo e($note->created_at->diffForHumans()); ?></span>
                            </div>
                            <p class="text-sm text-on-surface"><?php echo e($note->note); ?></p>
                        </div>
                        <form action="<?php echo e(route('admin.orders.notes.delete', $note)); ?>" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد؟')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-on-surface-variant hover:text-error transition-colors">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </form>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-4 text-on-surface-variant text-sm">
                        <span class="material-symbols-outlined text-2xl mb-2 block">sticky_note_2</span>
                        <p>لا توجد ملاحظات</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card p-5">
            <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">history</span>
                سجل التغييرات
            </h3>
            <div class="space-y-0">
                <?php $__empty_1 = true; $__currentLoopData = $statusHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex gap-4 pb-4 relative">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-primary-fixed flex items-center justify-center z-10">
                                <span class="material-symbols-outlined text-primary text-sm" style="font-variation-settings:'FILL' 1">circle</span>
                            </div>
                            <div class="w-0.5 bg-outline-variant/50 flex-1 mt-1"></div>
                        </div>
                        <div class="flex-1 pb-2">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-sm text-on-surface">
                                    <?php echo e(\App\Models\Order::STATUSES[$history->status] ?? $history->status); ?>

                                </span>
                                <?php if($history->previous_status): ?>
                                    <span class="text-xs text-on-surface-variant">
                                        (كان: <?php echo e(\App\Models\Order::STATUSES[$history->previous_status] ?? $history->previous_status); ?>)
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="text-xs text-on-surface-variant"><?php echo e($history->created_at->format('Y-m-d H:i')); ?> — <?php echo e($history->user?->name ?? 'النظام'); ?></div>
                            <?php if($history->note): ?>
                                <div class="text-xs text-on-surface mt-1 bg-surface-container-low p-2 rounded"><?php echo e($history->note); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-4 text-on-surface-variant text-sm">
                        <span class="material-symbols-outlined text-2xl mb-2 block">history</span>
                        <p>لا يوجد سجل تغييرات</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        
        <div class="card p-5">
            <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">person</span>
                العميل
            </h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-xs text-on-surface-variant">الاسم</dt><dd class="font-semibold text-on-surface"><?php echo e($order->user?->name ?? $order->shippingAddress?->name ?? 'زائر'); ?></dd></div>
                <div><dt class="text-xs text-on-surface-variant">البريد</dt><dd class="text-xs text-on-surface"><?php echo e($order->user?->email ?? $order->guest_email ?? '—'); ?></dd></div>
                <div><dt class="text-xs text-on-surface-variant">الهاتف</dt><dd class="text-on-surface"><?php echo e($order->user?->phone ?? $order->guest_phone ?? $order->shippingAddress?->phone ?? '—'); ?></dd></div>
            </dl>
        </div>

        
        <?php if($order->shippingAddress): ?>
            <div class="card p-5">
                <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">local_shipping</span>
                    عنوان الشحن
                </h3>
                <div class="text-sm text-on-surface"><?php echo e($order->shippingAddress->name); ?></div>
                <div class="text-sm text-on-surface-variant"><?php echo e($order->shippingAddress->phone); ?></div>
                <div class="text-sm mt-2 text-on-surface"><?php echo e($order->shippingAddress->address); ?></div>
                <div class="text-sm text-on-surface-variant"><?php echo e($order->shippingAddress->city); ?> - <?php echo e($order->shippingAddress->state_name); ?></div>
                <div class="text-sm text-on-surface-variant"><?php echo e($order->shippingAddress->country_name); ?></div>
                <?php if($order->tracking_number): ?>
                    <div class="mt-3 pt-3 border-t border-outline-variant/30">
                        <dt class="text-xs text-on-surface-variant">رقم التتبع</dt>
                        <dd class="font-mono text-sm text-on-surface"><?php echo e($order->tracking_number); ?></dd>
                    </div>
                <?php endif; ?>
                <?php if($order->shippingCompany): ?>
                    <div class="mt-2">
                        <dt class="text-xs text-on-surface-variant">شركة الشحن</dt>
                        <dd class="text-sm font-semibold text-on-surface"><?php echo e($order->shippingCompany->name); ?></dd>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php $payment = $order->payment->first(); ?>
        <?php if($payment): ?>
            <div class="card p-5">
                <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">credit_card</span>
                    الدفع
                </h3>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-on-surface-variant">الحالة:</dt><dd>
                        <span class="badge <?php echo e($payment->status === 'paid' ? 'badge-success' : 'badge-danger'); ?>">
                            <?php echo e($payment->status === 'paid' ? 'مدفوع' : 'غير مدفوع'); ?>

                        </span>
                    </dd></div>
                    <div class="flex justify-between"><dt class="text-on-surface-variant">الطريقة:</dt><dd class="text-on-surface"><?php echo e($payment->method ?? '—'); ?></dd></div>
                    <?php if($payment->transaction_id): ?>
                        <div class="flex justify-between"><dt class="text-on-surface-variant">رقم العملية:</dt><dd class="font-mono text-xs text-on-surface"><?php echo e($payment->transaction_id); ?></dd></div>
                    <?php endif; ?>
                </dl>
            </div>
        <?php endif; ?>

        
        <div class="card p-5">
            <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">calculate</span>
                ملخص الطلب
            </h3>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-on-surface-variant">المجموع الفرعي:</dt><dd class="text-on-surface"><?php echo e(number_format($order->subtotal, 0)); ?></dd></div>
                <?php if($order->discount > 0): ?>
                    <div class="flex justify-between text-emerald-600"><dt>الخصم:</dt><dd>-<?php echo e(number_format($order->discount, 0)); ?></dd></div>
                <?php endif; ?>
                <div class="flex justify-between"><dt class="text-on-surface-variant">الشحن:</dt><dd class="text-on-surface"><?php echo e(number_format($order->shipping_cost, 0)); ?></dd></div>
                <?php if($order->tax > 0): ?>
                    <div class="flex justify-between"><dt class="text-on-surface-variant">الضرائب:</dt><dd class="text-on-surface"><?php echo e(number_format($order->tax, 0)); ?></dd></div>
                <?php endif; ?>
                <?php if($order->cod_fee > 0): ?>
                    <div class="flex justify-between"><dt class="text-on-surface-variant">رسوم COD:</dt><dd class="text-on-surface"><?php echo e(number_format($order->cod_fee, 0)); ?></dd></div>
                <?php endif; ?>
                <div class="flex justify-between pt-2 border-t border-outline-variant/30 font-bold text-lg"><dt class="text-on-surface">الإجمالي:</dt><dd class="text-primary"><?php echo e(number_format($order->grand_total, 0)); ?></dd></div>
            </dl>
            <p class="text-xs text-on-surface-variant mt-3">تاريخ الطلب: <?php echo e($order->created_at->format('Y-m-d H:i')); ?></p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\amarn\ecommerce\resources\views/admin/orders/show.blade.php ENDPATH**/ ?>