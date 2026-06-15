<?php $__env->startSection('title', 'الإعدادات'); ?>

<?php
$activeTab = request('tab', 'store');
$tabs = [
    'store' => ['icon' => 'store', 'title' => 'المتجر'],
    'currency' => ['icon' => 'payments', 'title' => 'العملة والمنطقة'],
    'social' => ['icon' => 'share', 'title' => 'التواصل الاجتماعي'],
    'contact' => ['icon' => 'headset_mic', 'title' => 'معلومات الاتصال'],
    'seo' => ['icon' => 'search', 'title' => 'SEO ومحركات البحث'],
];
?>

<?php $__env->startPush('styles'); ?>
<style>
    .settings-card {
        background: white;
        border: 1px solid #e1e2ed;
        box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);
        transition: transform 0.2s ease;
    }
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    input:focus, select:focus, textarea:focus {
        outline: none !important;
        border-color: #004ac6 !important;
        box-shadow: 0 0 0 2px rgba(0, 74, 198, 0.2) !important;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<nav class="flex items-center gap-2 text-on-surface-variant text-sm mb-3">
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="hover:text-primary transition-colors">لوحة القيادة</a>
    <span class="material-symbols-outlined text-xs">chevron_left</span>
    <a href="<?php echo e(route('admin.settings.index')); ?>" class="hover:text-primary transition-colors">الإعدادات</a>
    <span class="material-symbols-outlined text-xs">chevron_left</span>
    <span class="text-primary font-semibold"><?php echo e($tabs[$activeTab]['title'] ?? 'عام'); ?></span>
</nav>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <h3 class="text-2xl font-bold text-on-surface">الإعدادات العامة</h3>
    <button type="submit" form="settings-form"
            class="px-6 py-2.5 rounded-xl bg-primary text-white font-medium hover:bg-primary-container shadow-sm transition-all active:scale-95 flex items-center gap-2">
        <span class="material-symbols-outlined text-sm">save</span>
        حفظ جميع الإعدادات
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
    <div class="flex border-b border-outline-variant overflow-x-auto">
        <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('admin.settings.index', ['tab' => $key])); ?>#<?php echo e($key); ?>"
               class="flex items-center gap-2 px-5 py-3.5 font-medium text-sm whitespace-nowrap transition-all <?php echo e($activeTab === $key ? 'border-b-2 border-primary text-primary bg-primary-fixed/30' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-low'); ?>">
                <span class="material-symbols-outlined text-lg"><?php echo e($tab['icon']); ?></span>
                <?php echo e($tab['title']); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" enctype="multipart/form-data" id="settings-form">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="group" value="<?php echo e($activeTab); ?>">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8 space-y-8">

            <?php if($activeTab === 'store'): ?>
            <section class="settings-card rounded-xl p-6">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-outline-variant">
                    <span class="material-symbols-outlined text-primary">storefront</span>
                    <h4 class="font-semibold text-lg">معلومات المتجر</h4>
                </div>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant">اسم المتجر <span class="text-error">*</span></label>
                        <input type="text" name="store_name" value="<?php echo e(old('store_name', $settings['store']['store_name'])); ?>" required
                               class="w-full rounded-lg border-outline-variant bg-white p-2.5 text-body-md <?php $__errorArgs = ['store_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['store_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant">البريد الإلكتروني <span class="text-error">*</span></label>
                        <input type="email" name="store_email" value="<?php echo e(old('store_email', $settings['store']['store_email'])); ?>" required
                               class="w-full rounded-lg border-outline-variant bg-white p-2.5 text-body-md <?php $__errorArgs = ['store_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['store_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant">رقم الهاتف <span class="text-error">*</span></label>
                        <input type="text" name="store_phone" value="<?php echo e(old('store_phone', $settings['store']['store_phone'])); ?>" required
                               class="w-full rounded-lg border-outline-variant bg-white p-2.5 text-body-md <?php $__errorArgs = ['store_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['store_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant">العنوان</label>
                        <input type="text" name="store_address" value="<?php echo e(old('store_address', $settings['store']['store_address'])); ?>"
                               class="w-full rounded-lg border-outline-variant bg-white p-2.5 text-body-md <?php $__errorArgs = ['store_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['store_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant">وصف المتجر</label>
                        <textarea name="store_description" rows="3"
                                  class="w-full rounded-lg border-outline-variant bg-white p-2.5 text-body-md <?php $__errorArgs = ['store_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('store_description', $settings['store']['store_description'])); ?></textarea>
                        <p class="text-xs text-on-surface-variant mt-1">يظهر في الصفحة الرئيسية وتذييل الموقع وفي نتائج البحث</p>
                        <?php $__errorArgs = ['store_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </section>

            <section class="settings-card rounded-xl p-6">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-outline-variant">
                    <span class="material-symbols-outlined text-primary">image</span>
                    <h4 class="font-semibold text-lg">الشعار والأيقونة</h4>
                </div>

                <div class="space-y-3 mb-6">
                    <label class="block text-sm font-medium text-on-surface-variant">شعار المتجر (Logo)</label>
                    <?php
                        $logoVal = $settings['store']['store_logo'];
                        $logoUrl = $logoVal && !preg_match('#^https?://#i', $logoVal) ? asset('storage/' . $logoVal) : $logoVal;
                    ?>
                    <?php if($logoVal): ?>
                    <div class="bg-surface-container-low border-2 border-dashed border-outline-variant rounded-lg p-3 flex items-center gap-3">
                        <img src="<?php echo e($logoUrl); ?>" alt="logo" class="h-16 w-16 object-contain bg-white rounded border p-1">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-on-surface-variant truncate" dir="ltr"><?php echo e($logoVal); ?></p>
                            <p class="text-xs text-emerald-600 mt-0.5 flex items-center gap-1"><span class="material-symbols-outlined text-sm">check_circle</span> شعار حالي</p>
                        </div>
                        <button type="button" onclick="if(confirm('حذف الشعار؟')) document.getElementById('remove-store-logo-form').submit()" class="bg-error-container/30 hover:bg-error-container text-error px-3 py-1.5 rounded text-xs flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">delete</span> حذف
                        </button>
                    </div>
                    <?php endif; ?>
                    <div class="grid md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-on-surface-variant mb-1">رفع ملف من الجهاز</label>
                            <input type="file" name="store_logo_file" accept="image/jpeg,image/jpg,image/png,image/webp,image/svg+xml" class="w-full text-sm file:rounded-lg file:border-0 file:bg-primary-fixed file:text-primary file:px-3 file:py-1.5 file:text-xs <?php $__errorArgs = ['store_logo_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <p class="text-xs text-on-surface-variant mt-1">
                                <span class="material-symbols-outlined text-xs align-text-bottom">info</span>
                                JPEG, PNG, WEBP, SVG — حتى 1MB<br>
                                <span class="inline-block bg-primary-fixed/50 text-primary px-1.5 py-0.5 rounded mt-0.5">موصى به: 500×500 بكسل PNG شفاف</span>
                            </p>
                            <?php $__errorArgs = ['store_logo_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-on-surface-variant mb-1">أو رابط URL خارجي</label>
                            <input type="url" name="store_logo" value="<?php echo e(old('store_logo', $logoVal && preg_match('#^https?://#i', $logoVal) ? $logoVal : '')); ?>" class="w-full rounded-lg border-outline-variant bg-white p-2.5 text-body-md font-mono text-sm" placeholder="https://...">
                        </div>
                    </div>
                </div>

                <div class="space-y-3 pt-4 border-t border-outline-variant">
                    <label class="block text-sm font-medium text-on-surface-variant">أيقونة المتصفح (Favicon)</label>
                    <?php
                        $favVal = $settings['store']['store_favicon'] ?? '';
                        $favUrl = $favVal && !preg_match('#^https?://#i', $favVal) ? asset('storage/' . $favVal) : $favVal;
                    ?>
                    <?php if($favVal): ?>
                    <div class="bg-surface-container-low border-2 border-dashed border-outline-variant rounded-lg p-3 flex items-center gap-3">
                        <img src="<?php echo e($favUrl); ?>" alt="favicon" class="h-10 w-10 object-contain bg-white rounded border p-1">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-on-surface-variant truncate" dir="ltr"><?php echo e($favVal); ?></p>
                            <p class="text-xs text-emerald-600 mt-0.5 flex items-center gap-1"><span class="material-symbols-outlined text-sm">check_circle</span> أيقونة حالية</p>
                        </div>
                        <button type="button" onclick="if(confirm('حذف الأيقونة؟')) document.getElementById('remove-store-favicon-form').submit()" class="bg-error-container/30 hover:bg-error-container text-error px-3 py-1.5 rounded text-xs flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">delete</span> حذف
                        </button>
                    </div>
                    <?php endif; ?>
                    <div class="grid md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-on-surface-variant mb-1">رفع ملف من الجهاز</label>
                            <input type="file" name="store_favicon_file" accept="image/x-icon,image/png,image/svg+xml,.ico" class="w-full text-sm file:rounded-lg file:border-0 file:bg-primary-fixed file:text-primary file:px-3 file:py-1.5 file:text-xs <?php $__errorArgs = ['store_favicon_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <p class="text-xs text-on-surface-variant mt-1">
                                <span class="material-symbols-outlined text-xs align-text-bottom">info</span>
                                ICO, PNG, SVG — حتى 256KB<br>
                                <span class="inline-block bg-primary-fixed/50 text-primary px-1.5 py-0.5 rounded mt-0.5">موصى به: 64×64 أو 32×32 بكسل</span>
                            </p>
                            <?php $__errorArgs = ['store_favicon_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-on-surface-variant mb-1">أو رابط URL خارجي</label>
                            <input type="url" name="store_favicon" value="<?php echo e(old('store_favicon', $favVal && preg_match('#^https?://#i', $favVal) ? $favVal : '')); ?>" class="w-full rounded-lg border-outline-variant bg-white p-2.5 text-body-md font-mono text-sm" placeholder="https://...">
                        </div>
                    </div>
                </div>
            </section>

            <?php elseif($activeTab === 'currency'): ?>
            <section class="settings-card rounded-xl p-6">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-outline-variant">
                    <span class="material-symbols-outlined text-primary">payments</span>
                    <h4 class="font-semibold text-lg">إعدادات العملة والمنطقة</h4>
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6 flex items-start gap-3">
                    <span class="material-symbols-outlined text-amber-600 shrink-0">info</span>
                    <p class="text-sm text-amber-800">اختر الدولة الافتراضية للمتجر. ستُعرض أسعار المنتجات والعملة ورمز الاتصال وفقاً للدولة المختارة. يمكن للزوار تغييرها من القائمة في الـ header.</p>
                </div>

                <?php
                    $countries = config('ecommerce.countries', []);
                    $currentDefault = \App\Models\Setting::get('default_country', config('ecommerce.default_country', 'SD'));
                ?>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant">الدولة الافتراضية (متجر) *</label>
                        <select name="default_country" required
                                class="w-full rounded-lg border-outline-variant bg-white p-2.5 text-body-md">
                            <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($code); ?>" <?php echo e($currentDefault === $code ? 'selected' : ''); ?>>
                                    <?php echo e($info['flag'] ?? ''); ?> <?php echo e($info['name']); ?> - <?php echo e($info['name_en']); ?> (<?php echo e($info['currency_symbol'] ?? ''); ?> <?php echo e($info['currency'] ?? ''); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <p class="text-xs text-on-surface-variant mt-1">الدولة التي يستخدمها الزائر الجديد افتراضياً. يمكن للزوار تغييرها من الـ header.</p>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant">العملة الاحتياطية (للتسوية)</label>
                        <select name="fallback_currency"
                                class="w-full rounded-lg border-outline-variant bg-white p-2.5 text-body-md">
                            <?php $fallbackCurr = \App\Models\Setting::get('fallback_currency', 'SDG'); ?>
                            <?php
                                $currencies = ['SDG' => 'جنيه سوداني', 'EGP' => 'جنيه مصري', 'DZD' => 'دينار جزائري', 'MAD' => 'درهم مغربي', 'TND' => 'دينار تونسي', 'LYD' => 'دينار ليبي', 'USD' => 'دولار أمريكي', 'EUR' => 'يورو'];
                            ?>
                            <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($code); ?>" <?php echo e($fallbackCurr === $code ? 'selected' : ''); ?>><?php echo e($name); ?> (<?php echo e($code); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <p class="text-xs text-on-surface-variant mt-1">العملة المستخدمة لتقارير المبيعات (التحويلات).</p>
                    </div>
                </div>

                <div class="mt-6 p-5 bg-gradient-to-l from-amber-50 to-yellow-50 border-2 border-amber-200 rounded-lg">
                    <h3 class="font-semibold text-sm mb-3 text-on-surface-variant flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-600">visibility</span>
                        معاينة الدولة المختارة
                    </h3>
                    <?php if(isset($countries[$currentDefault])): ?>
                        <?php $cur = $countries[$currentDefault]; ?>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                            <div class="bg-white p-3 rounded-lg">
                                <p class="text-xs text-on-surface-variant">العملة</p>
                                <p class="text-2xl font-bold text-on-surface mt-1"><?php echo e($cur['currency_symbol'] ?? '—'); ?></p>
                                <p class="text-xs text-on-surface-variant mt-1"><?php echo e($cur['currency'] ?? '—'); ?></p>
                            </div>
                            <div class="bg-white p-3 rounded-lg">
                                <p class="text-xs text-on-surface-variant">رمز الاتصال</p>
                                <p class="text-2xl font-bold text-on-surface mt-1"><?php echo e($cur['dial_code'] ?? '—'); ?></p>
                            </div>
                            <div class="bg-white p-3 rounded-lg">
                                <p class="text-xs text-on-surface-variant">الاسم</p>
                                <p class="text-sm font-bold text-on-surface mt-1"><?php echo e($cur['name'] ?? '—'); ?></p>
                            </div>
                            <div class="bg-white p-3 rounded-lg">
                                <p class="text-xs text-on-surface-variant">العملة الإنجليزية</p>
                                <p class="text-sm font-bold text-on-surface mt-1"><?php echo e($cur['name_en'] ?? '—'); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mt-6">
                    <h3 class="font-semibold text-sm mb-3 text-on-surface-variant flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-600">language</span>
                        الدول المدعومة
                    </h3>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-3 bg-white border-2 <?php echo e($currentDefault === $code ? 'border-primary bg-primary-fixed/30' : 'border-outline-variant'); ?> rounded-lg flex items-center gap-3">
                                <span class="text-3xl"><?php echo e($info['flag'] ?? '🏳️'); ?></span>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-sm truncate"><?php echo e($info['name']); ?></p>
                                    <p class="text-xs text-on-surface-variant"><?php echo e($info['name_en']); ?> • <?php echo e($info['currency_symbol'] ?? ''); ?> <?php echo e($info['currency'] ?? ''); ?></p>
                                </div>
                                <?php if($currentDefault === $code): ?>
                                    <span class="bg-primary text-on-primary text-xs px-2 py-0.5 rounded-full">الافتراضي</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </section>

            <?php elseif($activeTab === 'social'): ?>
            <section class="settings-card rounded-xl p-6">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-outline-variant">
                    <span class="material-symbols-outlined text-primary">share</span>
                    <h4 class="font-semibold text-lg">التواصل الاجتماعي</h4>
                </div>

                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-6 flex items-start gap-3">
                    <span class="material-symbols-outlined text-purple-600 shrink-0">info</span>
                    <p class="text-sm text-purple-800">أضف روابط حساباتك على وسائل التواصل الاجتماعي. الروابط الفارغة لن تظهر في الموقع.</p>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant"><span class="font-bold text-blue-600 ml-1">f</span> Facebook</label>
                        <input type="url" name="facebook_url" value="<?php echo e(old('facebook_url', $settings['social']['facebook_url'])); ?>" placeholder="https://facebook.com/yourpage"
                               class="w-full rounded-lg border-outline-variant bg-white p-2.5 text-body-md <?php $__errorArgs = ['facebook_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['facebook_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant"><span class="font-bold text-sky-500 ml-1">𝕏</span> Twitter / X</label>
                        <input type="url" name="twitter_url" value="<?php echo e(old('twitter_url', $settings['social']['twitter_url'])); ?>" placeholder="https://twitter.com/yourhandle"
                               class="w-full rounded-lg border-outline-variant bg-white p-2.5 text-body-md <?php $__errorArgs = ['twitter_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['twitter_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant"><span class="material-symbols-outlined text-pink-600 align-middle text-lg">photo_camera</span> Instagram</label>
                        <input type="url" name="instagram_url" value="<?php echo e(old('instagram_url', $settings['social']['instagram_url'])); ?>" placeholder="https://instagram.com/yourhandle"
                               class="w-full rounded-lg border-outline-variant bg-white p-2.5 text-body-md <?php $__errorArgs = ['instagram_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['instagram_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant"><span class="material-symbols-outlined text-red-600 align-middle text-lg">play_circle</span> YouTube</label>
                        <input type="url" name="youtube_url" value="<?php echo e(old('youtube_url', $settings['social']['youtube_url'])); ?>" placeholder="https://youtube.com/@yourchannel"
                               class="w-full rounded-lg border-outline-variant bg-white p-2.5 text-body-md <?php $__errorArgs = ['youtube_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['youtube_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant"><span class="material-symbols-outlined text-emerald-600 align-middle text-lg">chat</span> رقم الواتساب (مع رمز الدولة)</label>
                        <input type="text" name="whatsapp_number" value="<?php echo e(old('whatsapp_number', $settings['social']['whatsapp_number'])); ?>" placeholder="24990000000"
                               class="w-full rounded-lg border-outline-variant bg-white p-2.5 text-body-md <?php $__errorArgs = ['whatsapp_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <p class="text-xs text-on-surface-variant mt-1">يستخدم في زر الواتساب العائم في الموقع. أدخل الرقم بدون + أو مسافات</p>
                        <?php $__errorArgs = ['whatsapp_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </section>

            <?php elseif($activeTab === 'contact'): ?>
            <section class="settings-card rounded-xl p-6">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-outline-variant">
                    <span class="material-symbols-outlined text-primary">headset_mic</span>
                    <h4 class="font-semibold text-lg">معلومات الاتصال</h4>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant">البريد الإلكتروني <span class="text-error">*</span></label>
                        <input type="email" name="contact_email" value="<?php echo e(old('contact_email', $settings['contact']['contact_email'])); ?>" required
                               class="w-full rounded-lg border-outline-variant bg-white p-2.5 text-body-md <?php $__errorArgs = ['contact_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['contact_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant">رقم الهاتف <span class="text-error">*</span></label>
                        <input type="text" name="contact_phone" value="<?php echo e(old('contact_phone', $settings['contact']['contact_phone'])); ?>" required
                               class="w-full rounded-lg border-outline-variant bg-white p-2.5 text-body-md <?php $__errorArgs = ['contact_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['contact_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant">واتساب التواصل</label>
                        <input type="text" name="contact_whatsapp" value="<?php echo e(old('contact_whatsapp', $settings['contact']['contact_whatsapp'])); ?>" placeholder="24990000000"
                               class="w-full rounded-lg border-outline-variant bg-white p-2.5 text-body-md">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant">ساعات العمل</label>
                        <input type="text" name="contact_hours" value="<?php echo e(old('contact_hours', $settings['contact']['contact_hours'])); ?>" placeholder="24/7"
                               class="w-full rounded-lg border-outline-variant bg-white p-2.5 text-body-md">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant">العنوان الفعلي</label>
                        <textarea name="contact_address" rows="2"
                                  class="w-full rounded-lg border-outline-variant bg-white p-2.5 text-body-md <?php $__errorArgs = ['contact_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('contact_address', $settings['contact']['contact_address'])); ?></textarea>
                        <?php $__errorArgs = ['contact_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </section>

            <?php elseif($activeTab === 'seo'): ?>
            <section class="settings-card rounded-xl p-6">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-outline-variant">
                    <span class="material-symbols-outlined text-primary">search</span>
                    <h4 class="font-semibold text-lg">SEO ومحركات البحث</h4>
                </div>

                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6 flex items-start gap-3">
                    <span class="material-symbols-outlined text-orange-600 shrink-0">lightbulb</span>
                    <p class="text-sm text-orange-800">هذه المعلومات تستخدم لتحسين ظهور موقعك في نتائج Google ومحركات البحث الأخرى.</p>
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant">عنوان SEO (Meta Title)</label>
                        <input type="text" name="seo_meta_title" value="<?php echo e(old('seo_meta_title', $settings['seo']['seo_meta_title'])); ?>" placeholder="Amar Store - أفضل متجر إلكتروني"
                               class="w-full rounded-lg border-outline-variant bg-white p-2.5 text-body-md <?php $__errorArgs = ['seo_meta_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <p class="text-xs text-on-surface-variant mt-1">حد مثالي: 50-60 حرف. يظهر في تبويب المتصفح ونتائج البحث.</p>
                        <?php $__errorArgs = ['seo_meta_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant">وصف SEO (Meta Description)</label>
                        <textarea name="seo_meta_description" rows="3" maxlength="500"
                                  class="w-full rounded-lg border-outline-variant bg-white p-2.5 text-body-md <?php $__errorArgs = ['seo_meta_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  placeholder="وصف مختصر لمتجرك يظهر في نتائج البحث"><?php echo e(old('seo_meta_description', $settings['seo']['seo_meta_description'])); ?></textarea>
                        <p class="text-xs text-on-surface-variant mt-1">حد مثالي: 150-160 حرف</p>
                        <?php $__errorArgs = ['seo_meta_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant">الكلمات المفتاحية (مفصولة بفواصل)</label>
                        <input type="text" name="seo_meta_keywords" value="<?php echo e(old('seo_meta_keywords', $settings['seo']['seo_meta_keywords'])); ?>" placeholder="متجر, تسوق, إلكتروني, ملابس, إلكترونيات"
                               class="w-full rounded-lg border-outline-variant bg-white p-2.5 text-body-md <?php $__errorArgs = ['seo_meta_keywords'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['seo_meta_keywords'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-on-surface-variant">صورة Open Graph (المشاركة على Facebook/Twitter)</label>
                        <?php
                            $ogVal = $settings['seo']['seo_og_image'];
                            $ogUrl = $ogVal && !preg_match('#^https?://#i', $ogVal) ? asset('storage/' . $ogVal) : $ogVal;
                        ?>
                        <?php if($ogVal): ?>
                        <div class="bg-surface-container-low border-2 border-dashed border-outline-variant rounded-lg p-3 flex items-center gap-3">
                            <img src="<?php echo e($ogUrl); ?>" alt="og image" class="h-20 w-32 object-cover rounded border">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-on-surface-variant truncate" dir="ltr"><?php echo e($ogVal); ?></p>
                                <p class="text-xs text-emerald-600 mt-0.5 flex items-center gap-1"><span class="material-symbols-outlined text-sm">check_circle</span> صورة حالية</p>
                            </div>
                            <button type="button" onclick="if(confirm('حذف صورة OG؟')) document.getElementById('remove-og-form').submit()" class="bg-error-container/30 hover:bg-error-container text-error px-3 py-1.5 rounded text-xs flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">delete</span> حذف
                            </button>
                        </div>
                        <?php endif; ?>
                        <div class="grid md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-on-surface-variant mb-1">رفع ملف من الجهاز</label>
                                <input type="file" name="seo_og_image_file" accept="image/jpeg,image/jpg,image/png,image/webp" class="w-full text-sm file:rounded-lg file:border-0 file:bg-primary-fixed file:text-primary file:px-3 file:py-1.5 file:text-xs <?php $__errorArgs = ['seo_og_image_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <p class="text-xs text-on-surface-variant mt-1">
                                    <span class="material-symbols-outlined text-xs align-text-bottom">info</span>
                                    JPEG, PNG, WEBP — حتى 1MB<br>
                                    <span class="inline-block bg-primary-fixed/50 text-primary px-1.5 py-0.5 rounded mt-0.5">موصى به: 1200×630 بكسل</span>
                                </p>
                                <?php $__errorArgs = ['seo_og_image_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-on-surface-variant mb-1">أو رابط URL خارجي</label>
                                <input type="url" name="seo_og_image" value="<?php echo e(old('seo_og_image', $ogVal && preg_match('#^https?://#i', $ogVal) ? $ogVal : '')); ?>" placeholder="https://..."
                                       class="w-full rounded-lg border-outline-variant bg-white p-2.5 text-body-md font-mono text-sm <?php $__errorArgs = ['seo_og_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['seo_og_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-error text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <?php endif; ?>

        </div>

        <div class="lg:col-span-4 space-y-6">
            <div class="settings-card rounded-xl p-6">
                <div class="flex items-center gap-2 mb-4 pb-4 border-b border-outline-variant">
                    <span class="material-symbols-outlined text-primary">help</span>
                    <h4 class="font-semibold">مساعدة سريعة</h4>
                </div>
                <ul class="space-y-3 text-sm text-on-surface-variant">
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-xs mt-0.5 text-primary">check_circle</span>
                        <span>جميع الإعدادات تحفظ تلقائياً عند الضغط على "حفظ"</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-xs mt-0.5 text-primary">info</span>
                        <span>يمكنك العودة للإعدادات الافتراضية في أي وقت</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-xs mt-0.5 text-primary">lightbulb</span>
                        <span>تأكد من إدخال بريد إلكتروني صحيح لاستقبال الإشعارات</span>
                    </li>
                </ul>
            </div>

            <div class="settings-card rounded-xl p-6">
                <div class="flex items-center gap-2 mb-4 pb-4 border-b border-outline-variant">
                    <span class="material-symbols-outlined text-primary">info</span>
                    <h4 class="font-semibold">معلومات</h4>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center py-1">
                        <span class="text-on-surface-variant">آخر تحديث</span>
                        <span class="font-medium">—</span>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <span class="text-on-surface-variant">عدد الإعدادات</span>
                        <span class="font-medium"><?php echo e(count($settings ?? [])); ?> أقسام</span>
                    </div>
                </div>
            </div>

            <div class="settings-card rounded-xl p-6">
                <button type="submit" form="settings-form"
                        class="w-full px-6 py-3 rounded-xl bg-primary text-white font-medium hover:bg-primary-container shadow-sm transition-all active:scale-95 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">save</span>
                    حفظ التغييرات
                </button>
            </div>
        </div>
    </div>
</form>

<form id="remove-store-logo-form" method="POST" action="<?php echo e(route('admin.settings.removeImage')); ?>" style="display:none">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="key" value="store_logo">
</form>
<form id="remove-store-favicon-form" method="POST" action="<?php echo e(route('admin.settings.removeImage')); ?>" style="display:none">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="key" value="store_favicon">
</form>
<form id="remove-og-form" method="POST" action="<?php echo e(route('admin.settings.removeImage')); ?>" style="display:none">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="key" value="seo_og_image">
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\amarn\ecommerce\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>