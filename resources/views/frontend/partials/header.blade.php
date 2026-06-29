@if(site('top_bar_show', '1') === '1')
    @php
        $topBarBg = site('top_bar_bg_color', '#004ac6');
        $topBarColor = site('top_bar_text_color', '#ffffff');
        $topBarText = site('top_bar_text');
        if (empty($topBarText)) {
            $topBarText = __t('topbar.free_shipping', ['amount' => config('ecommerce.shipping.free_threshold', 500), 'symbol' => currentCurrencySymbol()]);
        }
        $topBarLink = site('top_bar_link');
    @endphp
    {{-- Top promo bar --}}
    <div class="text-sm py-3 shadow-md relative z-50 transition-all duration-200 font-medium" style="background: linear-gradient(135deg, {{ $topBarBg }} 0%, {{ $topBarBg }}dd 100%); color: {{ $topBarColor }}">
        <div class="container-app flex items-center justify-between flex-wrap gap-2">
            <div class="flex items-center gap-4 flex-wrap">
                @if($topBarLink)
                    <a href="{{ $topBarLink }}" class="flex items-center gap-1.5 hover:opacity-100 transition-opacity">
                        <span class="material-symbols-outlined text-base">campaign</span>
                        <span class="font-bold">{{ $topBarText }}</span>
                    </a>
                @else
                    <span class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-base animate-pulse">campaign</span>
                        <span class="font-bold">{{ $topBarText }}</span>
                    </span>
                @endif
                <span class="hidden sm:flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">payments</span>
                    <span>{{ __t('topbar.cod') }}</span>
                </span>
            </div>
            <div class="flex items-center gap-4">
                <span class="opacity-35">|</span>
                <a href="{{ route('track') }}" class="hover:opacity-80 transition text-sm" style="color: {{ $topBarColor }}">{{ __t('topbar.track_order') }}</a>
                <span class="opacity-35">|</span>
                <a href="{{ route('page.show', ['slug' => 'faq']) }}" class="hover:opacity-80 transition text-sm" style="color: {{ $topBarColor }}">{{ __t('topbar.help') }}</a>
            </div>
        </div>
    </div>
@endif

{{-- Main header --}}
<header class="sticky top-0 z-40 bg-white border-b border-outline-variant shadow-sm"
        x-data="{
            mobileMenu: false,
            searchOpen: false,
            userMenu: false,
        }">
    <div class="container-app flex justify-between items-center h-16 max-w-full mx-auto">
        <div class="flex items-center gap-6">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                @if(site('store_logo'))
                    <img src="{{ site('store_logo') }}" alt="{{ site('store_name') }}" class="h-8 w-auto object-contain">
                @else
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-600 to-accent-500 flex items-center justify-center text-white">
                        <span class="material-symbols-outlined text-sm">storefront</span>
                    </div>
                    <h1 class="font-headline-sm text-lg font-extrabold text-primary">{{ site('store_name', config('app.name')) }}</h1>
                @endif
            </a>

            {{-- Desktop Navigation --}}
            <nav class="hidden md:flex items-center gap-4">
                @if(site('nav_show_home', '1') === '1')
                    <a class="font-body-md text-sm transition-colors {{ request()->routeIs('home') ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary' }}" href="{{ route('home') }}">{{ __t('nav.home') }}</a>
                @endif
                @if(site('nav_show_products', '1') === '1')
                    <a class="font-body-md text-sm transition-colors {{ request()->routeIs('shop.index') && !request('featured') ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary' }}" href="{{ route('shop.index') }}">{{ __t('nav.products') }}</a>
                @endif
                @if(site('nav_show_categories', '1') === '1')
                    @foreach(($navCategories ?? collect())->take((int)site('nav_categories_limit', 3)) as $cat)
                        <a class="font-body-md text-sm transition-colors {{ request()->is('category/'.$cat->slug) ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary' }}" href="{{ route('shop.category', ['slug' => $cat->slug]) }}">{{ $cat->name }}</a>
                    @endforeach
                @endif
                @foreach(($navPages ?? collect()) as $page)
                    <a class="font-body-md text-sm transition-colors {{ request()->is('page/'.$page->slug) ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary' }}" href="{{ route('page.show', ['slug' => $page->slug]) }}">{{ $page->title }}</a>
                @endforeach
                @if(site('nav_show_contact', '1') === '1')
                    <a class="font-body-md text-sm transition-colors {{ request()->is('page/contact') || request()->is('contact') ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-secondary hover:text-primary' }}" href="{{ route('page.show', ['slug' => 'contact']) }}">{{ __t('nav.contact') }}</a>
                @endif
            </nav>
        </div>

        {{-- Search Bar with live suggestions --}}
        <div class="hidden lg:flex flex-1 max-w-md mx-6 relative"
             x-data="liveSearch('{{ route('shop.index') }}', 2)"
             @click.outside="close()">
            <form action="{{ route('shop.index') }}" method="GET" class="relative w-full">
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline">search</span>
                <input type="text"
                       name="q"
                       x-model="query"
                       @input.debounce.300ms="_search(); show()"
                       @focus="show()"
                       @keydown.escape="close()"
                       placeholder="{{ __t('nav.search_placeholder') }}"
                       class="w-full pr-10 pl-4 py-1.5 bg-surface-container-low border border-outline-variant rounded-full focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                       autocomplete="off">
            </form>

            {{-- Live search results dropdown --}}
            <div x-show="open && (results.length > 0 || loading)"
                 x-transition.opacity
                 @click.outside="close()"
                 class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-soft-xl border border-outline-variant overflow-hidden z-50 max-h-96 overflow-y-auto">
                <div x-show="loading" class="p-4 text-center text-gray-500">
                    <span class="material-symbols-outlined text-2xl text-brand-500 animate-spin">sync</span>
                </div>
                <template x-for="item in results" :key="item.id">
                    <a :href="item.url" class="flex items-center gap-3 p-3 hover:bg-brand-50 transition border-b border-outline-variant last:border-0">
                        <img :src="item.image" :alt="item.name" class="w-10 h-10 rounded-lg object-cover" loading="lazy">
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
        <div class="flex items-center gap-2">
            {{-- Currency Selector --}}
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" type="button"
                        class="material-symbols-outlined text-primary p-2 active:scale-95 transition-transform flex items-center gap-1 text-base hover:bg-gray-100 rounded-full"
                        title="{{ __t('topbar.change_currency') }}">
                    <span class="text-xs font-semibold">{{ countryCurrency(session('selected_country', 'SD')) }}</span>
                </button>
                <div x-show="open" x-transition
                     class="absolute left-0 mt-2 w-44 bg-white border border-outline-variant rounded-lg shadow-lg z-50 max-h-64 overflow-y-auto">
                    @php $countries = config('ecommerce.countries', []); @endphp
                    @foreach($countries as $code => $info)
                        <a href="{{ route('currency.switch', ['code' => $code]) }}"
                           class="flex items-center gap-2 px-3 py-2 hover:bg-gray-50 text-sm {{ session('selected_country', 'SD') === $code ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-700' }}">
                            <span class="text-base font-bold w-8 text-center">{{ $info['currency_symbol'] ?? '' }}</span>
                            <span class="flex-1">{{ $info['name'] ?? $code }}</span>
                            <span class="text-xs text-gray-400">{{ $code }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Language Switcher --}}
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" type="button"
                        class="flex items-center gap-1.5 px-2 py-1.5 text-sm font-semibold rounded-lg hover:bg-gray-100 transition-colors"
                        title="{{ __t('nav.language') }}">
                    @php $currentLang = $languages->firstWhere('code', current_locale()); @endphp
                    <span>{{ $currentLang->flag ?? '' }}</span>
                    <span class="uppercase">{{ current_locale() }}</span>
                    <span class="material-symbols-outlined text-base transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="open" x-transition
                     class="absolute left-0 mt-2 w-44 bg-white border border-outline-variant rounded-xl shadow-lg z-50 overflow-hidden">
                    @foreach($languages ?? collect() as $lang)
                        <a href="{{ route('lang.switch', ['locale' => $lang->code]) }}"
                           class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 text-sm transition-colors {{ current_locale() === $lang->code ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-700' }}">
                            <span class="text-lg">{{ $lang->flag ?? '🏳️' }}</span>
                            <span class="flex-1">{{ $lang->native_name }}</span>
                            <span class="text-xs text-gray-400 uppercase">{{ $lang->code }}</span>
                            @if($lang->is_default)
                                <span class="text-xs bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded-full">{{ __t('common.default') }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Mobile Search Icon --}}
            <button @click="searchOpen = !searchOpen" class="lg:hidden material-symbols-outlined text-primary p-2 hover:bg-gray-100 rounded-full">
                search
            </button>

            {{-- Wishlist --}}
            <a href="{{ route('wishlist.index') }}" class="material-symbols-outlined text-primary p-2 active:scale-95 transition-transform relative hover:bg-gray-100 rounded-full">
                favorite
                <span x-show="$store.wishlist.count > 0"
                      x-text="$store.wishlist.count"
                      x-cloak
                      class="absolute top-1 right-1 bg-accent-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold shadow-md"></span>
            </a>

            {{-- Cart --}}
            <a href="{{ route('cart.index') }}" class="material-symbols-outlined text-primary p-2 active:scale-95 transition-transform relative hover:bg-gray-100 rounded-full">
                shopping_cart
                <span x-show="$store.cart.count > 0"
                      x-text="$store.cart.count"
                      x-cloak
                      class="absolute top-1 right-1 bg-accent-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold shadow-md"></span>
            </a>

            {{-- Profile / User --}}
            @auth
                <div class="relative" @click.outside="userMenu = false">
                    <button @click="userMenu = !userMenu" class="material-symbols-outlined text-primary p-2 active:scale-95 transition-transform hover:bg-gray-100 rounded-full">
                        person
                    </button>
                    <div x-show="userMenu"
                         x-transition.duration.150ms
                         x-cloak
                         class="absolute left-0 mt-2 w-64 bg-white rounded-2xl shadow-soft-xl border border-outline-variant py-2 z-50">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="font-semibold text-sm text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="py-1">
                            <a href="{{ route('account.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-700 transition">
                                <span class="material-symbols-outlined w-4">person</span> {{ __t('nav.my_account') }}
                            </a>
                            <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-700 transition">
                                <span class="material-symbols-outlined w-4">inventory_2</span> {{ __t('nav.my_orders') }}
                            </a>
                            @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-700 transition">
                                    <span class="material-symbols-outlined w-4">dashboard</span> {{ __t('nav.dashboard') }}
                                </a>
                            @endif
                        </div>
                        <div class="border-t border-gray-100 pt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 w-full text-right px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                    <span class="material-symbols-outlined w-4">logout</span> {{ __t('nav.logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="material-symbols-outlined text-primary p-2 active:scale-95 transition-transform hover:bg-gray-100 rounded-full" title="{{ __t('nav.my_account') }}">
                    person
                </a>
            @endauth
        </div>
    </div>

    {{-- Mobile search bar --}}
    <div x-show="searchOpen"
         x-collapse
         x-cloak
         class="lg:hidden border-t border-outline-variant bg-white">
        <div class="container-app py-3">
            <form action="{{ route('shop.index') }}" method="GET" class="relative">
                <input type="text" name="q" placeholder="{{ __t('nav.search_placeholder') }}" class="w-full pr-10 pl-4 py-2 bg-surface-container-low border border-outline-variant rounded-full text-sm">
                <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 right-4 text-gray-400">search</span>
                <button type="submit" class="absolute left-1 top-1 bottom-1 px-4 btn-primary rounded-full text-xs">{{ __t('nav.search_submit') }}</button>
            </form>
        </div>
    </div>
</header>
