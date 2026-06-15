<?php $__env->startSection('title', $page['title'] . ' - ' . site('store_name')); ?>
<?php $__env->startSection('description', $page['intro'] ?? ''); ?>

<?php $__env->startSection('content'); ?>
<?php
    $colorMap = [
        'blue' => ['gradient' => 'from-blue-600 via-blue-500 to-indigo-500', 'bg' => 'bg-blue-50', 'border' => 'border-blue-500', 'icon' => 'bg-blue-100 text-blue-600', 'text' => 'text-blue-600', 'accent' => 'blue'],
        'green' => ['gradient' => 'from-emerald-600 via-emerald-500 to-teal-500', 'bg' => 'bg-emerald-50', 'border' => 'border-emerald-500', 'icon' => 'bg-emerald-100 text-emerald-600', 'text' => 'text-emerald-600', 'accent' => 'emerald'],
        'purple' => ['gradient' => 'from-purple-600 via-purple-500 to-pink-500', 'bg' => 'bg-purple-50', 'border' => 'border-purple-500', 'icon' => 'bg-purple-100 text-purple-600', 'text' => 'text-purple-600', 'accent' => 'purple'],
        'indigo' => ['gradient' => 'from-indigo-600 via-indigo-500 to-blue-500', 'bg' => 'bg-indigo-50', 'border' => 'border-indigo-500', 'icon' => 'bg-indigo-100 text-indigo-600', 'text' => 'text-indigo-600', 'accent' => 'indigo'],
        'red' => ['gradient' => 'from-rose-600 via-rose-500 to-pink-500', 'bg' => 'bg-rose-50', 'border' => 'border-rose-500', 'icon' => 'bg-rose-100 text-rose-600', 'text' => 'text-rose-600', 'accent' => 'rose'],
    ];
    $color = $colorMap[$page['color'] ?? 'indigo'] ?? $colorMap['indigo'];
?>


<section class="relative overflow-hidden bg-gradient-to-l <?php echo e($color['gradient']); ?> text-white">
    
    <div class="absolute inset-0 opacity-10">
        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
            <defs>
                <pattern id="page-pattern" x="0" y="0" width="50" height="50" patternUnits="userSpaceOnUse">
                    <circle cx="25" cy="25" r="1.5" fill="white"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#page-pattern)"/>
        </svg>
    </div>
    <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>

    <div class="container-app relative z-10 py-12 md:py-16">
        
        <nav class="flex items-center gap-2 text-sm text-white/80 mb-6">
            <a href="<?php echo e(route('home')); ?>" class="hover:text-white transition flex items-center gap-1">
                <i class="fas fa-home text-xs"></i>
                الرئيسية
            </a>
            <i class="fas fa-chevron-left text-[10px] text-white/50"></i>
            <span class="text-white font-medium"><?php echo e($page['title']); ?></span>
        </nav>

        <div class="flex items-center gap-5">
            <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-4xl border border-white/30 shadow-lg">
                <i class="fas <?php echo e($page['icon']); ?>"></i>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold mb-2 text-balance"><?php echo e($page['title']); ?></h1>
                <?php if($page['intro'] ?? null): ?>
                    <p class="text-white/90 text-lg max-w-2xl text-pretty"><?php echo e(Str::limit($page['intro'], 120)); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>


<div class="container-app py-10 md:py-16">
    <div class="max-w-4xl mx-auto">

        
        <?php if($page['intro'] ?? null): ?>
            <div class="mb-10 p-6 <?php echo e($color['bg']); ?> border-r-4 <?php echo e($color['border']); ?> rounded-2xl animate-fade-up">
                <div class="flex items-start gap-3">
                    <i class="fas fa-quote-right text-2xl <?php echo e($color['text']); ?> flex-shrink-0 mt-1"></i>
                    <p class="text-gray-700 leading-relaxed text-base md:text-lg"><?php echo e($page['intro']); ?></p>
                </div>
            </div>
        <?php endif; ?>

        
        <?php if(($page['sections'] ?? []) && count($page['sections']) > 0): ?>
            <div class="space-y-5 mb-10">
                <?php $__currentLoopData = $page['sections']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card card-hover animate-fade-up" style="animation-delay: <?php echo e($i * 60); ?>ms">
                        <div class="card-body p-6">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl <?php echo e($color['icon']); ?> flex items-center justify-center font-bold text-lg flex-shrink-0">
                                    <?php echo e($i + 1); ?>

                                </div>
                                <div class="flex-1 min-w-0">
                                    <h2 class="font-bold text-lg md:text-xl text-gray-800 mb-2 leading-snug"><?php echo e($section['title']); ?></h2>
                                    <p class="text-gray-600 leading-relaxed text-base"><?php echo e($section['body']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        
        <div class="card overflow-hidden mt-10">
            <div class="bg-gradient-to-l <?php echo e($color['gradient']); ?> text-white p-8 md:p-10 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30">
                    <i class="fas fa-headset text-3xl"></i>
                </div>
                <h3 class="text-2xl md:text-3xl font-bold mb-2">هل تحتاج مساعدة؟</h3>
                <p class="text-white/90 mb-6 text-lg">فريق خدمة العملاء جاهز للرد على استفساراتك</p>
                <div class="flex flex-wrap justify-center gap-3">
                    <a href="<?php echo e(route('page.show', 'contact')); ?>" class="btn btn-lg bg-white text-gray-800 hover:bg-gray-100 shadow-lg">
                        <i class="fas fa-envelope"></i>
                        صفحة الاتصال
                    </a>
                    <a href="https://wa.me/2490674784859" target="_blank" class="btn btn-lg bg-green-500 hover:bg-green-600 text-white shadow-lg">
                        <i class="fab fa-whatsapp text-xl"></i>
                        واتساب
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\amarn\ecommerce\resources\views/frontend/page.blade.php ENDPATH**/ ?>