@php
    $countries = config('ecommerce.countries', []);
    $defaultCountry = $countries[config('ecommerce.shipping.default_country', 'SD')] ?? null;
    $countryName = $defaultCountry['name'] ?? 'السودان';
@endphp

{{-- Newsletter section --}}
@if(site('show_newsletter', '1') === '1')
<section class="relative overflow-hidden" style="background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 50%, #7c3aed 100%);">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/5 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-white/5 rounded-full blur-2xl"></div>
        <div class="absolute top-0 left-1/4 w-px h-full bg-white/10"></div>
        <div class="absolute top-0 right-1/4 w-px h-full bg-white/5"></div>
        <div class="absolute top-1/2 left-0 w-full h-px bg-white/5"></div>
    </div>
    <div class="container-app py-16 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm px-5 py-2 rounded-full text-sm text-white/90 mb-6 shadow-lg">
                <span class="material-symbols-outlined text-yellow-300" style="font-size:18px">mail</span>
                نشرة إخبارية حصرية
            </div>
            <h3 class="text-3xl md:text-4xl font-extrabold text-white mb-4 leading-tight">اشترك في نشرتنا البريدية</h3>
            <p class="text-white/80 mb-8 text-base md:text-lg max-w-xl mx-auto leading-relaxed">احصل على أحدث العروض والخصومات الحصرية مباشرة في بريدك الإلكتروني، ولا تفوّت أي صفقة!</p>
            <form class="flex flex-col sm:flex-row max-w-lg mx-auto gap-3 sm:gap-0 shadow-2xl rounded-2xl overflow-hidden" 
                  onsubmit="event.preventDefault(); typeof showToast !== 'undefined' ? showToast('شكراً لاشتراكك! سنتواصل معك قريباً', 'success') : alert('شكراً!');">
                <input type="email" required placeholder="أدخل بريدك الإلكتروني..."
                       class="flex-1 min-w-0 px-5 py-4 bg-white text-gray-800 text-sm focus:outline-none border-0 focus:ring-2 focus:ring-inset focus:ring-yellow-400"
                       style="border-radius: 0;">
                <button type="submit" 
                        class="px-8 py-4 font-bold text-sm whitespace-nowrap flex items-center justify-center gap-2 transition-all duration-200 hover:brightness-110 active:scale-95 cursor-pointer text-white"
                        style="background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 0;">
                    <span class="material-symbols-outlined" style="font-size:18px">send</span>
                    اشتراك
                </button>
            </form>
            <p class="text-white/50 text-xs mt-4 flex items-center justify-center gap-3">
                <span>لن نشارك بريدك أبداً</span>
                <span class="w-1 h-1 bg-white/30 rounded-full"></span>
                <span>يمكنك إلغاء الاشتراك في أي وقت</span>
            </p>
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
                <div class="mb-4">
                    @if(site('store_logo'))
                        <img src="{{ site('store_logo') }}" alt="{{ site('store_name') }}" class="h-12 w-auto object-contain max-w-[200px] bg-white rounded-xl p-1">
                    @else
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-brand-500 to-accent-500 flex items-center justify-center text-white shadow-lg">
                                <span class="material-symbols-outlined text-xl">storefront</span>
                            </div>
                            <div>
                                <p class="font-extrabold text-xl text-white">{{ site('store_name', config('app.name')) }}</p>
                                <p class="text-xs text-gray-400">{{ site('store_description', 'متجرك المفضل') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
                <p class="text-sm leading-relaxed mb-6 max-w-md">
                    {{ site('footer_about', 'متجر إلكتروني متكامل يوفر لك تجربة تسوق فريدة مع شحن سريع ودفع آمن عند الاستلام.') }}
                </p>
                <div class="flex gap-2">
                    @if(site('facebook_url'))
                        <a href="{{ site('facebook_url') }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-xl bg-gray-800 hover:bg-brand-600 flex items-center justify-center transition" title="Facebook">
                            <i class="fa-brands fa-facebook-f text-lg text-white"></i>
                        </a>
                    @endif
                    @if(site('twitter_url'))
                        <a href="{{ site('twitter_url') }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-xl bg-gray-800 hover:bg-brand-400 flex items-center justify-center transition" title="Twitter">
                            <i class="fa-brands fa-x-twitter text-lg text-white"></i>
                        </a>
                    @endif
                    @if(site('instagram_url'))
                        <a href="{{ site('instagram_url') }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-xl bg-gray-800 hover:bg-gradient-to-br hover:from-pink-500 hover:to-purple-500 flex items-center justify-center transition" title="Instagram">
                            <i class="fa-brands fa-instagram text-lg text-white"></i>
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
                            <i class="fa-brands fa-whatsapp text-lg text-white"></i>
                        </a>
                    @endif
                    @if(site('youtube_url'))
                        <a href="{{ site('youtube_url') }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-xl bg-gray-800 hover:bg-red-600 flex items-center justify-center transition" title="YouTube">
                            <i class="fa-brands fa-youtube text-lg text-white"></i>
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
                            <i class="fa-brands fa-whatsapp text-accent-400 mt-1.5 text-base"></i>
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
