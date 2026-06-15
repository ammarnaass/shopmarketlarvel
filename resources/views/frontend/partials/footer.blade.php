@php
    $countries = config('ecommerce.countries', []);
    $defaultCountry = $countries[config('ecommerce.shipping.default_country', 'SD')] ?? null;
    $countryName = $defaultCountry['name'] ?? 'السودان';
@endphp

{{-- Newsletter section --}}
@if(site('show_newsletter', '1') === '1')
<section class="bg-gradient-to-l from-brand-600 to-accent-500 text-white">
    <div class="container-app py-12">
        <div class="grid md:grid-cols-2 gap-8 items-center">
            <div>
                <h3 class="text-2xl md:text-3xl font-extrabold mb-2">اشترك في النشرة البريدية</h3>
                <p class="text-white/90">احصل على آخر العروض والخصومات الحصرية مباشرة في بريدك</p>
            </div>
            <form class="flex gap-2" onsubmit="event.preventDefault(); showToast('شكراً لاشتراكك! سنتواصل معك قريباً', 'success');">
                <input type="email" required placeholder="بريدك الإلكتروني"
                       class="form-input bg-white text-gray-800 border-0 flex-1 h-12">
                <button type="submit" class="btn-accent btn-lg whitespace-nowrap">
                    <span class="material-symbols-outlined">send</span> اشتراك
                </button>
            </form>
        </div>
    </div>
</section>
@endif

{{-- Main footer --}}
<footer class="bg-gray-900 text-gray-300">
    <div class="container-app py-12 lg:py-16">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
            {{-- Brand --}}
            <div class="col-span-2 lg:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    @if(site('store_logo'))
                        <img src="{{ site('store_logo') }}" alt="{{ site('store_name') }}" class="h-12 w-auto object-contain bg-white rounded-xl p-1">
                    @else
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-brand-500 to-accent-500 flex items-center justify-center text-white shadow-lg">
                            <span class="material-symbols-outlined text-xl">storefront</span>
                        </div>
                    @endif
                    <div>
                        <p class="font-extrabold text-xl text-white">{{ site('store_name', config('app.name')) }}</p>
                        <p class="text-xs text-gray-400">{{ site('store_description', 'متجرك المفضل') }}</p>
                    </div>
                </div>
                <p class="text-sm leading-relaxed mb-6 max-w-md">
                    {{ site('footer_about', 'متجر إلكتروني متكامل يوفر لك تجربة تسوق فريدة مع شحن سريع ودفع آمن عند الاستلام في 6 دول عربية.') }}
                </p>
                <div class="flex gap-2">
                    @if(site('facebook_url'))
                        <a href="{{ site('facebook_url') }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-xl bg-gray-800 hover:bg-brand-600 flex items-center justify-center transition" title="Facebook">
                            <span class="material-symbols-outlined">facebook</span>
                        </a>
                    @endif
                    @if(site('twitter_url'))
                        <a href="{{ site('twitter_url') }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-xl bg-gray-800 hover:bg-brand-400 flex items-center justify-center transition" title="Twitter">
                            <span class="material-symbols-outlined">x</span>
                        </a>
                    @endif
                    @if(site('instagram_url'))
                        <a href="{{ site('instagram_url') }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-xl bg-gray-800 hover:bg-gradient-to-br hover:from-pink-500 hover:to-purple-500 flex items-center justify-center transition" title="Instagram">
                            <span class="material-symbols-outlined">photo_camera</span>
                        </a>
                    @endif
                    @if(site('whatsapp_number'))
                        @php
                            $wa = preg_replace('/[^0-9]/', '', site('whatsapp_number'));
                            $wa = ltrim($wa, '0');
                            // If number has no country code (len < 12), assume Algeria +213
                            if(strlen($wa) < 12) $wa = '213' . $wa;
                        @endphp
                        <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-xl bg-gray-800 hover:bg-green-600 flex items-center justify-center transition" title="WhatsApp">
                            <span class="material-symbols-outlined">whatsapp</span>
                        </a>
                    @endif
                    @if(site('youtube_url'))
                        <a href="{{ site('youtube_url') }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-xl bg-gray-800 hover:bg-red-600 flex items-center justify-center transition" title="YouTube">
                            <span class="material-symbols-outlined">play_circle</span>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Quick links --}}
            <div>
                <h3 class="font-bold text-white mb-4 text-base">روابط سريعة</h3>
                <ul class="space-y-2.5 text-sm">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-accent-400 transition flex items-center gap-2">
                            <span class="material-symbols-outlined text-[10px] text-gray-500">chevron_right</span> الرئيسية
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shop.index') }}" class="hover:text-accent-400 transition flex items-center gap-2">
                            <span class="material-symbols-outlined text-[10px] text-gray-500">chevron_right</span> جميع المنتجات
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('cart.index') }}" class="hover:text-accent-400 transition flex items-center gap-2">
                            <span class="material-symbols-outlined text-[10px] text-gray-500">chevron_right</span> سلة التسوق
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('orders.index') }}" class="hover:text-accent-400 transition flex items-center gap-2">
                            <span class="material-symbols-outlined text-[10px] text-gray-500">chevron_right</span> طلباتي
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('wishlist.index') }}" class="hover:text-accent-400 transition flex items-center gap-2">
                            <span class="material-symbols-outlined text-[10px] text-gray-500">chevron_right</span> المفضلة
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Customer service --}}
            <div>
                <h3 class="font-bold text-white mb-4 text-base">خدمة العملاء</h3>
                <ul class="space-y-2.5 text-sm">
                    <li>
                        <a href="{{ route('page.show', 'return-policy') }}" class="hover:text-accent-400 transition flex items-center gap-2">
                            <span class="material-symbols-outlined text-[10px] text-gray-500">chevron_right</span> سياسة الإرجاع
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('page.show', 'shipping') }}" class="hover:text-accent-400 transition flex items-center gap-2">
                            <span class="material-symbols-outlined text-[10px] text-gray-500">chevron_right</span> الشحن والتوصيل
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('page.show', 'faq') }}" class="hover:text-accent-400 transition flex items-center gap-2">
                            <span class="material-symbols-outlined text-[10px] text-gray-500">chevron_right</span> الأسئلة الشائعة
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('page.show', 'privacy') }}" class="hover:text-accent-400 transition flex items-center gap-2">
                            <span class="material-symbols-outlined text-[10px] text-gray-500">chevron_right</span> سياسة الخصوصية
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('page.show', 'terms') }}" class="hover:text-accent-400 transition flex items-center gap-2">
                            <span class="material-symbols-outlined text-[10px] text-gray-500">chevron_right</span> الشروط والأحكام
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h3 class="font-bold text-white mb-4 text-base">تواصل معنا</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-accent-400 mt-1">phone</span>
                        <div>
                            <p class="text-gray-400 text-xs">الدعم الفني</p>
                            <a href="tel:{{ site('contact_phone', '+249900000000') }}" class="hover:text-accent-400 transition" dir="ltr">{{ site('contact_phone', '+249 90 000 0000') }}</a>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-accent-400 mt-1">mail</span>
                        <div>
                            <p class="text-gray-400 text-xs">البريد الإلكتروني</p>
                            <a href="mailto:{{ site('contact_email', 'info@amarstore.com') }}" class="hover:text-accent-400 transition">{{ site('contact_email', 'info@amarstore.com') }}</a>
                        </div>
                    </li>
                    @if(site('contact_whatsapp'))
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-accent-400 mt-1">whatsapp</span>
                            <div>
                                <p class="text-gray-400 text-xs">واتساب</p>
                                @php
                                    $cwa = preg_replace('/[^0-9]/', '', site('contact_whatsapp'));
                                    $cwa = ltrim($cwa, '0');
                                    if(strlen($cwa) < 12) $cwa = '213' . $cwa;
                                @endphp
                                <a href="https://wa.me/{{ $cwa }}" target="_blank" class="hover:text-accent-400 transition" dir="ltr">{{ site('contact_whatsapp') }}</a>
                            </div>
                        </li>
                    @endif
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-accent-400 mt-1">location_on</span>
                        <div>
                            <p class="text-gray-400 text-xs">المقر الرئيسي</p>
                            <p>{{ site('contact_address', $countryName) }}</p>
                        </div>
                    </li>
                    @if(site('contact_hours'))
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-accent-400 mt-1">schedule</span>
                            <div>
                                <p class="text-gray-400 text-xs">ساعات العمل</p>
                                <p>{{ site('contact_hours') }}</p>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        {{-- Trust badges --}}
        <div class="border-t border-gray-800 mt-10 pt-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-10 h-10 rounded-lg bg-gray-800 flex items-center justify-center text-accent-400 flex-shrink-0">
                        <span class="material-symbols-outlined">local_shipping</span>
                    </div>
                    <div>
                        <p class="text-white font-semibold text-xs">شحن مجاني</p>
                        <p class="text-gray-500 text-xs">فوق {{ config('ecommerce.shipping.free_threshold', 500) }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-10 h-10 rounded-lg bg-gray-800 flex items-center justify-center text-accent-400 flex-shrink-0">
                        <span class="material-symbols-outlined">payments</span>
                    </div>
                    <div>
                        <p class="text-white font-semibold text-xs">دفع عند الاستلام</p>
                        <p class="text-gray-500 text-xs">بدون رسوم</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-10 h-10 rounded-lg bg-gray-800 flex items-center justify-center text-accent-400 flex-shrink-0">
                        <span class="material-symbols-outlined">undo</span>
                    </div>
                    <div>
                        <p class="text-white font-semibold text-xs">إرجاع 14 يوم</p>
                        <p class="text-gray-500 text-xs">سياسة مرنة</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-10 h-10 rounded-lg bg-gray-800 flex items-center justify-center text-accent-400 flex-shrink-0">
                        <span class="material-symbols-outlined">headphones</span>
                    </div>
                    <div>
                        <p class="text-white font-semibold text-xs">دعم 24/7</p>
                        <p class="text-gray-500 text-xs">دائماً معك</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="border-t border-gray-800 pt-6 flex flex-col md:flex-row items-center justify-between gap-4 text-sm">
            <p class="text-gray-400">
                © {{ date('Y') }} {{ site('store_name', config('app.name')) }}. {{ site('footer_copyright', 'جميع الحقوق محفوظة') }}.
                <span class="mx-1 text-gray-600">|</span>
                صُنع بـ <span class="material-symbols-outlined text-accent-500">favorite</span> لخدمتك
            </p>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1.5 bg-gray-800 rounded-lg text-xs inline-flex items-center gap-1">
                    <span class="material-symbols-outlined text-accent-400">credit_card</span> الدفع عند الاستلام
                </span>
                <span class="px-3 py-1.5 bg-gray-800 rounded-lg text-xs inline-flex items-center gap-1">
                    <span class="material-symbols-outlined text-green-400">shield</span> دفع آمن
                </span>
            </div>
        </div>
    </div>
</footer>
