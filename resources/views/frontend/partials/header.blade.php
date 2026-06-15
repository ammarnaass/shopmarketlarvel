{{-- Top promo bar --}}
<div class="bg-gradient-to-l from-brand-700 via-brand-600 to-accent-500 text-white text-sm">
    <div class="container-app">
        <div class="flex items-center justify-between h-10 flex-wrap">
            <div class="flex items-center gap-4 flex-wrap">
                <span class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-accent-200 text-lg">local_shipping</span>
                    <span>شحن مجاني فوق {{ config('ecommerce.shipping.free_threshold', 500) }} {{ currentCurrencySymbol()  }}</span>
                </span>
                <span class="hidden sm:flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-accent-200 text-lg">payments</span>
                    <span>الدفع عند الاستلام</span>
                </span>
            </div>
            <div class="hidden md:flex items-center gap-4">
                {{-- Theme toggle --}}
                <button @click="$store.theme.toggle()"
                        class="hover:text-accent-200 transition"
                        :title="$store.theme.dark ? 'الوضع النهاري' : 'الوضع الليلي'">
                    <span class="material-symbols-outlined" x-text="$store.theme.dark ? 'light_mode' : 'dark_mode'"></span>
                </button>
                <span class="text-white/30">|</span>
                <a href="{{ route('track') }}" class="hover:text-accent-200 transition">تتبع طلبك</a>
                <span class="text-white/30">|</span>
                <a href="{{ route('page.show', 'faq') }}" class="hover:text-accent-200 transition">المساعدة</a>
            </div>
        </div>
    </div>
</div>

{{-- Main header --}}
<header class="bg-white/95 backdrop-blur-md sticky top-0 z-40 border-b border-gray-100"
        x-data="{
            mobileMenu: $persist(false).as('hdr:mobileMenu'),
            searchOpen: false,
            userMenu: false,
        }">
    <div class="container-app">
        <div class="flex items-center justify-between h-16 lg:h-20 gap-4">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0">
                @if(site('store_logo'))
                    <img src="{{ site('store_logo') }}" alt="{{ site('store_name') }}" class="h-10 lg:h-12 w-auto object-contain">
                @else
                    <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-gradient-to-br from-brand-600 to-accent-500 flex items-center justify-center text-white shadow-brand">
                        <span class="material-symbols-outlined text-base lg:text-lg">storefront</span>
                    </div>
                @endif
                <div class="hidden sm:block">
                    <p class="font-extrabold text-lg lg:text-xl text-gray-900 leading-tight">{{ site('store_name', config('app.name')) }}</p>
                    <p class="text-[10px] text-gray-500 leading-tight">{{ site('store_description', 'متجرك المفضل') }}</p>
                </div>
            </a>

            {{-- Search (Desktop) with live suggestions --}}
            <div class="hidden md:block flex-1 max-w-2xl relative"
                 x-data="liveSearch('{{ route('shop.index') }}', 2)"
                 @click.outside="close()">
                <form action="{{ route('shop.index') }}" method="GET" class="relative">
                    <input type="text"
                           name="q"
                           x-model="query"
                           @input.debounce.300ms="_search(); show()"
                           @focus="show()"
                           @keydown.escape="close()"
                           @keydown.arrow-down.prevent="$event.target.parentElement.querySelector('button[type=submit]').focus()"
                           placeholder="ابحث عن منتجك المفضل..."
                           class="form-input ps-12 h-12 pr-32 rounded-full"
                           autocomplete="off">
                    <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-4 text-gray-400">search</span>
                    <button type="submit" class="absolute left-1 top-1 bottom-1 px-5 btn-primary rounded-full text-sm">
                        بحث
                    </button>
                </form>

                {{-- Live search results dropdown --}}
                <div x-show="open && (results.length > 0 || loading)"
                     x-transition.opacity
                     @click.outside="close()"
                     class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-soft-xl border border-gray-100 overflow-hidden z-50 max-h-96 overflow-y-auto">
                    <div x-show="loading" class="p-4 text-center text-gray-500">
                        <span class="material-symbols-outlined text-2xl text-brand-500 animate-spin">sync</span>
                    </div>
                    <template x-for="item in results" :key="item.id">
                        <a :href="item.url" class="flex items-center gap-3 p-3 hover:bg-brand-50 transition border-b border-gray-50 last:border-0">
                            <img :src="item.image" :alt="item.name" class="w-12 h-12 rounded-lg object-cover" loading="lazy">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate" x-text="item.name"></p>
                                <p class="text-xs text-brand-600 font-bold" x-text="item.price"></p>
                            </div>
                            <span class="material-symbols-outlined text-gray-300">chevron_right</span>
                        </a>
                    </template>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-1 sm:gap-2 flex-shrink-0">
                {{-- Currency Switcher --}}
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" type="button"
                            class="btn-icon text-gray-600 hover:text-brand-600 flex items-center gap-1"
                            title="تغيير العملة">
                        <span class="material-symbols-outlined text-lg">payments</span>
                        <span class="text-xs font-semibold hidden lg:inline">{{ countryCurrency(session('selected_country', 'SD')) }}</span>
                        <span class="material-symbols-outlined text-[10px]">expand_more</span>
                    </button>
                    <div x-show="open" x-transition
                         class="absolute left-0 mt-2 w-44 bg-white border border-gray-200 rounded-lg shadow-lg z-50 max-h-64 overflow-y-auto">
                        @php $countries = config('ecommerce.countries', []); @endphp
                        @foreach($countries as $code => $info)
                            <a href="{{ route('currency.switch', $code) }}"
                               class="flex items-center gap-2 px-3 py-2 hover:bg-gray-50 text-sm {{ session('selected_country', 'SD') === $code ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-700' }}">
                                <span class="text-base font-bold w-8 text-center">{{ $info['currency_symbol'] ?? '' }}</span>
                                <span class="flex-1">{{ $info['name'] ?? $code }}</span>
                                <span class="text-xs text-gray-400">{{ $code }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Search (Mobile) --}}
                <button @click="searchOpen = !searchOpen" class="btn-icon text-gray-600 hover:text-brand-600 md:hidden">
                    <span class="material-symbols-outlined text-lg">search</span>
                </button>

                {{-- Wishlist (with reactive count from $store.wishlist) --}}
                <a href="{{ route('wishlist.index') }}" class="btn-icon text-gray-600 hover:text-accent-500 relative hidden sm:inline-flex">
                    <span class="material-symbols-outlined text-lg">favorite</span>
                    <span x-show="$store.wishlist.count > 0"
                          x-text="$store.wishlist.count"
                          x-cloak
                          class="absolute -top-1 -right-1 bg-accent-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold shadow-md"></span>
                </a>

                {{-- Cart (reactive count from $store.cart) --}}
                <a href="{{ route('cart.index') }}" class="btn-icon text-gray-600 hover:text-brand-600 relative">
                    <span class="material-symbols-outlined text-lg">shopping_cart</span>
                    <span x-show="$store.cart.count > 0"
                          x-text="$store.cart.count"
                          x-cloak
                          class="absolute -top-1 -right-1 bg-accent-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold shadow-md"></span>
                </a>

                {{-- User --}}
                @auth
                    <div class="relative" @click.outside="userMenu = false">
                        <button @click="userMenu = !userMenu" class="flex items-center gap-2 p-2 rounded-xl hover:bg-gray-100 transition">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-500 to-brand-600 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                                {{ mb_substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span class="material-symbols-outlined text-xs text-gray-400 hidden md:block transition-transform"
                               :class="{ 'rotate-180': userMenu }">expand_more</span>
                        </button>
                        <div x-show="userMenu"
                             x-transition.duration.150ms
                             x-cloak
                             class="absolute left-0 mt-2 w-64 bg-white rounded-2xl shadow-soft-xl border border-gray-100 py-2 z-50">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="font-semibold text-sm text-gray-800">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('account.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-700 transition">
                                    <span class="material-symbols-outlined w-4">person</span> حسابي
                                </a>
                                <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-700 transition">
                                    <span class="material-symbols-outlined w-4">inventory_2</span> طلباتي
                                </a>
                                <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-700 transition sm:hidden">
                                    <span class="material-symbols-outlined w-4">favorite</span> المفضلة
                                </a>
                                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-700 transition">
                                        <span class="material-symbols-outlined w-4">dashboard</span> لوحة التحكم
                                    </a>
                                @endif
                            </div>
                            <div class="border-t border-gray-100 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 w-full text-right px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                        <span class="material-symbols-outlined w-4">logout</span> تسجيل الخروج
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden md:inline-flex btn btn-ghost btn-sm">
                        <span class="material-symbols-outlined">login</span> دخول
                    </a>
                    <a href="{{ route('register') }}" class="btn-primary btn-sm">
                        <span class="material-symbols-outlined">person_add</span> <span class="hidden sm:inline">تسجيل</span>
                    </a>
                @endauth

                {{-- Mobile menu button --}}
                <button @click="mobileMenu = !mobileMenu" class="btn-icon text-gray-600 hover:text-brand-600 md:hidden">
                    <span class="material-symbols-outlined text-lg" x-text="mobileMenu ? 'close' : 'menu'"></span>
                </button>
            </div>
        </div>

        {{-- Categories nav (Desktop) --}}
        <nav class="hidden md:flex items-center gap-1 py-2 border-t border-gray-100 text-sm overflow-x-auto no-scrollbar">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                <span class="material-symbols-outlined ml-1.5">home</span> الرئيسية
            </a>
            <a href="{{ route('shop.index') }}" class="nav-link {{ request()->routeIs('shop.index') ? 'active' : '' }}">
                <span class="material-symbols-outlined ml-1.5">storefront</span> جميع المنتجات
            </a>
            @foreach(($navCategories ?? collect())->take(6) as $cat)
                <a href="{{ route('shop.category', $cat->slug) }}" class="nav-link {{ request()->is('category/'.$cat->slug) ? 'active' : '' }}">
                    <span class="material-symbols-outlined ml-1.5 text-xs opacity-70">{{ $cat->icon ?? 'local_offer' }}</span> {{ $cat->name }}
                </a>
            @endforeach
        </nav>
    </div>

    {{-- Mobile search --}}
    <div x-show="searchOpen"
         x-collapse
         x-cloak
         class="md:hidden border-t border-gray-100 bg-white">
        <div class="container-app py-3">
            <form action="{{ route('shop.index') }}" method="GET" class="relative">
                <input type="text" name="q" placeholder="ابحث عن منتج..." class="form-input ps-12 rounded-full" autofocus>
                <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-4 text-gray-400">search</span>
                <button type="submit" class="absolute left-1 top-1 bottom-1 btn-primary btn-sm rounded-full">بحث</button>
            </form>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div x-show="mobileMenu"
         x-collapse
         x-cloak
         class="md:hidden border-t border-gray-100 bg-white">
        <div class="container-app py-4 space-y-1">
            <a href="{{ route('home') }}" class="block px-4 py-3 rounded-xl text-gray-700 hover:bg-brand-50 hover:text-brand-700 font-medium">
                <span class="material-symbols-outlined ml-2 text-brand-500">home</span> الرئيسية
            </a>
            <a href="{{ route('shop.index') }}" class="block px-4 py-3 rounded-xl text-gray-700 hover:bg-brand-50 hover:text-brand-700 font-medium">
                <span class="material-symbols-outlined ml-2 text-brand-500">storefront</span> جميع المنتجات
            </a>
            @auth
                <a href="{{ route('orders.index') }}" class="block px-4 py-3 rounded-xl text-gray-700 hover:bg-brand-50 hover:text-brand-700">
                    <span class="material-symbols-outlined ml-2 text-brand-500">inventory_2</span> طلباتي
                </a>
                <a href="{{ route('wishlist.index') }}" class="block px-4 py-3 rounded-xl text-gray-700 hover:bg-brand-50 hover:text-brand-700">
                    <span class="material-symbols-outlined ml-2 text-accent-500">favorite</span> المفضلة
                </a>
                <a href="{{ route('account.index') }}" class="block px-4 py-3 rounded-xl text-gray-700 hover:bg-brand-50 hover:text-brand-700">
                    <span class="material-symbols-outlined ml-2 text-brand-500">person</span> حسابي
                </a>
                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-xl text-gray-700 hover:bg-brand-50 hover:text-brand-700">
                        <span class="material-symbols-outlined ml-2 text-brand-500">dashboard</span> لوحة التحكم
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-gray-100 mt-2">
                    @csrf
                    <button type="submit" class="block w-full text-right px-4 py-3 rounded-xl text-red-600 hover:bg-red-50">
                        <span class="material-symbols-outlined ml-2">logout</span> تسجيل الخروج
                    </button>
                </form>
            @else
                <div class="grid grid-cols-2 gap-2 pt-3 border-t border-gray-100 mt-2">
                    <a href="{{ route('login') }}" class="btn btn-secondary">دخول</a>
                    <a href="{{ route('register') }}" class="btn-primary">تسجيل</a>
                </div>
            @endauth
        </div>
    </div>
</header>

@push('styles')
<style>
    .nav-link {
        @apply px-3 py-2 rounded-lg font-medium text-gray-600 hover:bg-brand-50 hover:text-brand-700 transition-all duration-200 whitespace-nowrap;
    }
    .nav-link.active {
        @apply bg-brand-50 text-brand-700;
    }
</style>
@endpush
