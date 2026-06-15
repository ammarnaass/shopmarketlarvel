<!DOCTYPE html>
<html lang="ar" dir="rtl" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-surface text-on-surface min-h-screen">
    {{-- Sidebar --}}
    <aside class="fixed right-0 top-0 h-full w-[260px] bg-on-background z-50 flex flex-col py-6 overflow-y-auto">
        <div class="px-6 mb-8 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-primary-container flex items-center justify-center text-on-primary">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">storefront</span>
            </div>
            <div>
                <h1 class="text-lg font-bold text-surface-container-lowest leading-tight">{{ config('app.name') }}</h1>
                <p class="text-xs text-surface-variant/60">إدارة النظام</p>
            </div>
        </div>

        <nav class="flex-1 space-y-1 px-3">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-150 active:scale-95 {{ request()->routeIs('admin.dashboard') ? 'active' : 'sidebar-link' }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="font-medium text-sm">لوحة القيادة</span>
            </a>

            <div class="pt-4 pb-1 px-4 text-xs text-surface-variant/50 font-semibold">المبيعات</div>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-150 active:scale-95 {{ request()->routeIs('admin.orders*') ? 'active' : 'sidebar-link' }}">
                <span class="material-symbols-outlined">shopping_cart</span>
                <span class="font-medium text-sm">الطلبات</span>
                @if(($stats['pending_orders'] ?? 0) > 0)
                    <span class="mr-auto bg-error text-on-error text-xs px-1.5 py-0.5 rounded-full">{{ $stats['pending_orders'] }}</span>
                @endif
            </a>
            <a href="#" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-150 active:scale-95 sidebar-link">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">bolt</span>
                <span class="font-medium text-sm">طلبات فورية</span>
                @if(($stats['instant_buy_orders'] ?? 0) > 0)
                    <span class="mr-auto bg-tertiary-container text-on-tertiary-container text-xs px-1.5 py-0.5 rounded-full">{{ $stats['instant_buy_orders'] }}</span>
                @endif
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-150 active:scale-95 {{ request()->routeIs('admin.coupons*') ? 'active' : 'sidebar-link' }}">
                <span class="material-symbols-outlined">confirmation_number</span>
                <span class="font-medium text-sm">الكوبونات</span>
            </a>

            <div class="pt-4 pb-1 px-4 text-xs text-surface-variant/50 font-semibold">الكتالوج</div>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-150 active:scale-95 {{ request()->routeIs('admin.products*') ? 'active' : 'sidebar-link' }}">
                <span class="material-symbols-outlined">inventory_2</span>
                <span class="font-medium text-sm">المنتجات</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-150 active:scale-95 {{ request()->routeIs('admin.categories*') ? 'active' : 'sidebar-link' }}">
                <span class="material-symbols-outlined">category</span>
                <span class="font-medium text-sm">التصنيفات</span>
            </a>
            <a href="{{ route('admin.tags.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-150 active:scale-95 {{ request()->routeIs('admin.tags*') ? 'active' : 'sidebar-link' }}">
                <span class="material-symbols-outlined">label</span>
                <span class="font-medium text-sm">الوسوم</span>
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-150 active:scale-95 {{ request()->routeIs('admin.reviews*') ? 'active' : 'sidebar-link' }}">
                <span class="material-symbols-outlined">star</span>
                <span class="font-medium text-sm">التقييمات</span>
            </a>

            <div class="pt-4 pb-1 px-4 text-xs text-surface-variant/50 font-semibold">العملاء</div>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-150 active:scale-95 {{ request()->routeIs('admin.users*') ? 'active' : 'sidebar-link' }}">
                <span class="material-symbols-outlined">group</span>
                <span class="font-medium text-sm">قائمة العملاء</span>
            </a>

            <div class="pt-4 pb-1 px-4 text-xs text-surface-variant/50 font-semibold">العمليات</div>
            <a href="{{ route('admin.shipping.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-150 active:scale-95 {{ request()->routeIs('admin.shipping*') ? 'active' : 'sidebar-link' }}">
                <span class="material-symbols-outlined">local_shipping</span>
                <span class="font-medium text-sm">الشحن</span>
            </a>
            <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-150 active:scale-95 {{ request()->routeIs('admin.payments*') ? 'active' : 'sidebar-link' }}">
                <span class="material-symbols-outlined">credit_card</span>
                <span class="font-medium text-sm">المدفوعات</span>
            </a>

            <div class="pt-4 pb-1 px-4 text-xs text-surface-variant/50 font-semibold">النظام</div>
            <a href="{{ route('admin.pages.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-150 active:scale-95 {{ request()->routeIs('admin.pages*') ? 'active' : 'sidebar-link' }}">
                <span class="material-symbols-outlined">description</span>
                <span class="font-medium text-sm">الصفحات</span>
            </a>
            <a href="{{ route('admin.currencies.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-150 active:scale-95 {{ request()->routeIs('admin.currencies*') ? 'active' : 'sidebar-link' }}">
                <span class="material-symbols-outlined">payments</span>
                <span class="font-medium text-sm">العملات</span>
            </a>
            <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-150 active:scale-95 {{ request()->routeIs('admin.reports*') ? 'active' : 'sidebar-link' }}">
                <span class="material-symbols-outlined">bar_chart</span>
                <span class="font-medium text-sm">التقارير</span>
            </a>
            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-150 active:scale-95 {{ request()->routeIs('admin.settings*') ? 'active' : 'sidebar-link' }}">
                <span class="material-symbols-outlined">settings</span>
                <span class="font-medium text-sm">الإعدادات</span>
            </a>
            <a href="{{ route('admin.customize.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-150 active:scale-95 {{ request()->routeIs('admin.customize*') ? 'active' : 'sidebar-link' }}">
                <span class="material-symbols-outlined">palette</span>
                <span class="font-medium text-sm">التخصيص</span>
            </a>
        </nav>

        <div class="px-6 py-4 mt-auto border-t border-outline/20">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary">
                    <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">person</span>
                </div>
                <div class="flex-1 overflow-hidden">
                    <p class="text-sm font-medium text-surface-bright truncate">{{ auth()->user()->name ?? 'المدير' }}</p>
                    <p class="text-xs text-surface-variant/70 truncate">مدير المتجر</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="pr-[260px] min-h-screen flex flex-col">
        {{-- Top Nav Bar --}}
        <header class="h-16 flex items-center justify-between px-8 bg-surface border-b border-outline-variant shadow-sm z-40">
            <div class="flex items-center gap-6">
                <h2 class="text-xl font-bold text-primary">@yield('page_title', 'لوحة التحكم')</h2>
            </div>
            <div class="flex items-center gap-4">
                <div class="relative group">
                    <span class="absolute inset-y-0 right-3 flex items-center text-outline group-focus-within:text-primary transition-colors">
                        <span class="material-symbols-outlined">search</span>
                    </span>
                    <input type="text" placeholder="بحث سريع..."
                           class="bg-surface-container-low border border-outline-variant rounded-lg pr-10 pl-4 py-1.5 w-64 text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                </div>
                <button class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded-lg transition-all relative">
                    <span class="material-symbols-outlined">notifications</span>
                    @if(($stats['pending_orders'] ?? 0) > 0)
                        <span class="notif-dot"></span>
                    @endif
                </button>
                <div class="flex items-center gap-2 mr-2 pr-2 border-r border-outline-variant">
                    <a href="{{ route('home') }}" target="_blank" class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded-lg transition-all" title="عرض المتجر">
                        <span class="material-symbols-outlined">open_in_new</span>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-2 text-error hover:bg-error-container/30 rounded-lg transition-all" title="تسجيل الخروج">
                            <span class="material-symbols-outlined">logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Alerts --}}
        <div class="px-8 pt-6">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition.opacity
                     class="alert alert-success mb-4">
                    <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                    <span class="flex-1">{{ session('success') }}</span>
                    <button @click="show = false" class="text-emerald-700 hover:text-emerald-900"><span class="material-symbols-outlined">close</span></button>
                </div>
            @endif
            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)" x-transition.opacity
                     class="alert alert-danger mb-4">
                    <span class="material-symbols-outlined">error</span>
                    <span class="flex-1">{{ session('error') }}</span>
                    <button @click="show = false" class="text-red-700 hover:text-red-900"><span class="material-symbols-outlined">close</span></button>
                </div>
            @endif
            @if(session('warning'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition.opacity
                     class="alert alert-warning mb-4">
                    <span class="material-symbols-outlined">warning</span>
                    <span class="flex-1">{{ session('warning') }}</span>
                    <button @click="show = false" class="text-amber-700 hover:text-amber-900"><span class="material-symbols-outlined">close</span></button>
                </div>
            @endif
        </div>

        {{-- Page Content --}}
        <div class="p-8 space-y-6">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>