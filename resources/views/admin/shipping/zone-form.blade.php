@extends('admin.layout')

@section('title', $zone ? 'تعديل ' . $zone->name : 'إضافة منطقة شحن')

@section('content')
@php
    use Illuminate\Support\Facades\Config;
    $countries = Config::get('ecommerce.countries', []);
    $allCompanies = \App\Models\ShippingCompany::where('status', 'active')->orderBy('name')->get();
@endphp

<!-- Breadcrumb & Header -->
<div class="mb-stack-lg flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <nav aria-label="Breadcrumb" class="flex text-outline font-label-md text-label-md mb-2">
            <ol class="flex items-center space-x-reverse space-x-2">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors">لوحة القيادة</a></li>
                <li><span class="material-symbols-outlined text-[14px]">chevron_left</span></li>
                <li><a href="{{ route('admin.shipping.index', ['tab' => 'zones']) }}" class="hover:text-primary transition-colors">الشحن</a></li>
                <li><span class="material-symbols-outlined text-[14px]">chevron_left</span></li>
                <li class="text-primary font-bold">{{ $zone ? 'تعديل منطقة شحن' : 'إضافة منطقة شحن' }}</li>
            </ol>
        </nav>
        <h2 class="font-headline-md text-headline-md font-bold text-on-surface">{{ $zone ? 'تعديل منطقة شحن' : 'إضافة منطقة شحن' }}</h2>
    </div>
    <a href="{{ route('admin.shipping.index', ['tab' => 'zones']) }}" class="flex items-center gap-2 px-6 py-2 border border-primary text-primary font-label-md rounded-lg hover:bg-primary/5 transition-all active:scale-95">
        <span class="material-symbols-outlined">arrow_back</span>
        رجوع
    </a>
</div>

<!-- Form -->
<form method="POST" action="{{ $zone ? route('admin.shipping.zone.update', $zone) : route('admin.shipping.zone.store') }}" class="max-w-5xl mx-auto">
    @csrf
    @if($zone)@method('PUT')@endif

    <div class="bg-surface-container-lowest shadow-sm rounded-xl overflow-hidden">
        <!-- Card Header -->
        <div class="p-6 border-b border-outline-variant bg-surface-container-low/50">
            <h3 class="font-title-lg text-title-lg font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">public</span>
                بيانات منطقة الشحن الجغرافية
            </h3>
        </div>

        <!-- Card Body -->
        <div class="p-8 space-y-8">

            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-stack-lg">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant block">اسم المنطقة <span class="text-error">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $zone->name ?? '') }}" required
                           placeholder="الرياض والمنطقة الوسطى"
                           class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg p-3 font-body-md focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all @error('name') border-error @enderror">
                    @error('name')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    <p class="text-[11px] text-outline">اسم فريد يساعدك في تمييز هذه المنطقة في لوحة الإدارة.</p>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant block">الأولوية</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $zone->sort_order ?? 0) }}" min="0"
                           class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg p-3 font-body-md focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all">
                    <p class="text-[11px] text-outline">القيم الأقل تأخذ أولوية أعلى عند مطابقة عنوان العميل.</p>
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant block">الوصف</label>
                    <textarea name="description" rows="3" placeholder="منطقة الشحن الرئيسية للرياض والمدن المحيطة..."
                              class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg p-3 font-body-md focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all">{{ old('description', $zone->description ?? '') }}</textarea>
                </div>
            </div>

            <hr class="border-outline-variant/30">

            <!-- Company & Delivery Type -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-stack-lg">
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant block">شركة الشحن</label>
                    <select name="company_id" class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg p-3 font-body-md focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all">
                        <option value="">— بدون شركة محددة (إدارة المتجر) —</option>
                        @foreach($allCompanies as $company)
                            <option value="{{ $company->id }}" {{ (string)old('company_id', $zone->company_id ?? '') === (string)$company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-outline">اتركه فارغاً إذا كنت تتولى الشحن بنفسك أو تريد السماح بأي شركة.</p>
                </div>
                <div class="space-y-2">
                    <label class="font-label-md text-label-md text-on-surface-variant block">نوع التوصيل المدعوم <span class="text-error">*</span></label>
                    <select name="delivery_type" required class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg p-3 font-body-md focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all">
                        @php $dt = old('delivery_type', $zone->delivery_type ?? 'both'); @endphp
                        <option value="home" {{ $dt === 'home' ? 'selected' : '' }}>توصيل للمنزل فقط</option>
                        <option value="office" {{ $dt === 'office' ? 'selected' : '' }}>استلام من مكتب الشركة فقط</option>
                        <option value="both" {{ $dt === 'both' ? 'selected' : '' }}>كلاهما (منزل أو مكتب)</option>
                    </select>
                </div>
            </div>

            <hr class="border-outline-variant/30">

            <!-- Geographic Selection -->
            <div class="space-y-6">
                <!-- Countries -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="font-label-md text-label-md text-on-surface-variant block">الدول المشمولة <span class="text-error">*</span></label>
                        <span class="text-[11px] text-primary cursor-pointer hover:underline">تحديد الكل</span>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-outline-variant cursor-pointer transition-all hover:bg-surface-container-high {{ in_array('*', old('countries', $zone->countries ?? [])) ? 'border-primary bg-primary/5' : '' }}">
                            <input type="checkbox" name="countries[]" value="*" {{ in_array('*', old('countries', $zone->countries ?? [])) ? 'checked' : '' }} class="w-5 h-5 rounded text-primary border-outline-variant focus:ring-primary transition-all">
                            <span class="flex items-center gap-2 font-label-md text-label-md">
                                <span class="material-symbols-outlined text-primary text-lg">language</span>
                                جميع الدول
                            </span>
                        </label>
                        @foreach($countries as $code => $info)
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-outline-variant cursor-pointer transition-all hover:bg-surface-container-high country-card {{ in_array($code, old('countries', $zone->countries ?? [])) ? 'border-primary bg-primary/5' : '' }}">
                                <input type="checkbox" name="countries[]" value="{{ $code }}" {{ in_array($code, old('countries', $zone->countries ?? [])) ? 'checked' : '' }} class="w-5 h-5 rounded text-primary border-outline-variant focus:ring-primary transition-all country-toggle" data-country="{{ $code }}">
                                <span class="flex items-center gap-2 font-label-md text-label-md">
                                    <span class="text-xl">{{ $info['flag'] ?? '' }}</span>
                                    {{ $info['name'] }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Cities / States -->
                <div id="states-container" class="space-y-3">
                    @php $selectedCountries = old('countries', $zone->countries ?? []); @endphp
                    @foreach($countries as $code => $info)
                        @if(in_array($code, $selectedCountries) || (in_array('*', $selectedCountries)))
                            <div class="country-states border border-outline-variant rounded-xl p-4 bg-surface-container-low/30 {{ in_array('*', $selectedCountries) && !in_array($code, $selectedCountries) ? 'hidden' : '' }}" data-country="{{ $code }}">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="font-label-md text-label-md font-bold text-on-surface">{{ $info['name'] }}</span>
                                    <span class="text-xs text-outline">({{ $code }})</span>
                                </div>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-y-3 gap-x-6 max-h-48 overflow-y-auto p-3 bg-surface-container-low rounded-xl border border-outline-variant/50">
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" name="cities[{{ $code }}][]" value="*" {{ in_array('*', old("cities.$code", $zone->cities[$code] ?? [])) ? 'checked' : '' }} class="w-5 h-5 rounded text-primary border-outline focus:ring-primary transition-all">
                                        <span class="font-body-sm text-body-sm font-semibold text-primary group-hover:text-primary">كل المدن</span>
                                    </label>
                                    @foreach($info['states'] ?? [] as $stateCode => $stateName)
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" name="cities[{{ $code }}][]" value="{{ $stateName }}" {{ in_array($stateName, old("cities.$code", $zone->cities[$code] ?? [])) ? 'checked' : '' }} class="w-5 h-5 rounded text-primary border-outline focus:ring-primary transition-all">
                                            <span class="font-body-sm text-body-sm group-hover:text-primary {{ !in_array($stateName, old("cities.$code", $zone->cities[$code] ?? [])) ? 'text-outline' : '' }}">{{ $stateName }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <p class="text-[11px] text-outline flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">info</span>
                    إذا لم تختر أي مدن، سيتم تطبيق المنطقة على كل مدن الدولة المختارة.
                </p>
            </div>

            <hr class="border-outline-variant/30">

            <!-- Shipping Costs -->
            <div class="space-y-6">
                <h3 class="font-title-lg text-title-lg font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">payments</span>
                    تكلفة الشحن
                </h3>
                <p class="text-[11px] text-outline flex items-center gap-2 p-3 bg-primary-fixed/10 border border-outline-variant rounded-lg">
                    <span class="material-symbols-outlined text-primary text-[14px]">info</span>
                    اترك الحقول فارغة لاستخدام "التكلفة العامة" (القياسية). الحقول الخاصة بنوع التوصيل تأخذ الأولوية عند توفرها.
                </p>

                <!-- General costs -->
                <div class="space-y-3">
                    <h4 class="font-label-md text-label-md font-bold text-on-surface">التكلفة العامة (تستخدم كـ fallback)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-stack-lg">
                        <div class="space-y-2">
                            <label class="font-label-md text-label-md text-on-surface-variant block">الشحن العادي</label>
                            <div class="relative">
                                <input type="number" name="cost" value="{{ old('cost', $zone->cost ?? 0) }}" min="0" step="0.01"
                                       class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg p-3 font-body-md focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all pl-12">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-outline text-sm">{{ currentCurrencySymbol() }}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="font-label-md text-label-md text-on-surface-variant block">الشحن السريع</label>
                            <div class="relative">
                                <input type="number" name="express_cost" value="{{ old('express_cost', $zone->express_cost ?? '') }}" min="0" step="0.01"
                                       class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg p-3 font-body-md focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all pl-12">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-outline text-sm">{{ currentCurrencySymbol() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Home delivery -->
                <div class="p-4 bg-tertiary-fixed/10 rounded-xl border border-tertiary-fixed-dim space-y-3">
                    <h4 class="font-label-md text-label-md font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">home</span>
                        توصيل للمنزل
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-stack-lg">
                        <div class="space-y-2">
                            <label class="font-label-md text-label-md text-on-surface-variant block">عادي (تكلفة خاصة)</label>
                            <div class="relative">
                                <input type="number" name="home_cost" value="{{ old('home_cost', $zone->home_cost ?? '') }}" min="0" step="0.01" placeholder="اتركه فارغ لاستخدام التكلفة العامة"
                                       class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg p-3 font-body-md focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all pl-12">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-outline text-sm">{{ currentCurrencySymbol() }}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="font-label-md text-label-md text-on-surface-variant block">سريع (تكلفة خاصة)</label>
                            <div class="relative">
                                <input type="number" name="home_express_cost" value="{{ old('home_express_cost', $zone->home_express_cost ?? '') }}" min="0" step="0.01" placeholder="اتركه فارغ لاستخدام التكلفة العامة"
                                       class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg p-3 font-body-md focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all pl-12">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-outline text-sm">{{ currentCurrencySymbol() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Office pickup -->
                <div class="p-4 bg-secondary-fixed/30 rounded-xl border border-secondary-fixed-dim space-y-3">
                    <h4 class="font-label-md text-label-md font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">business</span>
                        استلام من مكتب الشركة
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-stack-lg">
                        <div class="space-y-2">
                            <label class="font-label-md text-label-md text-on-surface-variant block">عادي (تكلفة خاصة)</label>
                            <div class="relative">
                                <input type="number" name="office_cost" value="{{ old('office_cost', $zone->office_cost ?? '') }}" min="0" step="0.01" placeholder="اتركه فارغ لاستخدام التكلفة العامة"
                                       class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg p-3 font-body-md focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all pl-12">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-outline text-sm">{{ currentCurrencySymbol() }}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="font-label-md text-label-md text-on-surface-variant block">سريع (تكلفة خاصة)</label>
                            <div class="relative">
                                <input type="number" name="office_express_cost" value="{{ old('office_express_cost', $zone->office_express_cost ?? '') }}" min="0" step="0.01" placeholder="اتركه فارغ لاستخدام التكلفة العامة"
                                       class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg p-3 font-body-md focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all pl-12">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-outline text-sm">{{ currentCurrencySymbol() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Weight-based + threshold -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-stack-lg">
                    <div class="space-y-2">
                        <label class="font-label-md text-label-md text-on-surface-variant block">تكلفة إضافية لكل كجم</label>
                        <div class="relative">
                            <input type="number" name="cost_per_kg" value="{{ old('cost_per_kg', $zone->cost_per_kg ?? '') }}" min="0" step="0.01"
                                   class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg p-3 font-body-md focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all pl-16">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-outline text-sm">{{ currentCurrencySymbol() }} / كجم</span>
                        </div>
                        <p class="text-[11px] text-outline">اختياري - للوزن الثقيل</p>
                    </div>
                    <div class="space-y-2">
                        <label class="font-label-md text-label-md text-on-surface-variant block">حد الشحن المجاني</label>
                        <div class="relative">
                            <input type="number" name="free_threshold" value="{{ old('free_threshold', $zone->free_threshold ?? '') }}" min="0" step="0.01"
                                   class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg p-3 font-body-md focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all pl-12">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-outline text-sm">{{ currentCurrencySymbol() }}</span>
                        </div>
                        <p class="text-[11px] text-outline">الطلبات فوقه مجاناً</p>
                    </div>
                </div>
            </div>

            <hr class="border-outline-variant/30">

            <!-- Estimated Delivery Times -->
            <div class="space-y-4">
                <h3 class="font-title-lg text-title-lg font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">schedule</span>
                    وقت التوصيل المتوقع
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-stack-lg">
                    <div class="space-y-2">
                        <label class="font-label-md text-label-md text-on-surface-variant block">الشحن العادي</label>
                        <input type="text" name="estimated_days_standard" value="{{ old('estimated_days_standard', $zone->estimated_days_standard ?? '3-5 أيام') }}"
                               placeholder="مثل: 3-5 أيام"
                               class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg p-3 font-body-md focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="font-label-md text-label-md text-on-surface-variant block">الشحن السريع</label>
                        <input type="text" name="estimated_days_express" value="{{ old('estimated_days_express', $zone->estimated_days_express ?? '1-2 يوم') }}"
                               placeholder="مثل: 1-2 يوم"
                               class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg p-3 font-body-md focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all">
                    </div>
                </div>
            </div>

            <hr class="border-outline-variant/30">

            <!-- Status Toggle -->
            <div class="flex items-center justify-between p-4 bg-tertiary-fixed/20 rounded-xl border border-tertiary-fixed-dim">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-tertiary-fixed flex items-center justify-center text-on-tertiary-fixed-variant">
                        <span class="material-symbols-outlined">toggle_on</span>
                    </div>
                    <div>
                        <h4 class="font-label-md text-label-md font-bold text-on-surface">حالة المنطقة</h4>
                        <p class="text-[11px] text-outline">منطقة نشطة (متاحة للعملاء)</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="status" value="inactive">
                    <input type="checkbox" name="status" value="active" class="sr-only peer" {{ old('status', $zone->status ?? 'active') === 'active' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-outline-variant peer-focus:outline-none rounded-full peer peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:right-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                </label>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row items-center gap-4 pt-6">
                <button type="submit" class="w-full sm:w-auto px-10 py-3 bg-primary text-white font-title-lg rounded-xl shadow-md hover:shadow-lg hover:bg-primary/90 transition-all active:scale-95 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">save</span>
                    {{ $zone ? 'تحديث المنطقة' : 'حفظ المنطقة' }}
                </button>
                @if($zone)
                <button type="button" onclick="alert('سيتم توجيهك لإضافة طرق شحن لهذه المنطقة')" class="w-full sm:w-auto px-8 py-3 border-2 border-primary-fixed-dim text-primary font-title-lg rounded-xl hover:bg-primary-fixed/30 transition-all active:scale-95 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">add_road</span>
                    إضافة طرق شحن
                </button>
                @endif
                <div class="sm:mr-auto flex items-center gap-3">
                    @if($zone)
                    <button type="button" onclick="if(confirm('هل أنت متأكد من حذف هذه المنطقة؟')) document.getElementById('delete-zone-form').submit()" class="text-error font-label-md hover:underline px-4 py-2">
                        حذف المنطقة
                    </button>
                    @endif
                    <a href="{{ route('admin.shipping.index', ['tab' => 'zones']) }}" class="px-6 py-2 border border-outline-variant text-on-surface-variant font-label-md rounded-lg hover:bg-surface-container-high transition-all">
                        إلغاء
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Help Cards -->
    <div class="mt-gutter grid grid-cols-1 md:grid-cols-3 gap-stack-md">
        <div class="p-4 bg-surface-container-high rounded-xl flex items-start gap-3">
            <span class="material-symbols-outlined text-primary">info</span>
            <div class="text-on-surface-variant">
                <p class="font-label-md text-label-md font-bold mb-1">ما هي مناطق الشحن؟</p>
                <p class="text-[11px] leading-relaxed">تساعدك مناطق الشحن على تحديد تكاليف ومدة مختلفة لكل موقع جغرافي حسب سياسة متجرك.</p>
            </div>
        </div>
        <div class="p-4 bg-surface-container-high rounded-xl flex items-start gap-3">
            <span class="material-symbols-outlined text-primary">psychology</span>
            <div class="text-on-surface-variant">
                <p class="font-label-md text-label-md font-bold mb-1">كيف تعمل الأولوية؟</p>
                <p class="text-[11px] leading-relaxed">إذا كان عنوان العميل ينتمي لمنطقتين، سيتم اختيار المنطقة ذات الرقم الأقل في خانة الأولوية.</p>
            </div>
        </div>
        <div class="p-4 bg-surface-container-high rounded-xl flex items-start gap-3">
            <span class="material-symbols-outlined text-primary">security</span>
            <div class="text-on-surface-variant">
                <p class="font-label-md text-label-md font-bold mb-1">خصوصية البيانات</p>
                <p class="text-[11px] leading-relaxed">يتم تأمين كافة عناوين الشحن والبيانات الجغرافية وفق أعلى معايير التشفير الرقمي.</p>
            </div>
        </div>
    </div>
</form>

@if($zone)
<form method="POST" action="{{ route('admin.shipping.zone.destroy', $zone) }}" id="delete-zone-form" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const countryToggles = document.querySelectorAll('.country-toggle');
    const allToggle = document.querySelector('input[name="countries[]"][value="*"]');

    function syncStatesVisibility() {
        const selected = Array.from(countryToggles).filter(c => c.checked).map(c => c.dataset.country);
        const showAll = allToggle && allToggle.checked;

        document.querySelectorAll('.country-states').forEach(section => {
            const code = section.dataset.country;
            const shouldShow = showAll || selected.includes(code);
            section.classList.toggle('hidden', !shouldShow);
        });
    }

    countryToggles.forEach(t => t.addEventListener('change', syncStatesVisibility));
    if (allToggle) allToggle.addEventListener('change', syncStatesVisibility);

    document.querySelectorAll('.country-card input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const parent = this.closest('.country-card');
            if (parent) {
                if (this.checked) {
                    parent.classList.add('border-primary', 'bg-primary/5');
                    parent.classList.remove('border-outline-variant');
                } else {
                    parent.classList.remove('border-primary', 'bg-primary/5');
                    parent.classList.add('border-outline-variant');
                }
            }
        });
    });
});
</script>
@endpush
