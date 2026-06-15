@extends('admin.layout')

@section('title', 'الكوبونات')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold">الكوبونات</h1>
        <p class="text-on-surface-variant text-sm mt-1">إدارة كوبونات الخصم</p>
    </div>
    <a href="{{ route('admin.coupons.create') }}" class="bg-primary hover:bg-primary text-white px-4 py-2 rounded-lg flex items-center gap-2">
        <span class="material-symbols-outlined">add</span>
        إضافة كوبون
    </a>
</div>

<div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-surface-container-low text-on-surface-variant text-xs">
                <tr>
                    <th class="px-4 py-3 text-right">الكود</th>
                    <th class="px-4 py-3 text-right">النوع</th>
                    <th class="px-4 py-3 text-right">القيمة</th>
                    <th class="px-4 py-3 text-right">الحد الأدنى</th>
                    <th class="px-4 py-3 text-right">الاستخدام</th>
                    <th class="px-4 py-3 text-right">تاريخ الانتهاء</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right">إجراءات</th>
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
                                <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs">نسبة %</span>
                            @else
                                <span class="bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded text-xs">مبلغ ثابت</span>
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
                                {{ $coupon->status === 'active' ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-green-600 hover:text-green-800" title="تعديل">
                                    <span class="material-symbols-outlined">edit</span>
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الكوبون؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="حذف">
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
                            <p>لا توجد كوبونات</p>
                            <a href="{{ route('admin.coupons.create') }}" class="text-primary hover:underline text-sm mt-2 inline-block">إضافة أول كوبون</a>
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
