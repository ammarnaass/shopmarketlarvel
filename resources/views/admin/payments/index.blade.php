@extends('admin.layout')

@section('title', __t('admin.payments.title'))

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold">{{ __t('admin.payments.title') }}</h1>
        <p class="text-gray-600 text-sm mt-1">{{ __t('admin.payments.manage_payments') }}</p>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-5 rounded-xl shadow-sm">
        <p class="text-green-100 text-sm">{{ __t('admin.payments.total_revenue') }}</p>
        <p class="text-3xl font-bold mt-1">{{ number_format($stats['total_revenue'], 0) }}</p>
        <p class="text-xs text-green-100 mt-1"><i class="fas fa-arrow-up ml-1"></i>{{ __t('admin.payments.from_online') }}</p>
    </div>
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-5 rounded-xl shadow-sm">
        <p class="text-blue-100 text-sm">{{ __t('admin.payments.cod_revenue') }}</p>
        <p class="text-3xl font-bold mt-1">{{ number_format($stats['cod_revenue'], 0) }}</p>
        <p class="text-xs text-blue-100 mt-1"><i class="fas fa-money-bill-wave ml-1"></i>{{ __t('admin.payments.from_delivered') }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-gray-500 text-sm">{{ __t('admin.payments.total_transactions') }}</p>
        <p class="text-3xl font-bold mt-1">{{ number_format($stats['total']) }}</p>
        <div class="flex gap-3 mt-2 text-xs">
            <span class="text-green-600"><i class="fas fa-circle text-[8px]"></i> {{ $stats['paid'] }} {{ __t('admin.payments.paid') }}</span>
            <span class="text-yellow-600"><i class="fas fa-circle text-[8px]"></i> {{ $stats['pending'] }} {{ __t('admin.payments.pending') }}</span>
            <span class="text-red-600"><i class="fas fa-circle text-[8px]"></i> {{ $stats['failed'] }} {{ __t('admin.payments.failed') }}</span>
        </div>
    </div>
</div>

{{-- Payment Methods --}}
<div class="bg-white rounded-xl shadow-sm p-5 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-bold text-lg"><i class="fas fa-credit-card text-blue-600 ml-2"></i>{{ __t('admin.payments.available_methods') }}</h2>
        <a href="{{ route('admin.payment-methods.index') }}" class="text-sm text-primary hover:underline flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">settings</span>
            {{ __t('admin.payment_methods.manage_methods') }}
        </a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($methods as $method)
            <div class="border-2 {{ $method->is_active ? 'border-green-500 bg-green-50' : 'border-gray-200' }} rounded-xl p-4 relative">
                @if($method->is_active)
                    <span class="absolute top-2 left-2 bg-green-600 text-white text-xs px-2 py-0.5 rounded-full">
                        <span class="material-symbols-outlined text-xs align-middle" style="font-size:12px">check</span> {{ __t('admin.payments.enabled') }}
                    </span>
                @else
                    <span class="absolute top-2 left-2 bg-gray-200 text-gray-600 text-xs px-2 py-0.5 rounded-full">
                        {{ __t('admin.payment_methods.inactive') }}
                    </span>
                @endif
                <div class="w-12 h-12 rounded-lg flex items-center justify-center mb-3 {{ 'bg-' . $method->color . '-100 text-' . $method->color . '-600' }}">
                    <span class="material-symbols-outlined">{{ $method->icon }}</span>
                </div>
                <h3 class="font-bold">{{ $method->name }}</h3>
                <p class="text-xs text-gray-600 mt-1">{{ $method->description }}</p>
                @if($method->fees_value > 0)
                    <p class="text-xs mt-2 {{ 'text-' . $method->color . '-600' }} font-medium">
                        @if($method->fees_type === 'percent'){{ $method->fees_value }}%@else{{ number_format($method->fees_value, 0) }} @endif
                        {{ __t('admin.payment_methods.fees') }}
                    </p>
                @endif
            </div>
        @endforeach
        @if($methods->isEmpty())
            <div class="col-span-full text-center py-8 text-gray-500">
                <span class="material-symbols-outlined text-4xl text-gray-300 mb-2 block">credit_card</span>
                <p class="text-sm">{{ __t('admin.payment_methods.empty_desc') }}</p>
                <a href="{{ route('admin.payment-methods.create') }}" class="text-primary text-sm hover:underline mt-2 inline-block">{{ __t('admin.payment_methods.add_method') }}</a>
            </div>
        @endif
    </div>
</div>

{{-- Recent Transactions --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-5 border-b">
        <h2 class="font-bold text-lg"><i class="fas fa-list text-blue-600 ml-2"></i>{{ __t('admin.payments.recent_transactions') }}</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-xs">
                <tr>
                    <th class="px-4 py-3 text-right">{{ __t('admin.payments.transaction_id') }}</th>
                    <th class="px-4 py-3 text-right">{{ __t('admin.payments.order_number') }}</th>
                    <th class="px-4 py-3 text-right">{{ __t('admin.payments.method') }}</th>
                    <th class="px-4 py-3 text-right">{{ __t('admin.payments.amount') }}</th>
                    <th class="px-4 py-3 text-right">{{ __t('admin.payments.status') }}</th>
                    <th class="px-4 py-3 text-right">{{ __t('admin.payments.date') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ $payment->transaction_id ?? 'PMT-' . $payment->id }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.orders.show', $payment->order) }}" class="font-mono text-blue-600 hover:underline">
                                {{ $payment->order?->order_number ?? '—' }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-xs">{{ $payment->method ?? '—' }}</td>
                        <td class="px-4 py-3 font-bold">{{ number_format($payment->amount, 0) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded text-xs
                                @switch($payment->status)
                                    @case('paid') bg-green-100 text-green-700 @break
                                    @case('pending') bg-yellow-100 text-yellow-700 @break
                                    @case('failed') bg-red-100 text-red-700 @break
                                @endswitch">
                                @switch($payment->status)
                                    @case('paid') {{ __t('admin.payments.paid') }} @break
                                    @case('pending') {{ __t('admin.payments.pending') }} @break
                                    @case('failed') {{ __t('admin.payments.failed') }} @break
                                @endswitch
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-receipt text-3xl text-gray-300 mb-2"></i>
                            <p>{{ __t('admin.payments.no_transactions') }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
        <div class="p-4 border-t">{{ $payments->links() }}</div>
    @endif
</div>
@endsection