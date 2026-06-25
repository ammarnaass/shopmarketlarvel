@extends('frontend.layout')

@section('title', 'إتمام الطلب - ' . site('store_name'))
@section('description', 'إتمام عملية الشراء')

@section('content')
@php
    $countries = config('ecommerce.countries', []);
    $defaultCountry = old('country_code', auth()->user()->country_code ?? config('ecommerce.store.default_country', 'SD'));
    $defaultState = old('state_code', auth()->user()->state_code ?? '');
    $defaultCity = old('city', auth()->user()->defaultAddress?->city);
    $countrySymbol = $countries[$defaultCountry]['currency_symbol'] ?? config('ecommerce.store.currency_symbol');
@endphp

<section class="bg-gradient-to-l from-emerald-600 via-emerald-500 to-teal-500 text-white py-10 md:py-14 relative overflow-hidden">
    <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
    <div class="container-app relative z-10">
        <nav class="flex items-center gap-2 text-sm text-white/80 mb-4">
            <a href="{{ route('cart.index') }}" class="hover:text-white transition flex items-center gap-1">
                <span class="material-symbols-outlined text-xs">shopping_cart</span>
                السلة
            </a>
            <span class="material-symbols-outlined text-[10px] text-white/50">chevron_right</span>
            <span class="text-white font-medium">إتمام الطلب</span>
        </nav>
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl border border-white/30">
                <span class="material-symbols-outlined">assignment_turned_in</span>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold mb-1">إتمام الطلب</h1>
                <p class="text-white/90">راجع بياناتك وأكمل عملية الشراء بأمان</p>
            </div>
        </div>
    </div>
</section>

<div class="container-app py-8 md:py-10">
    @if($errors->any())
        <div class="alert alert-danger mb-5">
            <span class="material-symbols-outlined text-lg">warning</span>
            <div class="flex-1">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    <form action="{{ route('checkout.place') }}" method="POST">
        @csrf
        <div class="grid lg:grid-cols-3 gap-6">

            {{-- ============ LEFT COLUMN ============ --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Address --}}
                <div class="card animate-fade-up">
                    <div class="card-header">
                        <h2 class="font-bold text-lg flex items-center gap-2">
                            <span class="material-symbols-outlined text-brand-600">location_on</span>
                            عنوان التوصيل
                        </h2>
                    </div>
                    <div class="card-body p-5">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">الاسم الكامل <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                                       class="form-input @error('name') form-input-error @enderror">
                                @error('name')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label">رقم الهاتف <span class="text-rose-500">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required
                                       class="form-input @error('phone') form-input-error @enderror">
                                @error('phone')<p class="form-error">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="form-label">الدولة <span class="text-rose-500">*</span></label>
                                <select name="country_code" id="country_code" required
                                        onchange="onCountryChange()"
                                        class="form-input appearance-none @error('country_code') form-input-error @enderror">
                                    @foreach($countries as $code => $info)
                                        <option value="{{ $code }}" {{ $defaultCountry == $code ? 'selected' : '' }}
                                                data-symbol="{{ $info['currency_symbol'] }}">
                                            {{ $info['name'] }} - {{ $info['name_en'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country_code')<p class="form-error">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="form-label">الولاية / المحافظة</label>
                                <select name="state_code" id="state_code" class="form-input appearance-none">
                                    <option value="">— اختر —</option>
                                    @foreach($countries[$defaultCountry]['states'] ?? [] as $code => $name)
                                        <option value="{{ $code }}" {{ $defaultState == $code ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="form-label">المدينة <span class="text-rose-500">*</span></label>
                                <input type="text" name="city" value="{{ $defaultCity }}" required
                                       class="form-input @error('city') form-input-error @enderror"
                                       oninput="calcShipping()">
                                @error('city')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label">المنطقة / الحي</label>
                                <input type="text" name="district" value="{{ old('district') }}" class="form-input">
                            </div>
                            <div class="md:col-span-2">
                                <label class="form-label">العنوان التفصيلي <span class="text-rose-500">*</span></label>
                                <textarea name="address" required rows="2" class="form-input @error('address') form-input-error @enderror">{{ old('address') }}</textarea>
                                @error('address')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label">الرمز البريدي</label>
                                <input type="text" name="zip" value="{{ old('zip') }}" class="form-input">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Shipping Method --}}
                <div class="card animate-fade-up">
                    <div class="card-header">
                        <h2 class="font-bold text-lg flex items-center gap-2">
                            <span class="material-symbols-outlined text-brand-600">local_shipping</span>
                            طريقة الشحن
                        </h2>
                    </div>
                    <div class="card-body p-5 space-y-2">
                        <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition hover:border-brand-400 has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50">
                            <input type="radio" name="shipping_method" value="standard" checked onchange="calcShipping()" class="form-radio">
                            <div class="flex-1">
                                <div class="font-semibold text-gray-800">عادي <span class="text-sm font-normal text-gray-500">(3-5 أيام)</span></div>
                                <div class="text-sm text-gray-500" id="standard-cost">{{ number_format($shippingCost, 0) }} {{ $countrySymbol }}</div>
                            </div>
                            <span class="material-symbols-outlined text-brand-500 text-xl">local_shipping</span>
                        </label>
                        <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition hover:border-brand-400 has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50">
                            <input type="radio" name="shipping_method" value="express" onchange="calcShipping()" class="form-radio">
                            <div class="flex-1">
                                <div class="font-semibold text-gray-800">سريع <span class="text-sm font-normal text-gray-500">(1-2 يوم)</span></div>
                                <div class="text-sm text-gray-500" id="express-cost">-</div>
                            </div>
                            <span class="material-symbols-outlined text-accent-500 text-xl">local_shipping</span>
                        </label>
                    </div>
                </div>

                {{-- Payment --}}
                <div class="card animate-fade-up">
                    <div class="card-header">
                        <h2 class="font-bold text-lg flex items-center gap-2">
                            <span class="material-symbols-outlined text-brand-600">credit_card</span>
                            طريقة الدفع
                        </h2>
                    </div>
                    <div class="card-body p-5 space-y-2">
                        <label class="flex items-center gap-3 p-4 border-2 border-emerald-500 bg-emerald-50 rounded-xl cursor-pointer">
                            <input type="radio" name="payment_method" value="cod" checked class="form-radio text-emerald-600">
                            <div class="flex-1">
                                <div class="font-bold text-gray-800">الدفع عند الاستلام</div>
                                <div class="text-sm text-gray-600">ادفع نقداً عند استلام طلبك بأمان</div>
                            </div>
                            <span class="material-symbols-outlined text-2xl text-emerald-600">payments</span>
                        </label>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="card animate-fade-up">
                    <div class="card-header">
                        <h2 class="font-bold text-lg flex items-center gap-2">
                            <span class="material-symbols-outlined text-brand-600">sticky_note_2</span>
                            ملاحظات (اختياري)
                        </h2>
                    </div>
                    <div class="card-body p-5">
                        <textarea name="notes" rows="3" placeholder="أي ملاحظات خاصة بالطلب (مثل: اتصل قبل التوصيل، اترك عند الباب...)"
                                  class="form-input">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ============ ORDER SUMMARY ============ --}}
            <div class="lg:col-span-1">
                <div class="card sticky top-24 animate-fade-up">
                    <div class="card-header bg-gradient-to-l from-brand-50 to-accent-50">
                        <h3 class="font-bold text-lg flex items-center gap-2">
                            <span class="material-symbols-outlined text-brand-600">receipt</span>
                            ملخص الطلب
                        </h3>
                    </div>
                    <div class="card-body p-5">
                        <div class="space-y-2 mb-4 max-h-64 overflow-y-auto">
                            @forelse($cart->items as $item)
                                <div class="flex items-start gap-2 text-sm">
                                    <span class="bg-brand-100 text-brand-700 rounded-full px-2 py-0.5 text-xs font-bold flex-shrink-0">
                                        {{ $item->quantity }}×
                                    </span>
                                    <span class="flex-1 line-clamp-2 text-gray-700">{{ $item->product->name }}</span>
                                    <span class="font-semibold text-gray-800 flex-shrink-0">{{ number_format($item->subtotal, 0) }}</span>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm text-center py-4">السلة فارغة</p>
                            @endforelse
                        </div>

                        <div class="border-t border-gray-100 pt-4 space-y-2.5 text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>المجموع الفرعي</span>
                                <span class="font-semibold" id="subtotal-display">{{ number_format($subtotal, 0) }} <span id="currency-symbol">{{ $countrySymbol }}</span></span>
                            </div>
                            @if($discount > 0)
                                <div class="flex justify-between text-emerald-600">
                                    <span><span class="material-symbols-outlined text-xs ml-1">local_offer</span>الخصم</span>
                                    <span class="font-semibold">-{{ number_format($discount, 0) }} {{ $countrySymbol }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-gray-600">
                                <span>الشحن</span>
                                <span class="font-semibold" id="shipping-display">{{ number_format($shippingCost, 0) }} <span id="currency-symbol-2">{{ $countrySymbol }}</span></span>
                            </div>
                            @if($codFee > 0)
                                <div class="flex justify-between text-amber-600">
                                    <span>رسوم COD</span>
                                    <span class="font-semibold">{{ number_format($codFee, 0) }} {{ $countrySymbol }}</span>
                                </div>
                            @endif
                            <div class="border-t border-gray-100 pt-3 mt-3 flex justify-between items-baseline">
                                <span class="font-bold text-gray-800 text-base">الإجمالي</span>
                                <span class="font-extrabold text-2xl bg-gradient-to-l from-brand-600 to-accent-500 bg-clip-text text-transparent" id="grand-total">{{ number_format($grandTotal, 0) }} <span id="currency-symbol-3">{{ $countrySymbol }}</span></span>
                            </div>
                        </div>

                        <button type="submit" class="btn-primary btn-block btn-lg mt-5 bg-gradient-to-l from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600">
                            <span class="material-symbols-outlined">shield</span>
                            تأكيد الطلب
                        </button>

                        {{-- Trust badges --}}
                        <div class="grid grid-cols-2 gap-2 pt-4 mt-4 border-t border-gray-100 text-xs text-gray-500">
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-emerald-500">shield</span>
                                <span>دفع آمن</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-brand-500">local_shipping</span>
                                <span>شحن سريع</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-purple-500">undo</span>
                                <span>إرجاع مضمون</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-amber-500">headphones</span>
                                <span>دعم 24/7</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
const baseTotal = {{ $subtotal - $discount }};
const countriesData = @json($countries);
let currentSymbol = '{{ $countrySymbol }}';

function onCountryChange() {
    const countrySel = document.getElementById('country_code');
    const stateSel = document.getElementById('state_code');
    const code = countrySel.value;
    const info = countriesData[code];
    if (!info) return;

    currentSymbol = info.currency_symbol;
    ['currency-symbol', 'currency-symbol-2', 'currency-symbol-3'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.textContent = currentSymbol;
    });

    stateSel.innerHTML = '<option value="">— اختر —</option>';
    if (info.states) {
        for (const [code, name] of Object.entries(info.states)) {
            const opt = document.createElement('option');
            opt.value = code;
            opt.textContent = name;
            stateSel.appendChild(opt);
        }
    }

    calcShipping();
}

function calcShipping() {
    const city = document.querySelector('[name=city]').value;
    const country = document.querySelector('[name=country_code]').value;
    const method = document.querySelector('[name=shipping_method]:checked').value;
    if (!city) return;

    fetch('{{ route("checkout.shipping") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ city, method, country_code: country })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (data.currency_symbol) {
                currentSymbol = data.currency_symbol;
                ['currency-symbol', 'currency-symbol-2', 'currency-symbol-3'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.textContent = currentSymbol;
                });
            }
            const cost = data.is_free ? 0 : Math.round(data.shipping_cost);
            document.getElementById('shipping-display').innerHTML = cost + ' ' + currentSymbol;
            document.getElementById('standard-cost').textContent = data.is_free ? 'شحن مجاني' : (cost + ' ' + currentSymbol);
            updateTotal(cost);
        }
    });
}

function updateTotal(shipping) {
    const codFee = {{ $codFee }};
    const total = baseTotal + shipping + codFee;
    document.getElementById('grand-total').innerHTML = Math.round(total) + ' ' + currentSymbol;
}
</script>
@endsection
