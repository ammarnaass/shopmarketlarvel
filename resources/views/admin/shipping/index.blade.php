@extends('admin.layout')

@section('title', 'إعدادات الشحن')

@section('content')
@php
    $activeTab = request('tab', 'zones');
@endphp

<!-- Page Header with Breadcrumb -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
    <div>
        <nav aria-label="Breadcrumb" class="flex text-outline text-sm mb-2">
            <ol class="flex items-center space-x-2 space-x-reverse">
                <li><a class="hover:text-primary transition-colors" href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-primary font-bold">إعدادات الشحن</li>
            </ol>
        </nav>
        <h2 class="text-[32px] font-bold leading-10 text-primary">إعدادات الشحن</h2>
    </div>
    <div class="flex gap-3">
        @if($activeTab === 'zones')
            <a href="{{ route('admin.shipping.zone.create') }}" class="bg-primary text-white px-6 py-3 rounded-xl flex items-center gap-2 hover:bg-primary-container transition-all shadow-sm active:scale-95 font-medium">
                <span class="material-symbols-outlined">add_location</span>
                إضافة منطقة شحن جديدة
            </a>
        @elseif($activeTab === 'methods')
            <a href="{{ route('admin.shipping.method.create') }}" class="bg-primary text-white px-6 py-3 rounded-xl flex items-center gap-2 hover:bg-primary-container transition-all shadow-sm active:scale-95 font-medium">
                <span class="material-symbols-outlined">add</span>
                إضافة طريقة شحن
            </a>
        @elseif($activeTab === 'companies')
            <a href="{{ route('admin.shipping.company.create') }}" class="bg-primary text-white px-6 py-3 rounded-xl flex items-center gap-2 hover:bg-primary-container transition-all shadow-sm active:scale-95 font-medium">
                <span class="material-symbols-outlined">add</span>
                إضافة شركة شحن
            </a>
        @elseif($activeTab === 'labels')
            <a href="{{ route('admin.shipping.label.create') }}" class="bg-primary text-white px-6 py-3 rounded-xl flex items-center gap-2 hover:bg-primary-container transition-all shadow-sm active:scale-95 font-medium">
                <span class="material-symbols-outlined">add</span>
                إنشاء بوليصة
            </a>
        @endif
    </div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm p-4 text-center">
        <div class="w-10 h-10 rounded-lg bg-primary-fixed flex items-center justify-center mx-auto mb-2 text-primary">
            <span class="material-symbols-outlined">map</span>
        </div>
        <div class="text-2xl font-bold text-on-surface">{{ $stats['zones_count'] }}</div>
        <div class="text-xs text-on-surface-variant">مناطق الشحن</div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm p-4 text-center">
        <div class="w-10 h-10 rounded-lg bg-secondary-container flex items-center justify-center mx-auto mb-2 text-secondary">
            <span class="material-symbols-outlined">local_shipping</span>
        </div>
        <div class="text-2xl font-bold text-on-surface">{{ $stats['methods_count'] }}</div>
        <div class="text-xs text-on-surface-variant">طرق الشحن</div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm p-4 text-center">
        <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center mx-auto mb-2 text-emerald-700">
            <span class="material-symbols-outlined">business</span>
        </div>
        <div class="text-2xl font-bold text-on-surface">{{ $stats['carriers_count'] }}</div>
        <div class="text-xs text-on-surface-variant">شركات الشحن</div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm p-4 text-center">
        <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center mx-auto mb-2 text-orange-700">
            <span class="material-symbols-outlined">receipt</span>
        </div>
        <div class="text-2xl font-bold text-on-surface">{{ $stats['labels_count'] }}</div>
        <div class="text-xs text-on-surface-variant">البوليصات</div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm p-4 text-center">
        <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center mx-auto mb-2 text-amber-700">
            <span class="material-symbols-outlined">schedule</span>
        </div>
        <div class="text-2xl font-bold text-on-surface">{{ $stats['pending_labels'] }}</div>
        <div class="text-xs text-on-surface-variant">بانتظار الشحن</div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm p-4 text-center">
        <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center mx-auto mb-2 text-indigo-700">
            <span class="material-symbols-outlined">local_shipping</span>
        </div>
        <div class="text-2xl font-bold text-on-surface">{{ $stats['shipped_labels'] }}</div>
        <div class="text-xs text-on-surface-variant">تم شحنها</div>
    </div>
</div>

<!-- Sub Navigation Tabs -->
<div class="flex border-b border-outline-variant mb-8 gap-8 overflow-x-auto no-scrollbar">
    <a href="{{ route('admin.shipping.index', ['tab' => 'zones']) }}"
       class="pb-4 px-2 whitespace-nowrap font-title-lg flex items-center gap-1.5 {{ $activeTab === 'zones' ? 'text-primary font-bold border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary transition-colors' }}">
        <span class="material-symbols-outlined text-lg">map</span> المناطق
    </a>
    <a href="{{ route('admin.shipping.index', ['tab' => 'methods']) }}"
       class="pb-4 px-2 whitespace-nowrap font-title-lg flex items-center gap-1.5 {{ $activeTab === 'methods' ? 'text-primary font-bold border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary transition-colors' }}">
        <span class="material-symbols-outlined text-lg">local_shipping</span> طرق الشحن
    </a>
    <a href="{{ route('admin.shipping.index', ['tab' => 'companies']) }}"
       class="pb-4 px-2 whitespace-nowrap font-title-lg flex items-center gap-1.5 {{ $activeTab === 'companies' ? 'text-primary font-bold border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary transition-colors' }}">
        <span class="material-symbols-outlined text-lg">business</span> شركات الشحن
    </a>
    <a href="{{ route('admin.shipping.index', ['tab' => 'labels']) }}"
       class="pb-4 px-2 whitespace-nowrap font-title-lg flex items-center gap-1.5 {{ $activeTab === 'labels' ? 'text-primary font-bold border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary transition-colors' }}">
        <span class="material-symbols-outlined text-lg">receipt</span> البوليصات
    </a>
</div>

{{-- ========== ZONES TAB ========== --}}
@if($activeTab === 'zones')
    <div class="space-y-6">
        @forelse($zones as $zone)
            <section class="zone-card bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden shadow-sm hover:shadow-md transition-all duration-200">
                {{-- Zone Header --}}
                <div class="bg-surface-container-low p-6 flex justify-between items-center border-b border-outline-variant">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary bg-primary-fixed p-2.5 rounded-lg">location_on</span>
                        <div>
                            <h3 class="text-lg font-bold text-on-surface flex items-center gap-2">
                                {{ $zone->name }}
                                @if($zone->is_default)
                                    <span class="text-tertiary text-xs bg-tertiary-fixed px-2 py-0.5 rounded-full font-label-sm">★ افتراضية</span>
                                @endif
                            </h3>
                            <p class="text-outline text-sm mt-1">
                                @if($zone->countries)
                                    <span class="inline-flex items-center gap-1 ml-2"><span class="material-symbols-outlined text-sm">language</span>{{ $zone->getFormattedCountries() }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm flex items-center gap-1 ml-2 {{ $zone->status === 'active' ? 'text-green-600 font-bold' : 'text-red-500 font-bold' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $zone->status === 'active' ? 'bg-green-600' : 'bg-red-500' }}"></span>
                            {{ $zone->status === 'active' ? 'نشطة' : 'معطلة' }}
                        </span>
                        <a href="{{ route('admin.shipping.zone.edit', $zone) }}" class="p-2 text-on-surface-variant hover:bg-surface-container-high rounded-lg transition-all" title="تعديل">
                            <span class="material-symbols-outlined">edit</span>
                        </a>
                        <form action="{{ route('admin.shipping.zone.destroy', $zone) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه المنطقة؟')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-error hover:bg-error-container rounded-lg transition-all" title="حذف">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Zone Body --}}
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-8">
                    {{-- Coverage Detail --}}
                    <div class="col-span-1 space-y-4 border-l border-outline-variant/20 pl-4">
                        @if($zone->countries)
                            <div>
                                <label class="text-outline text-xs font-bold block mb-1">الدول</label>
                                <p class="text-sm font-semibold text-on-surface">{{ $zone->getFormattedCountries() }}</p>
                            </div>
                        @endif
                        @if($zone->states)
                            <div>
                                <label class="text-outline text-xs font-bold block mb-1">المناطق / الولايات</label>
                                <p class="text-sm text-on-surface">{{ $zone->getFormattedStates() }}</p>
                            </div>
                        @endif
                        @if($zone->cities)
                            <div>
                                <label class="text-outline text-xs font-bold block mb-1">المدن المشمولة</label>
                                <p class="text-xs text-on-surface-variant leading-relaxed">{{ $zone->getFormattedCities() }}</p>
                            </div>
                        @endif
                        @if(!$zone->countries && !$zone->states && !$zone->cities)
                            <div>
                                <p class="text-sm text-on-surface-variant">لا توجد تغطية جغرافية محددة</p>
                            </div>
                        @endif
                    </div>

                    {{-- Shipping Methods --}}
                    <div class="col-span-2 space-y-4">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="font-bold text-on-surface">طرق الشحن المتاحة</h4>
                            <button type="button" onclick="toggleAddMethod({{ $zone->id }})" class="text-primary hover:underline text-sm font-semibold flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">add</span>
                                إضافة طريقة شحن
                            </button>
                        </div>

                        @php $zoneMethods = $zone->methods; @endphp
                        @if($zoneMethods->count() > 0)
                            <div class="grid grid-cols-1 gap-3">
                                @foreach($zoneMethods as $method)
                                    <div class="flex items-center justify-between p-4 bg-surface rounded-xl border border-outline-variant hover:border-primary transition-all group">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-surface-container-high rounded-full flex items-center justify-center">
                                                <span class="material-symbols-outlined text-primary">local_shipping</span>
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="font-bold text-on-surface">{{ $method->name }}</span>
                                                    @if($method->carrier)
                                                        <span class="text-xs text-on-surface-variant font-medium">({{ $method->carrier->name }})</span>
                                                    @endif
                                                    <span class="bg-primary-fixed text-on-primary-fixed-variant px-2 py-0.5 rounded text-xs font-semibold">{{ $method->getTypeLabel() }}</span>
                                                </div>
                                                @if($method->estimated_days)
                                                    <p class="text-outline text-xs mt-1">مدة التوصيل المتوقعة: {{ $method->estimated_days }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-left flex items-center gap-4">
                                            <div>
                                                @if($method->type === 'flat_rate')
                                                    <div class="text-xl font-bold text-primary">{{ number_format($method->flat_rate_amount, 2) }} <span class="text-sm font-medium">ر.س</span></div>
                                                @elseif($method->type === 'free_shipping')
                                                    <div class="font-bold text-emerald-600 text-lg">مجاني</div>
                                                    @if($method->free_shipping_min)
                                                        <span class="text-xs text-on-surface-variant block">فوق {{ number_format($method->free_shipping_min, 0) }} ر.س</span>
                                                    @endif
                                                @elseif($method->type === 'weight_based')
                                                    <div class="font-bold text-purple-700">حسب الوزن</div>
                                                @else
                                                    <div class="font-bold text-on-surface-variant">{{ $method->getTypeLabel() }}</div>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-1 pr-2 border-r border-outline-variant/30">
                                                <a href="{{ route('admin.shipping.method.edit', $method) }}" class="text-primary hover:text-primary-container p-1">
                                                    <span class="material-symbols-outlined text-lg">edit</span>
                                                </a>
                                                <form action="{{ route('admin.shipping.method.destroy', $method) }}" method="POST" class="inline" onsubmit="return confirm('حذف طريقة الشحن؟')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-error hover:text-red-700 p-1">
                                                        <span class="material-symbols-outlined text-lg">delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-on-surface-variant text-sm text-center py-8 bg-surface rounded-xl border border-dashed border-outline-variant">لا توجد طرق شحن محددة بعد لهذه المنطقة الجغرافية.</p>
                        @endif

                        {{-- Quick add method form --}}
                        <div id="addMethod-{{ $zone->id }}" class="hidden bg-primary-fixed/20 rounded-xl p-4 border border-primary/20">
                            <form action="{{ route('admin.shipping.zone.method.store', $zone) }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                    <div>
                                        <label class="text-xs text-on-surface-variant block mb-1">اسم الطريقة</label>
                                        <input type="text" name="name" class="w-full border border-outline-variant rounded-lg px-3 py-2 text-sm bg-white outline-none focus:ring-1 focus:ring-primary" placeholder="شحن عادي" required>
                                    </div>
                                    <div>
                                        <label class="text-xs text-on-surface-variant block mb-1">النوع</label>
                                        <select name="type" class="w-full border border-outline-variant rounded-lg px-3 py-2 text-sm bg-white outline-none cursor-pointer">
                                            <option value="flat_rate">شحن ثابت</option>
                                            <option value="free_shipping">شحن مجاني</option>
                                            <option value="weight_based">حسب الوزن</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs text-on-surface-variant block mb-1">المبلغ (ر.س)</label>
                                        <input type="number" name="flat_rate_amount" step="0.01" min="0" class="w-full border border-outline-variant rounded-lg px-3 py-2 text-sm bg-white outline-none" placeholder="25">
                                    </div>
                                    <div>
                                        <label class="text-xs text-on-surface-variant block mb-1">مدة التوصيل</label>
                                        <input type="text" name="estimated_days" class="w-full border border-outline-variant rounded-lg px-3 py-2 text-sm bg-white outline-none" placeholder="3-5 أيام">
                                    </div>
                                </div>
                                <div class="mt-3 flex gap-2">
                                    <button type="submit" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-sm flex items-center gap-1 font-semibold">
                                        <span class="material-symbols-outlined text-sm">check</span> إضافة الطريقة
                                    </button>
                                    <button type="button" onclick="toggleAddMethod({{ $zone->id }})" class="bg-white hover:bg-surface border border-outline-variant text-on-surface px-4 py-2 rounded-lg text-sm">إلغاء</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        @empty
            <div class="text-center py-16 text-on-surface-variant bg-surface-container-lowest rounded-xl border border-dashed border-outline-variant">
                <span class="material-symbols-outlined text-5xl mb-3 text-outline">map</span>
                <p class="text-lg mb-2 font-bold">لا توجد مناطق شحن مضافة بعد</p>
                <a href="{{ route('admin.shipping.zone.create') }}" class="text-primary hover:text-primary text-sm inline-flex items-center gap-1 font-bold">
                    <span class="material-symbols-outlined text-sm">add</span> إضافة منطقة شحن جديدة
                </a>
            </div>
        @endforelse

        <div class="mt-4">{{ $zones->withQueryString()->links() }}</div>
    </div>

{{-- ========== METHODS TAB ========== --}}
@elseif($activeTab === 'methods')
    <div class="space-y-3">
        @forelse($methods as $method)
            <div class="flex items-center justify-between p-4 bg-surface-container-lowest rounded-xl border border-outline-variant hover:border-primary transition-all shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-surface-container-high rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">local_shipping</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-on-surface text-base">{{ $method->name }}</span>
                            <span class="bg-primary-fixed text-on-primary-fixed-variant px-2 py-0.5 rounded text-xs font-semibold">{{ $method->getTypeLabel() }}</span>
                        </div>
                        <div class="text-xs text-on-surface-variant mt-1.5 flex items-center gap-3">
                            @if($method->zone)
                                <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-xs">location_on</span>{{ $method->zone->name }}</span>
                            @endif
                            @if($method->carrier)
                                <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-xs">business</span>{{ $method->carrier->name }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-6 text-sm">
                    <div class="text-left">
                        @if($method->type === 'flat_rate')
                            <div class="text-xl font-bold text-primary">{{ number_format($method->flat_rate_amount, 2) }} <span class="text-sm font-medium">ر.س</span></div>
                        @elseif($method->type === 'free_shipping')
                            <div class="font-bold text-emerald-600 text-lg">مجاني</div>
                            @if($method->free_shipping_min)
                                <span class="text-xs text-on-surface-variant">فوق {{ number_format($method->free_shipping_min, 0) }} ر.س</span>
                            @endif
                        @elseif($method->type === 'weight_based')
                            <div class="font-bold text-purple-700">حسب الوزن</div>
                        @else
                            <div class="font-bold text-on-surface-variant">{{ $method->getTypeLabel() }}</div>
                        @endif
                    </div>
                    @if($method->estimated_days)
                        <span class="text-on-surface-variant inline-flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span>{{ $method->estimated_days }}</span>
                    @endif
                    <span class="flex items-center gap-1 text-xs {{ $method->status ? 'text-green-600 font-bold' : 'text-red-500 font-bold' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $method->status ? 'bg-green-600' : 'bg-red-500' }}"></span> {{ $method->status ? 'نشطة' : 'معطلة' }}
                    </span>
                    <div class="flex gap-1 pr-2 border-r border-outline-variant/30">
                        <a href="{{ route('admin.shipping.method.edit', $method) }}" class="text-primary hover:text-primary-container p-1">
                            <span class="material-symbols-outlined">edit</span>
                        </a>
                        <form action="{{ route('admin.shipping.method.destroy', $method) }}" method="POST" class="inline" onsubmit="return confirm('حذف طريقة الشحن؟')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-error hover:text-red-700 p-1">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 text-on-surface-variant bg-surface-container-lowest rounded-xl border border-dashed border-outline-variant">
                <span class="material-symbols-outlined text-5xl mb-3 text-outline">local_shipping</span>
                <p class="font-bold text-lg">لا توجد طرق شحن مضافة بعد</p>
            </div>
        @endforelse
        <div class="mt-4">{{ $methods->withQueryString()->links() }}</div>
    </div>

{{-- ========== COMPANIES TAB ========== --}}
@elseif($activeTab === 'companies')
    <div>
        <div class="overflow-x-auto bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-surface-container-low text-on-surface-variant text-xs border-b border-outline-variant">
                        <th class="px-6 py-4 font-bold">الشركة</th>
                        <th class="px-6 py-4 font-bold">الموقع الإلكتروني</th>
                        <th class="px-6 py-4 font-bold">رابط التتبع</th>
                        <th class="px-6 py-4 font-bold">الحالة</th>
                        <th class="px-6 py-4 font-bold text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse($companies as $company)
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($company->logo)
                                        <img src="{{ $company->logo }}" class="w-10 h-10 rounded object-contain bg-white border p-1" alt="{{ $company->name }}">
                                    @else
                                        <div class="w-10 h-10 rounded bg-surface-container-high flex items-center justify-center text-outline">
                                            <span class="material-symbols-outlined text-lg">local_shipping</span>
                                        </div>
                                    @endif
                                    <span class="font-bold text-on-surface">{{ $company->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                @if($company->website)
                                    <a href="{{ $company->website }}" target="_blank" class="text-primary hover:underline font-mono">{{ $company->website }}</a>
                                @else
                                    <span class="text-on-surface-variant">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-on-surface-variant max-w-xs truncate" title="{{ $company->tracking_url }}">
                                {{ $company->tracking_url ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $company->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-error-container text-error' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $company->status === 'active' ? 'bg-emerald-500' : 'bg-error' }}"></span>
                                    {{ $company->status === 'active' ? 'نشطة' : 'معطلة' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.shipping.company.edit', $company) }}" class="p-1 text-on-surface-variant hover:text-primary transition-colors" title="تعديل">
                                        <span class="material-symbols-outlined">edit</span>
                                    </a>
                                    <form action="{{ route('admin.shipping.company.destroy', $company) }}" method="POST" class="inline" onsubmit="return confirm('حذف الشركة؟')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1 text-error hover:text-red-700 transition-colors" title="حذف">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-12 text-on-surface-variant font-bold">لا توجد شركات شحن مضافة بعد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $companies->withQueryString()->links() }}</div>
    </div>

{{-- ========== LABELS TAB ========== --}}
@elseif($activeTab === 'labels')
    <div>
        <div class="overflow-x-auto bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-surface-container-low text-on-surface-variant text-xs border-b border-outline-variant">
                        <th class="px-6 py-4 font-bold">رقم التتبع</th>
                        <th class="px-6 py-4 font-bold">الطلب</th>
                        <th class="px-6 py-4 font-bold">شركة الشحن</th>
                        <th class="px-6 py-4 font-bold">الوزن</th>
                        <th class="px-6 py-4 font-bold">التكلفة</th>
                        <th class="px-6 py-4 font-bold">الحالة</th>
                        <th class="px-6 py-4 font-bold text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse($labels as $label)
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.shipping.label.show', $label) }}" class="font-mono text-primary font-bold hover:underline">
                                    {{ $label->tracking_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                @if($label->order)
                                    <a href="{{ route('admin.orders.show', $label->order) }}" class="text-primary hover:underline font-bold">
                                        #{{ $label->order->order_number }}
                                    </a>
                                @else
                                    <span class="text-on-surface-variant">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-on-surface font-semibold">{{ $label->carrier?->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-on-surface-variant font-medium">{{ $label->weight ? $label->weight . ' كجم' : '-' }}</td>
                            <td class="px-6 py-4 font-bold text-primary">{{ number_format($label->cost, 2) }} ر.س</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-{{ $label->getStatusColor() }}-100 text-{{ $label->getStatusColor() }}-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-{{ $label->getStatusColor() }}-500"></span>
                                    {{ $label->getStatusLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.shipping.label.show', $label) }}" class="p-1 text-on-surface-variant hover:text-primary transition-colors" title="عرض">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </a>
                                    @if($label->status === 'pending')
                                        <form action="{{ route('admin.shipping.label.updateStatus', $label) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="shipped">
                                            <button type="submit" class="p-1 text-indigo-600 hover:text-indigo-800 transition-colors" title="شحن">
                                                <span class="material-symbols-outlined">local_shipping</span>
                                            </button>
                                        </form>
                                    @elseif($label->status === 'shipped')
                                        <form action="{{ route('admin.shipping.label.updateStatus', $label) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="delivered">
                                            <button type="submit" class="p-1 text-emerald-600 hover:text-emerald-800 transition-colors" title="تسليم">
                                                <span class="material-symbols-outlined">check_circle</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-12 text-on-surface-variant font-bold">لا توجد بوليصات شحن منشأة بعد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $labels->withQueryString()->links() }}</div>
    </div>
@endif

@push('scripts')
<script>
function toggleAddMethod(zoneId) {
    const el = document.getElementById('addMethod-' + zoneId);
    if(el) el.classList.toggle('hidden');
}
</script>
@endpush
@endsection
