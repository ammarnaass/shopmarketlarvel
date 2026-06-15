@extends('admin.layout')

@section('title', 'التقارير')

@section('content')
{{-- Date Filter Card --}}
<section class="bg-surface-container-lowest rounded-xl shadow-sm p-5 border border-outline-variant/30 flex flex-wrap items-center gap-6">
    <div class="flex items-center gap-3">
        <div class="p-2 bg-primary/5 rounded-lg">
            <span class="material-symbols-outlined text-primary">calendar_today</span>
        </div>
        <span class="font-title-lg text-on-surface">الفترة الزمنية:</span>
    </div>
    <form method="GET" action="{{ route('admin.reports.index') }}" class="flex items-center gap-2">
        <select name="period" onchange="this.form.submit()" class="border border-outline-variant rounded-lg text-label-md px-3 py-2 focus:ring-primary focus:border-primary bg-surface-container-lowest">
            <option value="7"  {{ $period == '7'  ? 'selected' : '' }}>آخر 7 أيام</option>
            <option value="14" {{ $period == '14' ? 'selected' : '' }}>آخر 14 يوم</option>
            <option value="30" {{ $period == '30' ? 'selected' : '' }}>آخر 30 يوم</option>
            <option value="60" {{ $period == '60' ? 'selected' : '' }}>آخر 60 يوم</option>
            <option value="90" {{ $period == '90' ? 'selected' : '' }}>آخر 90 يوم</option>
        </select>
    </form>
</section>

{{-- KPI Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
    {{-- Revenue --}}
    <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border-r-4 border-primary relative overflow-hidden group hover:shadow-md transition-all">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-on-surface-variant text-label-md mb-1">الإيرادات</p>
                <h3 class="text-headline-md font-bold text-on-surface">{{ number_format($summary['total_revenue'], 0) }}</h3>
            </div>
            <div class="p-3 bg-primary/10 rounded-full text-primary group-hover:bg-primary group-hover:text-on-primary transition-all duration-300">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">payments</span>
            </div>
        </div>
        <div class="flex items-center gap-1 text-[12px]">
            <span class="text-green-600 font-bold flex items-center"><span class="material-symbols-outlined text-[14px]">trending_up</span> إجمالي</span>
            <span class="text-outline">الإيرادات الكلية</span>
        </div>
    </div>
    {{-- Orders --}}
    <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border-r-4 border-secondary relative overflow-hidden group hover:shadow-md transition-all">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-on-surface-variant text-label-md mb-1">الطلبات</p>
                <h3 class="text-headline-md font-bold text-on-surface">{{ number_format($summary['total_orders']) }}</h3>
            </div>
            <div class="p-3 bg-secondary/10 rounded-full text-secondary group-hover:bg-secondary group-hover:text-on-secondary transition-all duration-300">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">shopping_basket</span>
            </div>
        </div>
        <div class="flex items-center gap-1 text-[12px]">
            <span class="text-green-600 font-bold flex items-center"><span class="material-symbols-outlined text-[14px]">trending_up</span> إجمالي</span>
            <span class="text-outline">عدد الطلبات</span>
        </div>
    </div>
    {{-- Avg Order --}}
    <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border-r-4 border-tertiary relative overflow-hidden group hover:shadow-md transition-all">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-on-surface-variant text-label-md mb-1">متوسط الطلب</p>
                <h3 class="text-headline-md font-bold text-on-surface">{{ number_format($summary['avg_order_value'], 0) }}</h3>
            </div>
            <div class="p-3 bg-tertiary/10 rounded-full text-tertiary group-hover:bg-tertiary group-hover:text-on-tertiary transition-all duration-300">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">analytics</span>
            </div>
        </div>
        <div class="flex items-center gap-1 text-[12px]">
            <span class="text-green-600 font-bold flex items-center"><span class="material-symbols-outlined text-[14px]">trending_up</span> متوسط</span>
            <span class="text-outline">قيمة الطلب</span>
        </div>
    </div>
    {{-- New Customers --}}
    <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border-r-4 border-outline relative overflow-hidden group hover:shadow-md transition-all">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-on-surface-variant text-label-md mb-1">عملاء جدد</p>
                <h3 class="text-headline-md font-bold text-on-surface">{{ number_format($summary['new_customers']) }}</h3>
            </div>
            <div class="p-3 bg-outline/10 rounded-full text-outline group-hover:bg-outline group-hover:text-surface-bright transition-all duration-300">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">person_add</span>
            </div>
        </div>
        <div class="flex items-center gap-1 text-[12px]">
            <span class="text-green-600 font-bold flex items-center"><span class="material-symbols-outlined text-[14px]">trending_up</span> إجمالي</span>
            <span class="text-outline">عملاء جدد</span>
        </div>
    </div>
    {{-- Completed Orders --}}
    <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border-r-4 border-primary-fixed-dim relative overflow-hidden group hover:shadow-md transition-all">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-on-surface-variant text-label-md mb-1">طلبات مكتملة</p>
                <h3 class="text-headline-md font-bold text-on-surface">{{ number_format($summary['completed_orders']) }}</h3>
            </div>
            <div class="p-3 bg-primary-fixed-dim/10 rounded-full text-primary-fixed-dim group-hover:bg-primary-fixed-dim group-hover:text-on-primary-fixed transition-all duration-300">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">check_circle</span>
            </div>
        </div>
        <div class="flex items-center gap-1 text-[12px]">
            <span class="text-green-600 font-bold flex items-center"><span class="material-symbols-outlined text-[14px]">trending_up</span> إجمالي</span>
            <span class="text-outline">الطلبات المكتملة</span>
        </div>
    </div>
</div>

{{-- Revenue Chart --}}
<div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/30 p-6">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-3">
            <div class="w-1 h-6 bg-primary rounded-full"></div>
            <h4 class="font-title-lg text-on-surface">الإيرادات والطلبات</h4>
        </div>
    </div>
    @php
        $maxRev = $chartData ? max(array_column($chartData, 'revenue')) : 0;
        $maxRev = $maxRev > 0 ? $maxRev : 1;
    @endphp
    <div class="flex items-end gap-1 h-64">
        @foreach($chartData as $day)
            @php
                $h = max(4, ($day['revenue'] / $maxRev) * 100);
            @endphp
            <div class="flex-1 flex flex-col items-center gap-1 group relative" title="{{ $day['label'] }}: {{ number_format($day['revenue'], 0) }} ({{ $day['orders'] }} طلب)">
                <div class="absolute bottom-full mb-1 hidden group-hover:block bg-gray-900 text-white text-xs rounded px-2 py-1 whitespace-nowrap z-10">
                    {{ number_format($day['revenue'], 0) }} — {{ $day['orders'] }} طلب
                </div>
                <div class="w-full bg-gradient-to-t from-primary to-primary-fixed rounded-t hover:from-primary-fixed hover:to-primary transition-all" style="height: {{ $h }}%"></div>
                <div class="text-[10px] text-outline writing-mode-vertical">{{ $day['label'] }}</div>
            </div>
        @endforeach
    </div>
    @if(count($chartData) > 14)
        <p class="text-xs text-outline text-center mt-4">عرض {{ count($chartData) }} يوم</p>
    @endif
</div>

{{-- Top Products & Top Categories --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    {{-- Top Products Table --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/30 overflow-hidden">
        <div class="p-6 border-b border-outline-variant/30 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-primary">military_tech</span>
                <h4 class="font-title-lg text-on-surface">أفضل المنتجات (إيرادات)</h4>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-surface-container-low text-outline text-label-sm">
                        <th class="px-6 py-4 font-semibold">#</th>
                        <th class="px-6 py-4 font-semibold">المنتج</th>
                        <th class="px-6 py-4 font-semibold">المبيعات</th>
                        <th class="px-6 py-4 font-semibold">الكمية</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse($topProducts as $i => $p)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="px-6 py-4 text-label-md font-bold text-outline">{{ $i + 1 }}</td>
                            <td class="px-6 py-4">
                                <span class="text-label-md font-medium">{{ $p->product_name }}</span>
                            </td>
                            <td class="px-6 py-4 text-label-md font-bold">{{ number_format($p->revenue, 0) }}</td>
                            <td class="px-6 py-4 text-label-md">{{ $p->qty }} قطعة</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-outline">لا توجد بيانات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Top Categories Table --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/30 overflow-hidden">
        <div class="p-6 border-b border-outline-variant/30 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-secondary">category</span>
                <h4 class="font-title-lg text-on-surface">أفضل التصنيفات</h4>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-surface-container-low text-outline text-label-sm">
                        <th class="px-6 py-4 font-semibold">#</th>
                        <th class="px-6 py-4 font-semibold">التصنيف</th>
                        <th class="px-6 py-4 font-semibold">الإيرادات</th>
                        <th class="px-6 py-4 font-semibold">الطلبات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse($topCategories as $i => $c)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="px-6 py-4 text-label-md font-bold text-outline">{{ $i + 1 }}</td>
                            <td class="px-6 py-4">
                                <span class="text-label-md font-medium">{{ $c->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-label-md font-bold">{{ number_format($c->revenue, 0) }}</td>
                            <td class="px-6 py-4 text-label-md">{{ $c->orders_count }} طلب</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-outline">لا توجد بيانات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Country Revenue --}}
@if($countryRevenue->count() > 0)
    <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/30 p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-6 bg-secondary rounded-full"></div>
            <h4 class="font-title-lg text-on-surface">الإيرادات حسب الدولة</h4>
        </div>
        @php $maxC = max($countryRevenue->pluck('revenue')->toArray()) ?: 1; @endphp
        <div class="space-y-4">
            @foreach($countryRevenue as $c)
                <div>
                    <div class="flex items-center justify-between text-sm mb-1.5">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-outline text-[18px]">location_on</span>
                            <span class="font-semibold text-on-surface">{{ $c->country_name ?? $c->country_code }}</span>
                            <span class="text-xs text-outline">({{ $c->orders_count }} طلب)</span>
                        </div>
                        <span class="font-bold text-primary">{{ number_format($c->revenue, 0) }}</span>
                    </div>
                    <div class="h-2.5 bg-surface-container-low rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-l from-primary to-primary-fixed rounded-full transition-all" style="width: {{ ($c->revenue / $maxC) * 100 }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

{{-- Export Actions --}}
<footer class="flex items-center justify-center gap-4 py-4">
    <button class="flex items-center gap-2 px-6 py-3 bg-[#1D6F42] text-white rounded-lg font-bold text-label-md hover:bg-[#155331] transition-all shadow-lg shadow-green-900/10 active:scale-95">
        <span class="material-symbols-outlined">table_view</span>
        تصدير Excel
    </button>
    <button class="flex items-center gap-2 px-6 py-3 bg-[#E02424] text-white rounded-lg font-bold text-label-md hover:bg-[#B91C1C] transition-all shadow-lg shadow-red-900/10 active:scale-95">
        <span class="material-symbols-outlined">picture_as_pdf</span>
        تصدير PDF
    </button>
    <button class="flex items-center gap-2 px-6 py-3 bg-on-background text-white rounded-lg font-bold text-label-md hover:bg-black transition-all shadow-lg shadow-black/10 active:scale-95">
        <span class="material-symbols-outlined">print</span>
        طباعة التقرير
    </button>
</footer>
@endsection
