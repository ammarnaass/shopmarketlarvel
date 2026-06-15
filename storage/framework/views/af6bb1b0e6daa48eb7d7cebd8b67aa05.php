<?php $__env->startSection('title', 'حسابي - ' . site('store_name')); ?>
<?php $__env->startSection('description', 'إدارة حسابك الشخصي في ' . site('store_name')); ?>

<?php $__env->startSection('content'); ?>
<?php
    $countries = config('ecommerce.countries', []);
?>

<section class="bg-gradient-to-l from-brand-600 via-brand-500 to-accent-500 text-white py-10 md:py-14">
    <div class="container-app">
        <nav class="flex items-center gap-2 text-sm text-white/80 mb-4">
            <a href="<?php echo e(route('home')); ?>" class="hover:text-white transition flex items-center gap-1">
                <span class="material-symbols-outlined text-xs">home</span>
                الرئيسية
            </a>
            <span class="material-symbols-outlined text-[10px] text-white/50">chevron_right</span>
            <span class="text-white font-medium">حسابي</span>
        </nav>
        <h1 class="text-3xl md:text-4xl font-extrabold mb-2">حسابي</h1>
        <p class="text-white/90">إدارة بياناتك الشخصية، عناوينك، وكلمات المرور</p>
    </div>
</section>

<div class="container-app py-8 md:py-10" x-data="{ tab: 'profile' }">
    <?php if(session('success')): ?>
        <div class="alert alert-success mb-5 animate-slide-down">
            <span class="material-symbols-outlined text-lg">check_circle</span>
            <span><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>
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

    <div class="grid lg:grid-cols-4 gap-6">
        
        <aside class="lg:col-span-1">
            <div class="card sticky top-24 animate-fade-up">
                
                <div class="p-5 text-center border-b border-gray-100">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-brand-500 to-accent-500 mx-auto flex items-center justify-center text-white text-3xl font-extrabold shadow-lg">
                        <?php echo e(mb_substr($user->name, 0, 1)); ?>

                    </div>
                    <div class="font-bold mt-3 text-gray-800"><?php echo e($user->name); ?></div>
                    <div class="text-xs text-gray-500 mt-0.5"><?php echo e($user->email); ?></div>
                    <?php if($user->roles->count() > 0): ?>
                        <div class="mt-2 flex justify-center gap-1 flex-wrap">
                            <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge badge-primary text-[10px]"><?php echo e($r->display_name ?? $r->name); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <nav class="p-3 space-y-1">
                    <button @click="tab='profile'" :class="tab==='profile' ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-right px-3 py-2.5 rounded-lg text-sm transition flex items-center gap-2">
                        <span class="material-symbols-outlined text-xs w-4">person</span>
                        البيانات الشخصية
                    </button>
                    <button @click="tab='addresses'" :class="tab==='addresses' ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-right px-3 py-2.5 rounded-lg text-sm transition flex items-center gap-2">
                        <span class="material-symbols-outlined text-xs w-4">location_on</span>
                        العناوين
                    </button>
                    <button @click="tab='password'" :class="tab==='password' ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-right px-3 py-2.5 rounded-lg text-sm transition flex items-center gap-2">
                        <span class="material-symbols-outlined text-xs w-4">lock</span>
                        كلمة المرور
                    </button>
                    <a href="<?php echo e(route('orders.index')); ?>" class="w-full text-right px-3 py-2.5 rounded-lg text-sm transition flex items-center gap-2 text-gray-700 hover:bg-gray-50">
                        <span class="material-symbols-outlined text-xs w-4">inventory_2</span>
                        طلباتي (<?php echo e($user->orders->count()); ?>)
                    </a>
                    <a href="<?php echo e(route('wishlist.index')); ?>" class="w-full text-right px-3 py-2.5 rounded-lg text-sm transition flex items-center gap-2 text-gray-700 hover:bg-gray-50">
                        <span class="material-symbols-outlined text-xs w-4">favorite</span>
                        المفضلة
                    </a>
                </nav>
            </div>
        </aside>

        
        <div class="lg:col-span-3 space-y-5">

            
            <div x-show="tab==='profile'" x-cloak class="card animate-fade-up">
                <div class="card-header">
                    <h2 class="font-bold text-lg flex items-center gap-2">
                        <span class="material-symbols-outlined text-brand-600">person</span>
                        البيانات الشخصية
                    </h2>
                </div>
                <div class="card-body p-5">
                    <form method="POST" action="<?php echo e(route('account.update')); ?>">
                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">الاسم الكامل <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" required
                                       class="form-input <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> form-input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
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
                                <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required
                                       class="form-input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> form-input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
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
                                <label class="form-label">الدولة <span class="text-rose-500">*</span></label>
                                <select name="country_code" class="form-input appearance-none">
                                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($code); ?>" <?php echo e($user->country_code == $code ? 'selected' : ''); ?>>
                                            <?php echo e($info['name']); ?> - <?php echo e($info['name_en']); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">الولاية / المحافظة</label>
                                <input type="text" name="state_code" value="<?php echo e(old('state_code', $user->state_code)); ?>"
                                       class="form-input">
                            </div>
                            <div class="md:col-span-2">
                                <label class="form-label">رقم الهاتف <span class="text-rose-500">*</span></label>
                                <input type="text" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>" required
                                       class="form-input <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> form-input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="mt-5 flex justify-end">
                            <button type="submit" class="btn-primary">
                                <span class="material-symbols-outlined">save</span>
                                حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            
            <div x-show="tab==='addresses'" x-cloak class="card animate-fade-up">
                <div class="card-header flex items-center justify-between">
                    <h2 class="font-bold text-lg flex items-center gap-2">
                        <span class="material-symbols-outlined text-brand-600">location_on</span>
                        عناويني
                    </h2>
                    <span class="badge badge-gray"><?php echo e($user->addresses->count()); ?></span>
                </div>
                <div class="card-body p-5">
                    <?php if($user->addresses->isEmpty()): ?>
                        <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl p-8 text-center mb-5">
                            <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">map</span>
                            <p class="text-gray-500">لا توجد عناوين محفوظة</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3 mb-5">
                            <?php $__currentLoopData = $user->addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="rounded-xl p-4 border-2 transition
                                            <?php echo e($addr->is_default ? 'border-brand-500 bg-brand-50' : 'border-gray-200 hover:border-gray-300'); ?>">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex items-start gap-3 flex-1 min-w-0">
                                            <div class="w-10 h-10 rounded-lg <?php echo e($addr->is_default ? 'bg-brand-500 text-white' : 'bg-gray-100 text-gray-500'); ?> flex items-center justify-center flex-shrink-0">
                                                <span class="material-symbols-outlined">location_on</span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="font-bold text-gray-800 flex items-center gap-2 flex-wrap">
                                                    <?php echo e($addr->name); ?>

                                                    <span class="text-xs text-gray-500 font-normal">(<?php echo e($addr->phone); ?>)</span>
                                                    <?php if($addr->is_default): ?>
                                                        <span class="badge badge-primary text-[10px]"><span class="material-symbols-outlined text-[8px]">check</span>افتراضي</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="text-sm text-gray-600 mt-1"><?php echo e($addr->full_address); ?></div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <?php if(!$addr->is_default): ?>
                                                <form method="POST" action="<?php echo e(route('account.address.default', $addr)); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <button class="text-xs text-brand-600 hover:underline font-semibold">اجعله افتراضي</button>
                                                </form>
                                            <?php endif; ?>
                                            <form method="POST" action="<?php echo e(route('account.address.destroy', $addr)); ?>" onsubmit="return confirm('حذف هذا العنوان؟')">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button class="text-xs text-rose-600 hover:underline font-semibold">حذف</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>

                    <details class="group rounded-xl border-2 border-dashed border-gray-200 hover:border-brand-300 transition">
                        <summary class="cursor-pointer px-4 py-3 font-semibold text-brand-600 flex items-center gap-2 list-none">
                            <span class="material-symbols-outlined">add_circle</span>
                            إضافة عنوان جديد
                        </summary>
                        <form method="POST" action="<?php echo e(route('account.address.store')); ?>" class="mt-3 p-4 border-t border-gray-100 grid md:grid-cols-2 gap-3">
                            <?php echo csrf_field(); ?>
                            <input type="text" name="name" placeholder="الاسم" required class="form-input text-sm">
                            <input type="text" name="phone" placeholder="الهاتف" required class="form-input text-sm">
                            <select name="country_code" class="form-input text-sm appearance-none">
                                <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($code); ?>" <?php echo e(($user->country_code ?? 'SD') == $code ? 'selected' : ''); ?>><?php echo e($info['name']); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <input type="text" name="state_code" placeholder="الولاية (اختياري)" class="form-input text-sm">
                            <input type="text" name="city" placeholder="المدينة" required class="form-input text-sm">
                            <input type="text" name="district" placeholder="الحي (اختياري)" class="form-input text-sm">
                            <input type="text" name="zip" placeholder="الرمز البريدي" class="form-input text-sm md:col-span-2">
                            <textarea name="address" placeholder="العنوان التفصيلي" required class="form-input text-sm md:col-span-2" rows="2"></textarea>
                            <label class="md:col-span-2 flex items-center gap-2 text-sm cursor-pointer">
                                <input type="checkbox" name="is_default" value="1" class="form-checkbox">
                                <span>اجعله افتراضي</span>
                            </label>
                            <div class="md:col-span-2">
                                <button type="submit" class="btn-primary btn-block">
                                <span class="material-symbols-outlined">save</span>
                                    حفظ العنوان
                                </button>
                            </div>
                        </form>
                    </details>
                </div>
            </div>

            
            <div x-show="tab==='password'" x-cloak class="card animate-fade-up">
                <div class="card-header">
                    <h2 class="font-bold text-lg flex items-center gap-2">
                        <span class="material-symbols-outlined text-brand-600">lock</span>
                        تغيير كلمة المرور
                    </h2>
                </div>
                <div class="card-body p-5">
                    <form method="POST" action="<?php echo e(route('account.password')); ?>" class="space-y-4 max-w-md">
                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                        <div>
                            <label class="form-label">كلمة المرور الحالية <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="current_password" required
                                       class="form-input pl-11 <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> form-input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">key</span>
                            </div>
                            <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div>
                            <label class="form-label">كلمة المرور الجديدة <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="password" required minlength="6"
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
                            <p class="form-help"><span class="material-symbols-outlined text-xs ml-1">info</span>يجب أن تكون 6 أحرف على الأقل</p>
                        </div>
                        <div>
                            <label class="form-label">تأكيد كلمة المرور الجديدة <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" required minlength="6"
                                       class="form-input pl-11">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">shield</span>
                            </div>
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="btn-primary">
                                <span class="material-symbols-outlined">shield</span>
                                تحديث كلمة المرور
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\amarn\ecommerce\resources\views/frontend/account/index.blade.php ENDPATH**/ ?>