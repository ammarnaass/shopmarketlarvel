<?php $__env->startSection('title', 'إدارة الشحن'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $activeTab = request('tab', 'zones');
?>


<div class="mb-6 bg-gradient-to-l from-blue-600 to-indigo-700 rounded-xl p-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold flex items-center gap-3">
                <i class="fas fa-shipping-fast text-3xl"></i> إدارة الشحن المتكاملة
            </h1>
            <p class="text-blue-100 text-sm mt-1">مناطق الشحن، طرق التوصيل، شركات الشحن، البوليصات والتتبع</p>
        </div>
        <div class="flex gap-3">
            <?php if($activeTab === 'zones'): ?>
                <a href="<?php echo e(route('admin.shipping.zone.create')); ?>" class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg text-sm font-semibold backdrop-blur-sm">
                    <i class="fas fa-plus ml-1"></i> إضافة منطقة
                </a>
            <?php elseif($activeTab === 'methods'): ?>
                <a href="<?php echo e(route('admin.shipping.method.create')); ?>" class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg text-sm font-semibold backdrop-blur-sm">
                    <i class="fas fa-plus ml-1"></i> إضافة طريقة شحن
                </a>
            <?php elseif($activeTab === 'companies'): ?>
                <a href="<?php echo e(route('admin.shipping.company.create')); ?>" class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg text-sm font-semibold backdrop-blur-sm">
                    <i class="fas fa-plus ml-1"></i> إضافة شركة
                </a>
            <?php elseif($activeTab === 'labels'): ?>
                <a href="<?php echo e(route('admin.shipping.label.create')); ?>" class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg text-sm font-semibold backdrop-blur-sm">
                    <i class="fas fa-plus ml-1"></i> إنشاء بوليصة
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>


<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-4 text-center">
        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mx-auto mb-2">
            <i class="fas fa-map text-blue-600"></i>
        </div>
        <div class="text-2xl font-bold text-gray-800"><?php echo e($stats['zones_count']); ?></div>
        <div class="text-xs text-gray-500">مناطق الشحن</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 text-center">
        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center mx-auto mb-2">
            <i class="fas fa-truck-loading text-purple-600"></i>
        </div>
        <div class="text-2xl font-bold text-gray-800"><?php echo e($stats['methods_count']); ?></div>
        <div class="text-xs text-gray-500">طرق الشحن</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 text-center">
        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center mx-auto mb-2">
            <i class="fas fa-building text-green-600"></i>
        </div>
        <div class="text-2xl font-bold text-gray-800"><?php echo e($stats['carriers_count']); ?></div>
        <div class="text-xs text-gray-500">شركات الشحن</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 text-center">
        <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center mx-auto mb-2">
            <i class="fas fa-file-invoice text-orange-600"></i>
        </div>
        <div class="text-2xl font-bold text-gray-800"><?php echo e($stats['labels_count']); ?></div>
        <div class="text-xs text-gray-500">البوليصات</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 text-center">
        <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center mx-auto mb-2">
            <i class="fas fa-clock text-yellow-600"></i>
        </div>
        <div class="text-2xl font-bold text-gray-800"><?php echo e($stats['pending_labels']); ?></div>
        <div class="text-xs text-gray-500">بانتظار الشحن</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 text-center">
        <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center mx-auto mb-2">
            <i class="fas fa-shipping-fast text-indigo-600"></i>
        </div>
        <div class="text-2xl font-bold text-gray-800"><?php echo e($stats['shipped_labels']); ?></div>
        <div class="text-xs text-gray-500">تم شحنها</div>
    </div>
</div>


<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="flex border-b overflow-x-auto">
        <a href="<?php echo e(route('admin.shipping.index', ['tab' => 'zones'])); ?>"
           class="px-6 py-3 font-semibold text-sm whitespace-nowrap <?php echo e($activeTab === 'zones' ? 'border-b-2 border-blue-600 text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600'); ?>">
            <i class="fas fa-map ml-1"></i> مناطق الشحن (<?php echo e($stats['zones_count']); ?>)
        </a>
        <a href="<?php echo e(route('admin.shipping.index', ['tab' => 'methods'])); ?>"
           class="px-6 py-3 font-semibold text-sm whitespace-nowrap <?php echo e($activeTab === 'methods' ? 'border-b-2 border-blue-600 text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600'); ?>">
            <i class="fas fa-truck-loading ml-1"></i> طرق الشحن (<?php echo e($stats['methods_count']); ?>)
        </a>
        <a href="<?php echo e(route('admin.shipping.index', ['tab' => 'companies'])); ?>"
           class="px-6 py-3 font-semibold text-sm whitespace-nowrap <?php echo e($activeTab === 'companies' ? 'border-b-2 border-blue-600 text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600'); ?>">
            <i class="fas fa-building ml-1"></i> شركات الشحن (<?php echo e($stats['carriers_count']); ?>)
        </a>
        <a href="<?php echo e(route('admin.shipping.index', ['tab' => 'labels'])); ?>"
           class="px-6 py-3 font-semibold text-sm whitespace-nowrap <?php echo e($activeTab === 'labels' ? 'border-b-2 border-blue-600 text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600'); ?>">
            <i class="fas fa-file-invoice ml-1"></i> البوليصات (<?php echo e($stats['labels_count']); ?>)
        </a>
    </div>

    
    <?php if($activeTab === 'zones'): ?>
        <div class="p-5">
            <?php $__empty_1 = true; $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="border rounded-xl mb-4 overflow-hidden hover:shadow-md transition-shadow">
                    
                    <div class="bg-gradient-to-l from-gray-50 to-white p-4 flex items-center justify-between border-b">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg"><?php echo e($zone->name); ?></h3>
                                <div class="flex flex-wrap gap-2 text-xs text-gray-500 mt-1">
                                    <?php if($zone->countries): ?>
                                        <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded">
                                            <i class="fas fa-globe ml-1"></i><?php echo e(is_array($zone->countries) ? implode('، ', $zone->countries) : $zone->countries); ?>

                                        </span>
                                    <?php endif; ?>
                                    <?php if($zone->states): ?>
                                        <span class="bg-purple-50 text-purple-700 px-2 py-0.5 rounded">
                                            <i class="fas fa-map-pin ml-1"></i><?php echo e(is_array($zone->states) ? implode('، ', array_slice($zone->states, 0, 5)) : $zone->states); ?><?php echo e(count($zone->states) > 5 ? '...' : ''); ?>

                                        </span>
                                    <?php endif; ?>
                                    <?php if($zone->cities): ?>
                                        <span class="bg-green-50 text-green-700 px-2 py-0.5 rounded">
                                            <i class="fas fa-city ml-1"></i><?php echo e(is_array($zone->cities) ? implode('، ', array_slice($zone->cities, 0, 5)) : $zone->cities); ?><?php echo e((is_array($zone->cities) && count($zone->cities) > 5) ? '...' : ''); ?>

                                        </span>
                                    <?php endif; ?>
                                    <?php if($zone->is_default): ?>
                                        <span class="bg-yellow-50 text-yellow-700 px-2 py-0.5 rounded">
                                            <i class="fas fa-star ml-1"></i>افتراضية
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm <?php echo e($zone->status === 'active' ? 'text-green-600' : 'text-red-500'); ?>">
                                <i class="fas fa-circle text-xs ml-1"></i><?php echo e($zone->status === 'active' ? 'نشطة' : 'معطلة'); ?>

                            </span>
                            <a href="<?php echo e(route('admin.shipping.zone.edit', $zone)); ?>" class="text-blue-600 hover:text-blue-800 px-2 py-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo e(route('admin.shipping.zone.destroy', $zone)); ?>" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه المنطقة؟')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-500 hover:text-red-700 px-2 py-1">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    
                    <div class="p-4">
                        <?php $zoneMethods = $zone->methods; ?>
                        <?php if($zoneMethods->count() > 0): ?>
                            <div class="space-y-2">
                                <?php $__currentLoopData = $zoneMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded bg-<?php echo e($method->getTypeColor()); ?>-100 flex items-center justify-center">
                                                <i class="fas <?php echo e($method->getTypeIcon()); ?> text-<?php echo e($method->getTypeColor()); ?>-600 text-xs"></i>
                                            </div>
                                            <div>
                                                <span class="font-semibold text-sm"><?php echo e($method->name); ?></span>
                                                <?php if($method->carrier): ?>
                                                    <span class="text-xs text-gray-500">(<?php echo e($method->carrier->name); ?>)</span>
                                                <?php endif; ?>
                                                <span class="text-xs bg-<?php echo e($method->getTypeColor()); ?>-100 text-<?php echo e($method->getTypeColor()); ?>-700 px-2 py-0.5 rounded mr-2"><?php echo e($method->getTypeLabel()); ?></span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-4 text-sm">
                                            <?php if($method->type === 'flat_rate'): ?>
                                                <span class="font-bold text-green-700"><?php echo e(number_format($method->flat_rate_amount, 2)); ?> ر.س</span>
                                            <?php elseif($method->type === 'free_shipping'): ?>
                                                <span class="font-bold text-emerald-600">مجاني</span>
                                            <?php elseif($method->type === 'weight_based'): ?>
                                                <span class="font-bold text-purple-700">حسب الوزن</span>
                                            <?php else: ?>
                                                <span class="font-bold text-gray-700">متغير</span>
                                            <?php endif; ?>
                                            <?php if($method->estimated_days): ?>
                                                <span class="text-gray-500"><i class="fas fa-clock ml-1"></i><?php echo e($method->estimated_days); ?></span>
                                            <?php endif; ?>
                                            <a href="<?php echo e(route('admin.shipping.method.edit', $method)); ?>" class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo e(route('admin.shipping.method.destroy', $method)); ?>" method="POST" class="inline" onsubmit="return confirm('حذف طريقة الشحن؟')">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="text-red-500 hover:text-red-700">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-400 text-sm text-center py-3">لا توجد طرق شحن لهذه المنطقة</p>
                        <?php endif; ?>

                        
                        <div class="mt-3 border-t pt-3">
                            <button type="button" onclick="toggleAddMethod(<?php echo e($zone->id); ?>)" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                <i class="fas fa-plus-circle ml-1"></i> إضافة طريقة شحن
                            </button>
                            <div id="addMethod-<?php echo e($zone->id); ?>" class="hidden mt-3 bg-blue-50 rounded-lg p-4">
                                <form action="<?php echo e(route('admin.shipping.zone.method.store', $zone)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                        <div>
                                            <label class="text-xs text-gray-600">اسم الطريقة</label>
                                            <input type="text" name="name" class="w-full border rounded px-3 py-2 text-sm" placeholder="شحن عادي" required>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-600">النوع</label>
                                            <select name="type" class="w-full border rounded px-3 py-2 text-sm">
                                                <option value="flat_rate">شحن ثابت</option>
                                                <option value="free_shipping">شحن مجاني</option>
                                                <option value="weight_based">حسب الوزن</option>
                                                <option value="courier_api">API شركة شحن</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-600">المبلغ (ر.س)</label>
                                            <input type="number" name="flat_rate_amount" step="0.01" min="0" class="w-full border rounded px-3 py-2 text-sm" placeholder="25">
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-600">مدة التوصيل</label>
                                            <input type="text" name="estimated_days" class="w-full border rounded px-3 py-2 text-sm" placeholder="3-5 أيام">
                                        </div>
                                    </div>
                                    <div class="mt-3 flex gap-2">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                                            <i class="fas fa-check ml-1"></i> إضافة
                                        </button>
                                        <button type="button" onclick="toggleAddMethod(<?php echo e($zone->id); ?>)" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm">إلغاء</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-12 text-gray-400">
                    <i class="fas fa-map text-4xl mb-3"></i>
                    <p class="text-lg">لا توجد مناطق شحن بعد</p>
                    <a href="<?php echo e(route('admin.shipping.zone.create')); ?>" class="text-blue-600 hover:text-blue-800 text-sm mt-2 inline-block">
                        <i class="fas fa-plus ml-1"></i> إضافة منطقة شحن جديدة
                    </a>
                </div>
            <?php endif; ?>

            <div class="mt-4"><?php echo e($zones->withQueryString()->links()); ?></div>
        </div>

    
    <?php elseif($activeTab === 'methods'): ?>
        <div class="p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-lg">طرق الشحن</h2>
                <a href="<?php echo e(route('admin.shipping.method.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                    <i class="fas fa-plus ml-1"></i> إضافة طريقة
                </a>
            </div>
            <?php $__empty_1 = true; $__currentLoopData = $methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="border rounded-lg p-4 mb-3 hover:shadow-sm transition flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-<?php echo e($method->getTypeColor()); ?>-100 flex items-center justify-center">
                            <i class="fas <?php echo e($method->getTypeIcon()); ?> text-<?php echo e($method->getTypeColor()); ?>-600"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-semibold"><?php echo e($method->name); ?></span>
                                <span class="text-xs bg-<?php echo e($method->getTypeColor()); ?>-100 text-<?php echo e($method->getTypeColor()); ?>-700 px-2 py-0.5 rounded"><?php echo e($method->getTypeLabel()); ?></span>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                <span><i class="fas fa-map-marker-alt ml-1"></i><?php echo e($method->zone?->name); ?></span>
                                <?php if($method->carrier): ?>
                                    <span class="mr-3"><i class="fas fa-truck ml-1"></i><?php echo e($method->carrier->name); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 text-sm">
                        <?php if($method->type === 'flat_rate'): ?>
                            <span class="font-bold text-green-700"><?php echo e(number_format($method->flat_rate_amount, 2)); ?> ر.س</span>
                        <?php elseif($method->type === 'free_shipping'): ?>
                            <span class="font-bold text-emerald-600">مجاني</span>
                            <span class="text-xs text-gray-500">فوق <?php echo e(number_format($method->free_shipping_min, 2)); ?> ر.س</span>
                        <?php elseif($method->type === 'weight_based'): ?>
                            <span class="font-bold text-purple-700">حسب الوزن</span>
                        <?php else: ?>
                            <span class="font-bold text-gray-600"><?php echo e($method->getTypeLabel()); ?></span>
                        <?php endif; ?>
                        <?php if($method->estimated_days): ?>
                            <span class="text-gray-500"><i class="fas fa-clock ml-1"></i><?php echo e($method->estimated_days); ?></span>
                        <?php endif; ?>
                        <span class="text-<?php echo e($method->status ? 'green' : 'red'); ?>-500 text-xs">
                            <i class="fas fa-circle"></i> <?php echo e($method->status ? 'نشطة' : 'معطلة'); ?>

                        </span>
                        <a href="<?php echo e(route('admin.shipping.method.edit', $method)); ?>" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="<?php echo e(route('admin.shipping.method.destroy', $method)); ?>" method="POST" class="inline" onsubmit="return confirm('حذف طريقة الشحن؟')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-12 text-gray-400">
                    <i class="fas fa-truck-loading text-4xl mb-3"></i>
                    <p>لا توجد طرق شحن بعد</p>
                </div>
            <?php endif; ?>
            <div class="mt-4"><?php echo e($methods->withQueryString()->links()); ?></div>
        </div>

    
    <?php elseif($activeTab === 'companies'): ?>
        <div class="p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-lg">شركات الشحن</h2>
                <a href="<?php echo e(route('admin.shipping.company.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                    <i class="fas fa-plus ml-1"></i> إضافة شركة
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 text-xs">
                        <tr>
                            <th class="px-4 py-3 text-right">الشركة</th>
                            <th class="px-4 py-3 text-right">الموقع</th>
                            <th class="px-4 py-3 text-right">رابط التتبع</th>
                            <th class="px-4 py-3 text-right">API</th>
                            <th class="px-4 py-3 text-right">المناطق</th>
                            <th class="px-4 py-3 text-right">الحالة</th>
                            <th class="px-4 py-3 text-right">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <?php if($company->logo): ?>
                                            <img src="<?php echo e($company->logo); ?>" class="w-8 h-8 rounded object-cover" alt="<?php echo e($company->name); ?>">
                                        <?php else: ?>
                                            <div class="w-8 h-8 rounded bg-green-100 flex items-center justify-center">
                                                <i class="fas fa-truck text-green-600 text-xs"></i>
                                            </div>
                                        <?php endif; ?>
                                        <span class="font-semibold"><?php echo e($company->name); ?></span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    <?php if($company->website): ?>
                                        <a href="<?php echo e($company->website); ?>" target="_blank" class="text-blue-600 hover:underline"><?php echo e($company->website); ?></a>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-xs font-mono text-gray-600 max-w-xs truncate" title="<?php echo e($company->tracking_url); ?>">
                                    <?php echo e($company->tracking_url); ?>

                                </td>
                                <td class="px-4 py-3">
                                    <?php if($company->is_active): ?>
                                        <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs">مفعّل</span>
                                    <?php else: ?>
                                        <span class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded text-xs">معطل</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-xs"><?php echo e($company->zones_count ?? $company->zones()->count()); ?></span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded text-xs <?php echo e($company->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'); ?>">
                                        <?php echo e($company->status === 'active' ? 'نشطة' : 'معطلة'); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-2">
                                        <a href="<?php echo e(route('admin.shipping.company.edit', $company)); ?>" class="text-blue-600 hover:text-blue-800" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('admin.shipping.company.destroy', $company)); ?>" method="POST" class="inline" onsubmit="return confirm('حذف الشركة؟')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-red-500 hover:text-red-700" title="حذف"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="7" class="text-center py-8 text-gray-400">لا توجد شركات شحن بعد</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4"><?php echo e($companies->withQueryString()->links()); ?></div>
        </div>

    
    <?php elseif($activeTab === 'labels'): ?>
        <div class="p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-lg">بوليصات الشحن</h2>
                <a href="<?php echo e(route('admin.shipping.label.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                    <i class="fas fa-plus ml-1"></i> إنشاء بوليصة
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 text-xs">
                        <tr>
                            <th class="px-4 py-3 text-right">رقم التتبع</th>
                            <th class="px-4 py-3 text-right">الطلب</th>
                            <th class="px-4 py-3 text-right">شركة الشحن</th>
                            <th class="px-4 py-3 text-right">الوزن</th>
                            <th class="px-4 py-3 text-right">التكلفة</th>
                            <th class="px-4 py-3 text-right">الحالة</th>
                            <th class="px-4 py-3 text-right">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $labels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <a href="<?php echo e(route('admin.shipping.label.show', $label)); ?>" class="font-mono text-blue-600 hover:underline">
                                        <?php echo e($label->tracking_number); ?>

                                    </a>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if($label->order): ?>
                                        <a href="<?php echo e(route('admin.orders.show', $label->order)); ?>" class="text-blue-600 hover:underline">
                                            #<?php echo e($label->order->order_number); ?>

                                        </a>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3"><?php echo e($label->carrier?->name ?? '-'); ?></td>
                                <td class="px-4 py-3"><?php echo e($label->weight ? $label->weight . ' كغ' : '-'); ?></td>
                                <td class="px-4 py-3 font-bold"><?php echo e(number_format($label->cost, 2)); ?> ر.س</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded text-xs bg-<?php echo e($label->getStatusColor()); ?>-100 text-<?php echo e($label->getStatusColor()); ?>-700">
                                        <?php echo e($label->getStatusLabel()); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-2">
                                        <a href="<?php echo e(route('admin.shipping.label.show', $label)); ?>" class="text-blue-600 hover:text-blue-800" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if($label->status === 'pending'): ?>
                                            <form action="<?php echo e(route('admin.shipping.label.updateStatus', $label)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="status" value="shipped">
                                                <button type="submit" class="text-indigo-600 hover:text-indigo-800" title="شحن">
                                                    <i class="fas fa-shipping-fast"></i>
                                                </button>
                                            </form>
                                        <?php elseif($label->status === 'shipped'): ?>
                                            <form action="<?php echo e(route('admin.shipping.label.updateStatus', $label)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="status" value="delivered">
                                                <button type="submit" class="text-green-600 hover:text-green-800" title="تسليم">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="7" class="text-center py-8 text-gray-400">لا توجد بوليصات بعد</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4"><?php echo e($labels->withQueryString()->links()); ?></div>
        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleAddMethod(zoneId) {
    const el = document.getElementById('addMethod-' + zoneId);
    el.classList.toggle('hidden');
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\amarn\ecommerce\resources\views/admin/shipping/index.blade.php ENDPATH**/ ?>