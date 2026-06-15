<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="theme-color" content="<?php echo e($siteSettings['primary_color'] ?? '#2563eb'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $__env->yieldContent('title', site('store_name', config('app.name'))); ?> - <?php echo e(site('store_name', config('app.name'))); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('description', site('seo_meta_description', site('store_description', 'متجر إلكتروني متكامل'))); ?>">
    <meta name="keywords" content="<?php echo e(site('seo_meta_keywords', '')); ?>">
    <meta property="og:title" content="<?php echo $__env->yieldContent('title', site('seo_meta_title', site('store_name'))); ?>">
    <meta property="og:description" content="<?php echo $__env->yieldContent('description', site('seo_meta_description', site('store_description'))); ?>">
    <meta property="og:image" content="<?php echo e(site('seo_og_image', '')); ?>">
    <meta property="og:type" content="website">

    <link rel="icon" type="image/x-icon" href="<?php echo e(site('store_favicon', 'data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 100 100%27%3E%3Crect width=%27100%27 height=%27100%27 rx=%2720%27 fill=%27%234f46e5%27/%3E%3Ctext x=%2750%27 y=%2765%27 text-anchor=%27middle%27 fill=%27white%27 font-family=%27sans-serif%27 font-size=%2750%27 font-weight=%27bold%27%3EA%3C/text%3E%3C/svg%3E')); ?>">

    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    
    <style>
        :root {
            --color-primary: <?php echo e($siteSettings['primary_color'] ?? '#2563eb'); ?>;
            --color-accent:  <?php echo e($siteSettings['accent_color'] ?? '#f59e0b'); ?>;
        }
        .bg-brand-500 { background-color: var(--color-primary) !important; }
        .bg-brand-600 { background-color: var(--color-primary) !important; }
        .bg-brand-700 { background-color: var(--color-primary) !important; filter: brightness(0.9); }
        .text-brand-500, .text-brand-600, .text-brand-700 { color: var(--color-primary) !important; }
        .border-brand-500, .border-brand-600 { border-color: var(--color-primary) !important; }
        .from-brand-500 { --tw-gradient-from: var(--color-primary) !important; }
        .to-brand-500   { --tw-gradient-to:   var(--color-primary) !important; }
        .from-brand-600 { --tw-gradient-from: var(--color-primary) !important; }
        .to-brand-600   { --tw-gradient-to:   var(--color-primary) !important; }
        .from-brand-700 { --tw-gradient-from: var(--color-primary) !important; }
        .to-brand-700   { --tw-gradient-to:   var(--color-primary) !important; }
        .bg-accent-500 { background-color: var(--color-accent) !important; }
        .text-accent-500, .text-accent-600 { color: var(--color-accent) !important; }
        .hover\:bg-brand-600:hover { background-color: var(--color-primary) !important; }
    </style>

    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap">

    <style>
        /* Inline critical CSS (loaded before Vite) */
        body { font-family: 'IBM Plex Sans Arabic', 'Cairo', 'Tajawal', system-ui, sans-serif; }
        [x-cloak] { display: none !important; }

        /* Page loading screen */
        .page-loader {
            position: fixed;
            inset: 0;
            background: white;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.3s ease;
        }
        .page-loader.fade-out {
            opacity: 0;
            pointer-events: none;
        }
        .page-loader-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #e0e7ff;
            border-top-color: #4f46e5;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>

    
    <style>
        /* Fade transition between page navigations */
        @view-transitions { navigation: auto; }
        ::view-transition-old(root),
        ::view-transition-new(root) { animation-duration: 0.18s; }
        ::view-transition-old(root) { animation: fade-out 0.18s ease-out both; }
        ::view-transition-new(root) { animation: fade-in 0.22s ease-out both; }
        @keyframes fade-out { to { opacity: 0; } }
        @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }

        /* Loading indicator at top of page when navigating */
        html.is-loading { cursor: progress; }
        html.is-loading::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg,
                <?php echo e($siteSettings['primary_color'] ?? '#2563eb'); ?>,
                <?php echo e($siteSettings['accent_color'] ?? '#f59e0b'); ?>);
            z-index: 99999;
            animation: loading-bar 1s ease-in-out infinite;
        }
        @keyframes loading-bar {
            0%   { transform: translateX(-100%); }
            50%  { transform: translateX(0%); }
            100% { transform: translateX(100%); }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col antialiased">

    
    <div class="page-loader" id="pageLoader">
        <div class="page-loader-spinner"></div>
    </div>

    <script>
        window.addEventListener('load', () => {
            const loader = document.getElementById('pageLoader');
            if (loader) {
                setTimeout(() => loader.classList.add('fade-out'), 100);
                setTimeout(() => loader.remove(), 500);
            }
        });
    </script>

    
    <?php echo $__env->make('frontend.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php if(session('success') || session('error') || session('warning') || session('info')): ?>
        <?php
            $type = session('success') ? 'success' : (session('error') ? 'error' : (session('warning') ? 'warning' : 'info'));
            $msg = session('success') ?? session('error') ?? session('warning') ?? session('info');
        ?>
        <div x-data x-cloak
             x-init="$store.toast.show(<?php echo \Illuminate\Support\Js::from($msg)->toHtml() ?>, <?php echo \Illuminate\Support\Js::from($type)->toHtml() ?>)">
        </div>
    <?php endif; ?>

    
    <main class="flex-1">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <?php echo $__env->make('frontend.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('frontend.partials.alpine-components', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php
        $sessionCountry = session('selected_country', config('ecommerce.store.default_country', 'SD'));
        $sessionCurrency = $sessionCountry ? (config('ecommerce.countries.' . $sessionCountry . '.currency_symbol') ?: config('ecommerce.store.currency_symbol', 'ج.س')) : config('ecommerce.store.currency_symbol', 'ج.س');
        $sessionCurrencyCode = $sessionCountry ? (config('ecommerce.countries.' . $sessionCountry . '.currency') ?: config('ecommerce.store.currency', 'SDG')) : config('ecommerce.store.currency', 'SDG');
    ?>
    <script>
        // CSRF token for AJAX requests
        window.Laravel = {
            csrfToken: '<?php echo e(csrf_token()); ?>'
        };

        // Global cart defaults (overridden by per-page scripts)
        window.__CART_COUNT__ = window.__CART_COUNT__ || 0;
        window.__CART_SUBTOTAL__ = window.__CART_SUBTOTAL__ || 0;
        window.__CURRENCY_SYMBOL__ = <?php echo json_encode($sessionCurrency, 15, 512) ?>;
        window.__CURRENCY__ = <?php echo json_encode($sessionCurrencyCode, 15, 512) ?>;
        window.__COUNTRY__ = <?php echo json_encode($sessionCountry, 15, 512) ?>;

        // Format currency helper
        window.formatCurrency = function(amount, symbol) {
            symbol = symbol || window.__CURRENCY_SYMBOL__;
            return new Intl.NumberFormat('ar-SA', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(amount) + ' ' + symbol;
        };
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>

    
    <script>
        (function() {
            // Show loading bar on any internal link click
            document.addEventListener('click', function(e) {
                const link = e.target.closest('a[href]');
                if (!link) return;
                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('mailto:') ||
                    href.startsWith('tel:') || link.target === '_blank' ||
                    link.hasAttribute('download') || e.ctrlKey || e.metaKey || e.shiftKey) return;
                // Only flag internal same-origin links
                if (link.origin === window.location.origin) {
                    document.documentElement.classList.add('is-loading');
                }
            });
            // Also on form submit
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (form.method && form.method.toLowerCase() === 'get') return;
                document.documentElement.classList.add('is-loading');
            });
            // Reset on pageshow (back/forward navigation)
            window.addEventListener('pageshow', function() {
                document.documentElement.classList.remove('is-loading');
            });
            // Reset on load (in case of bfcache)
            window.addEventListener('load', function() {
                document.documentElement.classList.remove('is-loading');
            });
        })();
    </script>
</body>
</html>
<?php /**PATH C:\Users\amarn\ecommerce\resources\views/frontend/layout.blade.php ENDPATH**/ ?>