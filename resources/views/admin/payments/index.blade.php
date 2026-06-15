@extends('admin.layout')

@section('title', 'المدفوعات')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold">المدفوعات</h1>
        <p class="text-gray-600 text-sm mt-1">إدارة طرق الدفع والمعاملات المالية</p>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-5 rounded-xl shadow-sm">
        <p class="text-green-100 text-sm">إجمالي الإيرادات</p>
        <p class="text-3xl font-bold mt-1">{{ number_format($stats['total_revenue'], 0) }}</p>
        <p class="text-xs text-green-100 mt-1"><i class="fas fa-arrow-up ml-1"></i>من المدفوعات الإلكترونية</p>
    </div>
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-5 rounded-xl shadow-sm">
        <p class="text-blue-100 text-sm">إيرادات الدفع عند الاستلام</p>
        <p class="text-3xl font-bold mt-1">{{ number_format($stats['cod_revenue'], 0) }}</p>
        <p class="text-xs text-blue-100 mt-1"><i class="fas fa-money-bill-wave ml-1"></i>من الطلبات المُسلّمة</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-gray-500 text-sm">إجمالي المعاملات</p>
        <p class="text-3xl font-bold mt-1">{{ number_format($stats['total']) }}</p>
        <div class="flex gap-3 mt-2 text-xs">
            <span class="text-green-600"><i class="fas fa-circle text-[8px]"></i> {{ $stats['paid'] }} مدفوع</span>
            <span class="text-yellow-600"><i class="fas fa-circle text-[8px]"></i> {{ $stats['pending'] }} معلق</span>
            <span class="text-red-600"><i class="fas fa-circle text-[8px]"></i> {{ $stats['failed'] }} فاشل</span>
        </div>
    </div>
</div>

{{-- Payment Methods --}}
<div class="bg-white rounded-xl shadow-sm p-5 mb-6">
    <h2 class="font-bold text-lg mb-4"><i class="fas fa-credit-card text-blue-600 ml-2"></i>طرق الدفع المتاحة</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($methods as $key => $method)
            <div class="border-2 {{ $method['active'] ? 'border-'.$method['color'].'-500 bg-'.$method['color'].'-50' : 'border-gray-200' }} rounded-xl p-4 relative">
                @if($method['active'])
                    <span class="absolute top-2 left-2 bg-{{ $method['color'] }}-600 text-white text-xs px-2 py-0.5 rounded-full">
                        <i class="fas fa-check"></i> مفعّل
                    </span>
                @else
                    <span class="absolute top-2 left-2 bg-gray-200 text-gray-600 text-xs px-2 py-0.5 rounded-full">
                        قريباً
                    </span>
                @endif
                <div class="w-12 h-12 rounded-lg bg-{{ $method['color'] }}-100 text-{{ $method['color'] }}-600 flex items-center justify-center text-2xl mb-3">
                    <i class="fas {{ $method['icon'] }}"></i>
                </div>
                <h3 class="font-bold">{{ $method['name'] }}</h3>
                <p class="text-xs text-gray-600 mt-1">{{ $method['description'] }}</p>
                @if(!$method['active'])
                    <button class="mt-3 text-xs text-blue-600 hover:underline">طلب التفعيل</button>
                @endif
            </div>
        @endforeach
    </div>
</div>

{{-- Recent Transactions --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-5 border-b">
        <h2 class="font-bold text-lg"><i class="fas fa-list text-blue-600 ml-2"></i>أحدث المعاملات</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-xs">
                <tr>
                    <th class="px-4 py-3 text-right">رقم المعاملة</th>
                    <th class="px-4 py-3 text-right">رقم الطلب</th>
                    <th class="px-4 py-3 text-right">الطريقة</th>
                    <th class="px-4 py-3 text-right">المبلغ</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right">التاريخ</th>
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
                                    @case('paid') مدفوع @break
                                    @case('pending') معلق @break
                                    @case('failed') فاشل @break
                                @endswitch
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-receipt text-3xl text-gray-300 mb-2"></i>
                            <p>لا توجد معاملات بعد</p>
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
