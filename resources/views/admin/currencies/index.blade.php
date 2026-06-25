@extends('admin.layout')

@section('title', 'إدارة العملات')
@section('page_title', 'إدارة العملات')

@section('content')
@php
    $storeCurrency = $storeCurrency ?? 'SDG';
    $storeSymbol = $storeSymbol ?? 'ج.س';
    $defaultCountry = $defaultCountry ?? 'SD';
@endphp

{{-- Breadcrumbs --}}
<div class="mb-6">
    <nav class="flex items-center gap-2 text-outline font-label-sm mb-3">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors">الرئيسية</a>
        <span class="material-symbols-outlined text-[16px]">chevron_left</span>
        <a href="{{ route('admin.settings.index') }}" class="hover:text-primary transition-colors">إعدادات المتجر</a>
        <span class="material-symbols-outlined text-[16px]">chevron_left</span>
        <span class="text-primary font-bold">إدارة العملات</span>
    </nav>
    <div class="flex justify-between items-end">
        <div>
            <h2 class="font-headline-md text-headline-md text-on-surface font-bold">إدارة العملات</h2>
            <p class="text-on-surface-variant font-body-sm mt-1">تكوين العملات المتاحة، أسعار الصرف، وخيارات العرض في متجرك.</p>
        </div>
    </div>
</div>

{{-- Validation Errors --}}
@if($errors->any())
    <div class="mb-6 bg-error-container/20 border border-error/30 text-on-error-container p-4 rounded-lg flex items-start gap-4">
        <span class="material-symbols-outlined text-error">error</span>
        <div>
            <p class="font-semibold mb-1">يرجى تصحيح الأخطاء التالية:</p>
            <ul class="text-sm list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- Main Grid --}}
<div class="grid grid-cols-12 gap-6">
    {{-- Currency Table (8 cols) --}}
    <div class="col-span-12 lg:col-span-8">
        <div class="bg-surface-container-lowest rounded-lg border border-outline-variant shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center">
                <h3 class="font-title-lg text-title-lg text-on-surface">العملات المفعلة</h3>
                <button class="text-primary font-label-md hover:underline flex items-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">sync</span>
                    تحديث أسعار الصرف الآن
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-right">
                    <thead>
                        <tr class="bg-surface-container-low text-on-surface-variant font-label-sm border-b border-outline-variant">
                            <th class="px-6 py-3 font-semibold">العملة</th>
                            <th class="px-6 py-3 font-semibold">الرمز</th>
                            <th class="px-6 py-3 font-semibold">سعر الصرف</th>
                            <th class="px-6 py-3 font-semibold">الحالة</th>
                            <th class="px-6 py-3 font-semibold">التحكم</th>
                        </tr>
                    </thead>
                    <tbody class="font-body-sm">
                        @foreach($currencies as $cur)
                            <tr class="border-b border-outline-variant hover:bg-surface-container transition-colors {{ $cur['code'] === $storeCurrency ? 'bg-primary-fixed/20' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-6 bg-surface-container-high rounded-sm flex items-center justify-center font-bold text-[10px]">{{ $cur['code'] }}</span>
                                        <span class="font-medium {{ $cur['code'] === $storeCurrency ? 'text-primary' : '' }}">
                                            {{ $cur['country_name'] }}
                                            @if($cur['code'] === $storeCurrency)
                                                <span class="text-primary-fixed-dim text-xs mr-1">(الأساسية)</span>
                                            @endif
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-on-surface-variant font-bold">{{ $cur['symbol'] }}</td>
                                <td class="px-6 py-4 font-mono">{{ number_format($cur['rate_to_usd'] ?? 1, 4) }}</td>
                                <td class="px-6 py-4">
                                    @if($cur['code'] === $storeCurrency)
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-[11px] font-bold uppercase">نشط</span>
                                    @else
                                        <span class="px-2 py-1 bg-outline-variant/30 text-outline rounded-full text-[11px] font-bold uppercase">معطل</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <button class="text-primary hover:bg-primary-container/10 p-1 rounded transition-colors" title="تعديل">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </button>
                                        @if($cur['code'] === $storeCurrency)
                                            <button class="text-outline p-1 rounded transition-colors opacity-50 cursor-not-allowed" disabled>
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        @else
                                            <button class="text-error hover:bg-error-container/10 p-1 rounded transition-colors" title="حذف">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Settings Sidebar (4 cols) --}}
    <div class="col-span-12 lg:col-span-4 space-y-6">
        {{-- Manual Settings Card --}}
        <div class="bg-surface-container-lowest rounded-lg border border-outline-variant shadow-sm p-6">
            <div class="flex items-center gap-2 mb-6 text-primary">
                <span class="material-symbols-outlined">settings_suggest</span>
                <h3 class="font-title-lg text-title-lg">إعدادات مخصصة</h3>
            </div>
            <p class="text-on-surface-variant font-body-sm mb-4">أدخل القيم يدوياً (لعملة غير مدرجة في الدول)</p>

            <form method="POST" action="{{ route('admin.currencies.update') }}" class="space-y-4">
                @csrf

                <div class="flex flex-col gap-1.5">
                    <label class="font-label-md text-on-surface">الدولة الافتراضية <span class="text-error">*</span></label>
                    <select name="default_country" required class="bg-surface-container-low border border-outline-variant p-2.5 rounded font-body-sm focus:ring-2 focus:ring-primary outline-none @error('default_country') border-error @enderror">
                        @php
                            $supported = config('ecommerce.countries');
                        @endphp
                        @foreach($supported as $code => $info)
                            <option value="{{ $code }}" {{ old('default_country', $defaultCountry) === $code ? 'selected' : '' }}>
                                {{ $info['name'] }} ({{ $code }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-on-surface-variant mt-1">تستخدم لجلب العملة ورمز الاتصال تلقائياً</p>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="font-label-md text-on-surface">كود العملة (ISO 4217) <span class="text-error">*</span></label>
                    <input type="text" name="currency" required maxlength="3" minlength="3"
                           value="{{ old('currency', $storeCurrency) }}"
                           placeholder="SDG, EGP, USD, EUR..."
                           class="bg-surface-container-low border border-outline-variant p-2.5 rounded font-body-sm focus:ring-2 focus:ring-primary outline-none font-mono uppercase @error('currency') border-error @enderror">
                    <p class="text-xs text-on-surface-variant mt-1">3 أحرف مثل: SDG, EGP, USD</p>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="font-label-md text-on-surface">رمز العملة <span class="text-error">*</span></label>
                    <input type="text" name="currency_symbol" required maxlength="10"
                           value="{{ old('currency_symbol', $storeSymbol) }}"
                           placeholder="ج.س, $, €, £..."
                           class="bg-surface-container-low border border-outline-variant p-2.5 rounded font-body-sm focus:ring-2 focus:ring-primary outline-none @error('currency_symbol') border-error @enderror">
                    <p class="text-xs text-on-surface-variant mt-1">الرمز الذي يظهر بجانب الأسعار</p>
                </div>

                <div class="flex items-start gap-3 p-3 bg-primary-fixed border border-outline-variant/30 rounded-lg">
                    <span class="material-symbols-outlined text-[18px] text-primary mt-0.5">info</span>
                    <p class="text-xs text-on-primary-fixed-variant">
                        سيتم تحديث ملف <code class="bg-surface-container-lowest px-1 rounded font-mono">.env</code> تلقائياً بـ <code class="bg-surface-container-lowest px-1 rounded font-mono">STORE_CURRENCY</code> و <code class="bg-surface-container-lowest px-1 rounded font-mono">STORE_CURRENCY_SYMBOL</code> و <code class="bg-surface-container-lowest px-1 rounded font-mono">STORE_DEFAULT_COUNTRY</code> ومسح الكاش.
                    </p>
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-on-primary-fixed-variant text-white font-label-md py-3 rounded-lg transition-all active:scale-95 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">save</span>
                    حفظ الإعدادات
                </button>
            </form>
        </div>

        {{-- Auto Update Card --}}
        <div class="bg-surface-container-lowest rounded-lg border border-outline-variant shadow-sm p-6 overflow-hidden relative">
            <div class="flex items-center gap-2 mb-4 text-tertiary">
                <span class="material-symbols-outlined">auto_mode</span>
                <h3 class="font-title-lg text-title-lg">تحديث تلقائي</h3>
            </div>
            <p class="text-on-surface-variant font-body-sm mb-4">مزامنة أسعار الصرف تلقائياً كل 24 ساعة لضمان دقة الأسعار العالمية.</p>
            <div class="flex items-center justify-between p-3 bg-tertiary-fixed rounded-lg mb-4">
                <span class="font-label-md text-on-tertiary-fixed font-bold">الحالة: مفعل</span>
                <div class="w-10 h-5 bg-tertiary rounded-full relative cursor-pointer">
                    <div class="absolute left-1 top-1 w-3 h-3 bg-white rounded-full translate-x-5 transition-transform"></div>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex flex-col gap-1">
                    <label class="font-label-sm text-outline">مصدر البيانات (API)</label>
                    <input class="bg-surface-container-highest border border-outline-variant p-2 rounded font-body-sm text-outline" disabled type="text" value="Fixer.io Standard Plan">
                </div>
                <div class="flex items-center gap-2 text-on-surface-variant">
                    <span class="material-symbols-outlined text-[18px]">history</span>
                    <span class="font-label-sm">آخر تحديث: {{ now()->format('Y-m-d h:i A') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Currency Reference Table --}}
<div class="mt-6 bg-surface-container-lowest rounded-lg border border-outline-variant shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-outline-variant">
        <h3 class="font-title-lg text-title-lg text-on-surface flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">table</span>
            جدول العملات المدعومة
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-right">
            <thead>
                <tr class="bg-surface-container-low text-on-surface-variant font-label-sm border-b border-outline-variant">
                    <th class="px-6 py-3 font-semibold">الرمز</th>
                    <th class="px-6 py-3 font-semibold">الكود</th>
                    <th class="px-6 py-3 font-semibold">الدولة</th>
                    <th class="px-6 py-3 font-semibold">رمز الاتصال</th>
                    <th class="px-6 py-3 font-semibold">سعر الصرف (تقريبي)</th>
                    <th class="px-6 py-3 font-semibold">الحالة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($currencies as $cur)
                    <tr class="border-b border-outline-variant hover:bg-surface-container transition-colors {{ $cur['code'] === $storeCurrency ? 'bg-primary-fixed/20' : '' }}">
                        <td class="px-6 py-4">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary to-primary-fixed-dim text-white flex items-center justify-center text-lg font-bold">
                                {{ $cur['symbol'] }}
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono font-bold">{{ $cur['code'] }}</td>
                        <td class="px-6 py-4">{{ $cur['country_name'] }} <span class="text-xs text-on-surface-variant">({{ $cur['country'] }})</span></td>
                        <td class="px-6 py-4 font-mono">{{ $cur['dial_code'] }}</td>
                        <td class="px-6 py-4 text-xs text-on-surface-variant font-mono">1 USD ≈ {{ number_format($cur['rate_to_usd'] ?? 1, 2) }} {{ $cur['code'] }}</td>
                        <td class="px-6 py-4">
                            @if($cur['code'] === $storeCurrency)
                                <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded text-[11px] font-bold flex items-center gap-0.5 w-fit">
                                    <span class="material-symbols-outlined text-[12px]">check</span>الافتراضي
                                </span>
                            @else
                                <span class="bg-outline-variant/30 text-outline px-2 py-0.5 rounded text-[11px]">متاح</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- How It Works --}}
<div class="mt-6 bg-surface-container-lowest rounded-lg border border-outline-variant shadow-sm p-6">
    <h3 class="font-title-lg text-title-lg text-on-surface mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined text-tertiary">help</span>
        كيف يعمل نظام العملات؟
    </h3>
    <div class="grid md:grid-cols-2 gap-4">
        <div class="p-4 bg-primary-fixed border border-outline-variant/30 rounded-lg">
            <h4 class="font-bold text-primary mb-2 flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">store</span>
                العملة الافتراضية
            </h4>
            <p class="text-sm text-on-surface-variant">العملة المعروضة للزوار عند دخولهم المتجر لأول مرة. تُخزَّن في <code class="bg-surface-container-lowest px-1 rounded font-mono">.env</code> كـ <code class="bg-surface-container-lowest px-1 rounded font-mono">STORE_CURRENCY</code>.</p>
        </div>
        <div class="p-4 bg-secondary-fixed border border-outline-variant/30 rounded-lg">
            <h4 class="font-bold text-on-secondary-fixed-variant mb-2 flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">language</span>
                تبديل العملة
            </h4>
            <p class="text-sm text-on-surface-variant">الزوار يمكنهم تبديل العملة من القائمة في الـ header. التغيير يُحفظ في session ويُنعكس فوراً على كل الأسعار.</p>
        </div>
        <div class="p-4 bg-tertiary-fixed border border-outline-variant/30 rounded-lg">
            <h4 class="font-bold text-on-tertiary-fixed-variant mb-2 flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">calculate</span>
                حساب الأسعار
            </h4>
            <p class="text-sm text-on-surface-variant">الأسعار في الـ products تُخزَّن بالـ SDG (الجنيه السوداني). عند تبديل العملة، نعرض السعر بالرمز الجديد دون تحويل رقمي (يستخدم كـ display only).</p>
        </div>
        <div class="p-4 bg-primary-fixed-dim/30 border border-outline-variant/30 rounded-lg">
            <h4 class="font-bold text-on-primary-fixed-variant mb-2 flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">code</span>
                إضافة عملة جديدة
            </h4>
            <p class="text-sm text-on-surface-variant">أضف الدولة في <code class="bg-surface-container-lowest px-1 rounded font-mono">config/ecommerce.php</code> ضمن <code class="bg-surface-container-lowest px-1 rounded font-mono">countries</code>، ثم حدّث العملة من هذه الصفحة.</p>
        </div>
    </div>
</div>

{{-- Notification Alert --}}
<div class="mt-6 bg-secondary-fixed text-on-secondary-container p-4 rounded-lg flex items-center gap-4 border border-outline-variant/30">
    <span class="material-symbols-outlined text-[28px]">info</span>
    <div class="flex-1">
        <h4 class="font-label-md font-bold">إعدادات العملات</h4>
        <p class="font-body-sm">يمكنك تعديل العملة الافتراضية للمتجر من خلال نموذج الإعدادات المخصصة على اليمين. العملة الأساسية محددة باللون الأزرق في الجدول.</p>
    </div>
    <button class="bg-on-secondary-container text-surface-container-lowest px-4 py-1.5 rounded font-label-sm hover:opacity-90 transition-opacity" onclick="this.parentElement.remove()">حسناً</button>
</div>
@endsection
