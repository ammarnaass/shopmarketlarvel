<?php $__env->startSection('title', 'تسجيل الدخول - ' . site('store_name')); ?>
<?php $__env->startSection('description', 'سجل دخولك إلى حسابك في ' . site('store_name')); ?>

<?php $__env->startSection('content'); ?>

<section class="min-h-[80vh] flex items-center py-12 bg-gradient-to-bl from-gray-50 via-white to-brand-50/30">
    <div class="container-app">
        <div class="max-w-md mx-auto">
            
            <div class="card animate-fade-up overflow-hidden">
                <div class="bg-gradient-to-l from-brand-600 via-brand-500 to-accent-500 text-white p-6 text-center">
                    <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30">
                        <span class="material-symbols-outlined text-2xl">login</span>
                    </div>
                    <h1 class="text-2xl font-extrabold mb-1">مرحباً بعودتك</h1>
                    <p class="text-white/90 text-sm">سجل دخولك للمتابعة</p>
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

                    <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <div>
                            <label class="form-label">البريد الإلكتروني</label>
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

                        <div>
                            <label class="form-label">كلمة المرور</label>
                            <div class="relative">
                                <input type="password" name="password" required
                                       placeholder="••••••••"
                                       autocomplete="current-password"
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

                        <div class="flex items-center justify-between text-sm">
                            <label class="flex items-center gap-2 cursor-pointer text-gray-600">
                                <input type="checkbox" name="remember" class="form-checkbox">
                                <span>تذكرني</span>
                            </label>
                            <a href="#" class="text-brand-600 font-semibold hover:underline">
                                نسيت كلمة المرور؟
                            </a>
                        </div>

                        <button type="submit" class="btn-primary btn-block btn-lg mt-2 bg-gradient-to-l from-brand-600 to-accent-500 hover:from-brand-700 hover:to-accent-600">
                            <span class="material-symbols-outlined">login</span>
                            تسجيل الدخول
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
                        ليس لديك حساب؟
                        <a href="<?php echo e(route('register')); ?>" class="text-brand-600 font-bold hover:underline">
                            سجل الآن
                        </a>
                    </p>
                </div>
            </div>

            <p class="text-center text-xs text-gray-500 mt-6">
                بتسجيل دخولك، أنت توافق على
                <a href="<?php echo e(route('page.show', 'terms')); ?>" class="text-brand-600 hover:underline">الشروط والأحكام</a>
                و
                <a href="<?php echo e(route('page.show', 'privacy')); ?>" class="text-brand-600 hover:underline">سياسة الخصوصية</a>
            </p>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\amarn\ecommerce\resources\views/frontend/auth/login.blade.php ENDPATH**/ ?>