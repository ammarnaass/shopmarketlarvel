<?php $__env->startSection('title', 'تسجيل جديد - ' . site('store_name')); ?>
<?php $__env->startSection('description', 'أنشئ حساباً جديداً في ' . site('store_name')); ?>

<?php $__env->startSection('content'); ?>
<?php
    $countries = config('ecommerce.countries', []);
    $defaultCountry = old('country_code', config('ecommerce.store.default_country', 'SD'));
?>

<section class="min-h-[80vh] flex items-center py-12 bg-gradient-to-bl from-gray-50 via-white to-accent-50/30">
    <div class="container-app">
        <div class="max-w-2xl mx-auto">
            <div class="card animate-fade-up overflow-hidden">
                <div class="bg-gradient-to-l from-accent-500 via-rose-500 to-pink-500 text-white p-6 text-center">
                    <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30">
                        <span class="material-symbols-outlined text-2xl">person_add</span>
                    </div>
                    <h1 class="text-2xl font-extrabold mb-1">إنشاء حساب جديد</h1>
                    <p class="text-white/90 text-sm">انضم إلينا واستمتع بتجربة تسوق فريدة</p>
                </div>

                <div class="card-body p-6 md:p-8">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger mb-5">
                            <span class="material-symbols-outlined text-lg">warning</span>
                            <div class="flex-1">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <p><?php echo e($error); ?></p>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('register')); ?>" class="space-y-4">
                        <?php echo csrf_field(); ?>

                        <div>
                            <label class="form-label">الاسم الكامل <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="name" value="<?php echo e(old('name')); ?>" required
                                       placeholder="مثال: محمد عبدالله"
                                       class="form-input pl-11 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> form-input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">person</span>
                            </div>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label class="form-label">البريد الإلكتروني <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="email" name="email" value="<?php echo e(old('email')); ?>" required
                                       placeholder="example@email.com"
                                       autocomplete="email"
                                       class="form-input pl-11 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> form-input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">mail</span>
                            </div>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">الدولة <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select name="country_code" id="country_code" required
                                            onchange="updateStates(this.value)"
                                            class="form-input pl-11 appearance-none <?php $__errorArgs = ['country_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> form-input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($code); ?>" <?php echo e($defaultCountry == $code ? 'selected' : ''); ?>

                                                    data-dial="<?php echo e($info['dial_code']); ?>">
                                                <?php echo e($info['name']); ?> (<?php echo e($info['name_en']); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">public</span>
                                </div>
                                <?php $__errorArgs = ['country_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label class="form-label">الولاية / المحافظة</label>
                                <div class="relative">
                                    <select name="state_code" id="state_code" class="form-input pl-11 appearance-none">
                                        <option value="">— اختر ولايتك —</option>
                                        <?php $__currentLoopData = $countries[$defaultCountry]['states'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($code); ?>" <?php echo e(old('state_code') == $code ? 'selected' : ''); ?>><?php echo e($name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">location_on</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="form-label">رقم الهاتف <span class="text-rose-500">*</span></label>
                            <div class="flex gap-2" dir="ltr">
                                <input type="text" id="dial_code" value="<?php echo e($countries[$defaultCountry]['dial_code'] ?? ''); ?>"
                                       readonly
                                       class="w-20 px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-center font-semibold text-gray-600">
                                <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" required
                                       placeholder="5XXXXXXXX"
                                       class="flex-1 form-input text-right <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> form-input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            </div>
                            <p class="form-help"><span class="material-symbols-outlined text-xs ml-1">info</span>أدخل الرقم بدون رمز الدولة</p>
                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">كلمة المرور <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input type="password" name="password" required minlength="6"
                                           placeholder="••••••••"
                                           autocomplete="new-password"
                                           class="form-input pl-11 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> form-input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">lock</span>
                                </div>
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label class="form-label">تأكيد كلمة المرور <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" required
                                           placeholder="••••••••"
                                           autocomplete="new-password"
                                           class="form-input pl-11">
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">lock</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-primary btn-block btn-lg mt-2 bg-gradient-to-l from-accent-500 to-pink-500 hover:from-accent-600 hover:to-pink-600">
                            <span class="material-symbols-outlined">person_add</span>
                            إنشاء حساب
                        </button>
                    </form>

                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="bg-white px-3 text-gray-500">أو</span>
                        </div>
                    </div>

                    <p class="text-center text-sm text-gray-600">
                        لديك حساب بالفعل؟
                        <a href="<?php echo e(route('login')); ?>" class="text-brand-600 font-bold hover:underline">
                            سجل دخولك
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
    $countriesJson = json_encode($countries, JSON_UNESCAPED_UNICODE);
?>
<script>
const countriesData = <?php echo json_encode($countriesJson, 15, 512) ?>;

function updateStates(countryCode) {
    const dialEl = document.getElementById('dial_code');
    const stateEl = document.getElementById('state_code');
    const info = countriesData[countryCode];
    if (!info) return;
    dialEl.value = info.dial_code;
    stateEl.innerHTML = '<option value="">— اختر ولايتك —</option>';
    if (info.states) {
        for (const [code, name] of Object.entries(info.states)) {
            const opt = document.createElement('option');
            opt.value = code;
            opt.textContent = name;
            stateEl.appendChild(opt);
        }
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\amarn\ecommerce\resources\views/frontend/auth/register.blade.php ENDPATH**/ ?>