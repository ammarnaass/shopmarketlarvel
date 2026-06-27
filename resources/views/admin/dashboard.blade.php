@extends('admin.layout')

@section('title', __t('admin.title'))

@section('page_title', __t('admin.title'))

@section('content')
{{-- Main KPIs --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="kpi-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-on-surface-variant">{{ __t('admin.dashboard.total_revenue') }}</p>
                <p class="text-2xl font-bold mt-1 text-on-surface">{{ number_format($stats['total_revenue'], 0) }}</p>
                <p class="text-xs mt-1 {{ $stats['revenue_growth'] >= 0 ? 'text-emerald-600' : 'text-error' }}">
                    <span class="material-symbols-outlined text-sm align-text-bottom">{{ $stats['revenue_growth'] >= 0 ? 'trending_up' : 'trending_down' }}</span>
                    {{ $stats['revenue_growth'] >= 0 ? '+' : '' }}{{ $stats['revenue_growth'] }}% {{ __t('admin.dashboard.revenue_growth') }}
                </p>
            </div>
            <div class="kpi-icon">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">payments</span>
            </div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-on-surface-variant">{{ __t('admin.dashboard.total_orders') }}</p>
                <p class="text-2xl font-bold mt-1 text-on-surface">{{ number_format($stats['total_orders']) }}</p>
                <p class="text-xs mt-1 {{ $stats['orders_growth'] >= 0 ? 'text-emerald-600' : 'text-error' }}">
                    <span class="material-symbols-outlined text-sm align-text-bottom">{{ $stats['orders_growth'] >= 0 ? 'trending_up' : 'trending_down' }}</span>
                    {{ $stats['orders_growth'] >= 0 ? '+' : '' }}{{ $stats['orders_growth'] }}%
                </p>
            </div>
            <div class="kpi-icon">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">shopping_cart</span>
            </div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-on-surface-variant">{{ __t('admin.dashboard.total_users') }}</p>
                <p class="text-2xl font-bold mt-1 text-on-surface">{{ number_format($stats['total_customers']) }}</p>
                <p class="text-xs mt-1 text-on-surface-variant">+{{ $stats['new_customers_this_month'] }} {{ __t('admin.dashboard.new_customers') }}</p>
            </div>
            <div class="kpi-icon">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">group</span>
            </div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-on-surface-variant">{{ __t('admin.dashboard.total_products') }}</p>
                <p class="text-2xl font-bold mt-1 text-on-surface">{{ number_format($stats['total_products']) }}</p>
                <p class="text-xs mt-1 {{ $stats['low_stock'] > 0 ? 'text-warning' : 'text-emerald-600' }}">
                    @if($stats['low_stock'] > 0)
                        <span class="material-symbols-outlined text-sm align-text-bottom">warning</span> {{ $stats['low_stock'] }} {{ __t('admin.dashboard.low_stock') }}
                    @else
                        <span class="material-symbols-outlined text-sm align-text-bottom">check_circle</span> {{ __t('admin.dashboard.all_stock_good') }}
                    @endif
                </p>
            </div>
            <div class="kpi-icon">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">inventory_2</span>
            </div>
        </div>
    </div>
</div>

{{-- Charts & Distribution --}}
<div class="grid lg:grid-cols-3 gap-6">
    {{-- Weekly Sales Chart --}}
    <div class="lg:col-span-2 card">
        <div class="card-header flex items-center justify-between">
            <h3 class="font-bold text-lg flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">trending_up</span>
                {{ __t('admin.dashboard.weekly_sales') }}
            </h3>
            <span class="text-xs text-on-surface-variant">{{ __t('admin.dashboard.last_7_days') }}</span>
        </div>
        <div class="card-body">
            <div class="flex items-end gap-2 h-48">
                @php $maxRev = max(array_column($weeklyChart, 'revenue')) ?: 1; @endphp
                @foreach($weeklyChart as $day)
                    @php $h = $maxRev > 0 ? max(8, ($day['revenue'] / $maxRev) * 100) : 8; @endphp
                    <div class="flex-1 flex flex-col items-center gap-1" title="{{ number_format($day['revenue'], 0) }}">
                        <div class="w-full bg-gradient-to-t from-primary to-primary-container rounded-t-lg transition-all hover:from-primary-container hover:to-primary" style="height: {{ $h }}%"></div>
                        <div class="text-xs text-on-surface-variant">{{ $day['day'] }}</div>
                        <div class="text-xs font-semibold text-on-surface">{{ $day['orders'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Order Status Distribution --}}
    <div class="card">
        <div class="card-header">
            <h3 class="font-bold text-lg flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">donut_large</span>
                {{ __t('admin.dashboard.order_statuses') }}
            </h3>
        </div>
        <div class="card-body space-y-4">
            @php $totalOrders = max(1, array_sum(array_column($statusDistribution, 'count'))); @endphp
            @foreach($statusDistribution as $status)
                @php $percent = round(($status['count'] / $totalOrders) * 100, 1); @endphp
                <div>
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="font-medium">{{ $status['label'] }}</span>
                        <span class="text-on-surface-variant">{{ $status['count'] }} ({{ $percent }}%)</span>
                    </div>
                    <div class="h-2 bg-surface-container-highest rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all" style="width: {{ $percent }}%; background: {{ $status['color'] }}"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Quick settings --}}
<div class="card">
    <div class="card-header">
        <h3 class="font-bold text-lg flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">tune</span>
            {{ __t('admin.dashboard.quick_settings') }}
        </h3>
    </div>
    <div class="card-body">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="border border-outline-variant/50 rounded-lg p-3">
                <div class="text-xs text-on-surface-variant mb-1">{{ __t('admin.dashboard.currency_label') }}</div>
                <div class="font-bold text-sm mb-2 text-on-surface">{{ $settings['currency'] }}</div>
                <a href="#" class="text-primary text-xs hover:underline">{{ __t('admin.dashboard.change') }}</a>
            </div>
            <div class="border border-outline-variant/50 rounded-lg p-3">
                <div class="text-xs text-on-surface-variant mb-1">{{ __t('admin.dashboard.shipping_company') }}</div>
                <div class="font-bold text-sm mb-2 text-on-surface">{{ $settings['shipping_company'] }}</div>
                <button onclick="changeQuickSetting('shipping_company')" class="text-primary text-xs hover:underline">{{ __t('admin.dashboard.change') }}</button>
            </div>
            <div class="border border-outline-variant/50 rounded-lg p-3">
                <div class="text-xs text-on-surface-variant mb-1">{{ __t('admin.dashboard.payment_label') }}</div>
                <div class="font-bold text-sm mb-2 text-on-surface">{{ $settings['payment_method'] }}</div>
                <a href="{{ route('admin.coupons.index') }}" class="text-primary text-xs hover:underline">{{ __t('admin.dashboard.settings') }}</a>
            </div>
            <div class="border border-outline-variant/50 rounded-lg p-3">
                <div class="text-xs text-on-surface-variant mb-1">{{ __t('admin.dashboard.theme_label') }}</div>
                <div class="font-bold text-sm mb-2 text-on-surface">{{ $settings['theme'] }}</div>
                <a href="#" class="text-primary text-xs hover:underline">{{ __t('admin.dashboard.change') }}</a>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-outline-variant/30 flex flex-wrap gap-3 text-sm">
            <span class="text-on-surface-variant">{{ __t('admin.dashboard.quick_reports') }}:</span>
            <a href="{{ route('admin.orders.index') }}" class="text-primary hover:underline">{{ __t('admin.dashboard.today_sales') }}</a>
            <a href="{{ route('admin.orders.index') }}" class="text-primary hover:underline">{{ __t('admin.dashboard.week_sales') }}</a>
            <a href="{{ route('admin.orders.index') }}" class="text-primary hover:underline">{{ __t('admin.dashboard.month_sales') }}</a>
        </div>
    </div>
</div>

{{-- Tables section --}}
<div class="grid lg:grid-cols-3 gap-6">
    {{-- Recent orders --}}
    <div class="lg:col-span-2 card">
        <div class="card-header flex items-center justify-between">
            <h3 class="font-bold text-lg flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">receipt_long</span>
                {{ __t('admin.dashboard.recent_orders') }}
            </h3>
            <a href="{{ route('admin.orders.index') }}" class="text-primary text-sm hover:underline">{{ __t('admin.dashboard.view_all') }}</a>
        </div>
        <div class="overflow-x-auto">
            <table class="table-wrap">
                <thead>
                    <tr>
                        <th>{{ __t('admin.dashboard.order_id') }}</th>
                        <th>{{ __t('admin.dashboard.customer') }}</th>
                        <th>{{ __t('admin.dashboard.product_col') }}</th>
                        <th>{{ __t('admin.dashboard.total') }}</th>
                        <th>{{ __t('admin.dashboard.status') }}</th>

                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td class="font-semibold">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-primary hover:underline">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td>
                                {{ $order->user?->name ?? $order->shippingAddress?->name ?? __t('admin.dashboard.guest') }}
                                @if($order->is_instant_buy)
                                    <span class="material-symbols-outlined text-sm align-text-bottom text-tertiary" title="{{ __t('admin.dashboard.instant_order') }}">bolt</span>
                                @endif
                            </td>
                            <td>
                                <div class="line-clamp-1">{{ $order->items->first()?->product_name ?? '—' }}</div>
                                @if($order->items->count() > 1)
                                    <div class="text-xs text-on-surface-variant">+{{ $order->items->count() - 1 }} {{ __t('admin.dashboard.more_products') }}</div>
                                @endif
                            </td>
                            <td class="font-bold">{{ number_format($order->grand_total, 0) }}</td>
                            <td>
                                <span class="badge
                                    @switch($order->status)
                                        @case('pending') badge-warning @break
                                        @case('confirmed') badge-info @break
                                        @case('processing') badge-primary @break
                                        @case('shipped') badge-primary @break
                                        @case('delivered') badge-success @break
                                        @case('cancelled') badge-danger @break
                                    @endswitch">
                                    {{ $order->status_name }}
                                </span>
                            </td>

                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-on-surface-variant">{{ __t('admin.dashboard.no_orders') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Right column --}}
    <div class="space-y-6">
        {{-- Low stock --}}
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h3 class="font-bold text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-error" style="font-variation-settings:'FILL' 1">report</span>
                    {{ __t('admin.dashboard.low_stock_title') }}
                </h3>
                <a href="{{ route('admin.products.index') }}" class="text-primary text-xs hover:underline">{{ __t('admin.dashboard.all_link') }}</a>
            </div>
            <div class="divide-y divide-outline-variant/30">
                @forelse($lowStockProducts as $p)
                    <div class="p-3 flex items-center gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-sm line-clamp-1 text-on-surface">{{ $p->name }}</div>
                            <div class="text-xs text-on-surface-variant">{{ $p->category?->name ?? '—' }}</div>
                        </div>
                        <div class="text-left">
                            <span class="badge badge-danger">{{ $p->stock }} {{ __t('admin.dashboard.pieces') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-sm text-on-surface-variant">{{ __t('admin.dashboard.stock_is_good') }} ✓</div>
                @endforelse
            </div>
        </div>

        {{-- Top products --}}
        <div class="card">
            <div class="card-header">
                <h3 class="font-bold text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-tertiary" style="font-variation-settings:'FILL' 1">local_fire_department</span>
                    {{ __t('admin.dashboard.top_selling') }}
                </h3>
            </div>
            <div class="divide-y divide-outline-variant/30">
                @forelse($topProducts as $i => $tp)
                    <div class="p-3 flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-primary-fixed text-primary flex items-center justify-center font-bold text-xs">{{ $i + 1 }}</div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-sm line-clamp-1 text-on-surface">{{ $tp->product_name }}</div>
                            <div class="text-xs text-on-surface-variant">{{ $tp->total_qty }} {{ __t('admin.dashboard.pieces_sold') }}</div>
                        </div>
                        <div class="text-left text-xs">
                            <div class="font-bold text-emerald-600">{{ number_format($tp->total_revenue, 0) }}</div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-sm text-on-surface-variant">{{ __t('admin.dashboard.no_sales') }}</div>
                @endforelse
            </div>
        </div>

        {{-- Active coupons --}}
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h3 class="font-bold text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-tertiary" style="font-variation-settings:'FILL' 1">confirmation_number</span>
                    {{ __t('admin.dashboard.active_coupons') }}
                </h3>
                <a href="{{ route('admin.coupons.index') }}" class="text-primary text-xs hover:underline">{{ __t('admin.dashboard.manage') }}</a>
            </div>
            <div class="divide-y divide-outline-variant/30">
                @forelse($activeCoupons as $c)
                    <div class="p-3 flex items-center gap-3">
                        <div class="font-mono font-bold text-sm bg-primary-fixed text-primary px-2 py-1 rounded">{{ $c->code }}</div>
                        <div class="flex-1 text-xs text-on-surface-variant">
                            @if($c->type === 'percent')
                                {{ $c->value }}%
                            @else
                                {{ number_format($c->value, 0) }} {{ __t('admin.dashboard.fixed') }}
                            @endif
                        </div>
                        <div class="text-xs text-on-surface-variant">
                            {{ $c->used_count }}{{ $c->usage_limit ? '/' . $c->usage_limit : '' }}
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-sm text-on-surface-variant">{{ __t('admin.dashboard.no_active_coupons') }}</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function changeQuickSetting(key) {
    const value = prompt('القيمة الجديدة لـ ' + key + ':');
    if (!value) return;

    fetch('{{ route("admin.quickSetting") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ key, value }),
    })
    .then(r => r.json())
    .then(data => {
        alert(data.message || (data.success ? 'تم التحديث' : 'حدث خطأ'));
        if (data.success) location.reload();
    });
}
</script>
@endpush
@endsection