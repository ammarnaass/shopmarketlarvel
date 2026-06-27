@extends('admin.layout')

@section('title', __t('admin.coupons.page_title'))

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold">{{ __t('admin.coupons.page_title') }}</h1>
        <p class="text-on-surface-variant text-sm mt-1">{{ __t('admin.coupons.manage') }}</p>
    </div>
    <a href="{{ route('admin.coupons.create') }}" class="bg-primary hover:bg-primary text-white px-4 py-2 rounded-lg flex items-center gap-2">
        <span class="material-symbols-outlined">add</span>
        {{ __t('admin.coupons.add') }}
    </a>
</div>

<div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-surface-container-low text-on-surface-variant text-xs">
                <tr>
                    <th class="px-4 py-3 text-right">{{ __t('admin.coupons.code') }}</th>
                    <th class="px-4 py-3 text-right">{{ __t('admin.coupons.type') }}</th>
                    <th class="px-4 py-3 text-right">{{ __t('admin.coupons.value') }}</th>
                    <th class="px-4 py-3 text-right">{{ __t('admin.coupons.min_order') }}</th>
                    <th class="px-4 py-3 text-right">{{ __t('admin.coupons.usage') }}</th>
                    <th class="px-4 py-3 text-right">{{ __t('admin.coupons.expiry_date') }}</th>
                    <th class="px-4 py-3 text-right">{{ __t('admin.coupons.status') }}</th>
                    <th class="px-4 py-3 text-right">{{ __t('admin.common.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                    <tr class="border-t hover:bg-surface-container-low">
                        <td class="px-4 py-3">
                            <span class="font-mono font-bold text-sm bg-pink-100 text-pink-700 px-2 py-1 rounded">{{ $coupon->code }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if($coupon->type === 'percent')
                                <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs">{{ __t('admin.coupons.percent') }}</span>
                            @else
                                <span class="bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded text-xs">{{ __t('admin.coupons.fixed_amount') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-bold">
                            @if($coupon->type === 'percent'){{ $coupon->value }}%@else{{ number_format($coupon->value, 0) }}@endif
                        </td>
                        <td class="px-4 py-3 text-on-surface-variant">
                            {{ $coupon->min_order ? number_format($coupon->min_order, 0) : '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-semibold">{{ $coupon->used_count }}</span>
                            <span class="text-gray-400">/ {{ $coupon->usage_limit ?? '∞' }}</span>
                        </td>
                        <td class="px-4 py-3 text-xs">
                            @if($coupon->expiry_date)
                                {{ \Carbon\Carbon::parse($coupon->expiry_date)->format('Y-m-d') }}
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs {{ $coupon->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $coupon->status === 'active' ? __t('admin.common.active') : __t('admin.common.inactive') }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-green-600 hover:text-green-800" title="{{ __t('admin.common.edit') }}">
                                    <span class="material-symbols-outlined">edit</span>
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline" onsubmit="return confirm('{{ __t('admin.coupons.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="{{ __t('admin.common.delete') }}">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl text-gray-300 mb-3">confirmation_number</span>
                            <p>{{ __t('admin.coupons.no_coupons') }}</p>
                            <a href="{{ route('admin.coupons.create') }}" class="text-primary hover:underline text-sm mt-2 inline-block">{{ __t('admin.coupons.add_first') }}</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($coupons->hasPages())
        <div class="p-4 border-t">{{ $coupons->links() }}</div>
    @endif
</div>
@endsection
