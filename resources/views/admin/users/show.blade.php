@extends('admin.layout')

@section('title', $user->name)

@section('content')
@php
    $ordersCount = $user->orders()->count();
    $totalSpent = $user->orders()->where('payment_status', 'paid')->sum('grand_total');
    $addressesCount = $user->addresses()->count();
    $memberDays = $user->created_at->diffInDays(now());
@endphp

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div class="flex items-center gap-4">
        @if($user->avatar)
            <img src="{{ asset('storage/' . $user->avatar) }}" class="w-16 h-16 rounded-full object-cover" alt="">
        @else
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 text-white flex items-center justify-center font-bold text-2xl">
                {{ mb_substr($user->name, 0, 1) }}
            </div>
        @endif
        <div>
            <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
            <p class="text-on-surface-variant text-sm">
                <a href="{{ route('admin.users.index') }}" class="text-primary hover:underline">العملاء</a>
                <span class="mx-1">/</span>
                <span>{{ $user->name }}</span>
            </p>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.users.edit', $user) }}" class="bg-primary hover:bg-primary text-white px-4 py-2 rounded-lg text-sm">
            <span class="material-symbols-outlined ml-1">edit</span>تعديل
        </a>
        @if($user->id !== auth()->id())
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                    <span class="material-symbols-outlined ml-1">delete</span>حذف
                </button>
            </form>
        @endif
    </div>
</div>

{{-- Quick badges --}}
<div class="mb-6 flex flex-wrap items-center gap-2">
    <span class="px-3 py-1 rounded text-sm
        @switch($user->role)
            @case('admin') bg-error-container text-on-error-container @break
            @case('manager') bg-blue-100 text-blue-700 @break
            @case('customer') bg-gray-100 text-gray-700 @break
        @endswitch">
        <span class="material-symbols-outlined ml-1">admin_panel_settings</span>
        @switch($user->role)
            @case('admin') مدير @break
            @case('manager') مساعد @break
            @case('customer') عميل @break
        @endswitch
    </span>
    <span class="px-3 py-1 rounded text-sm
        @switch($user->status ?? 'active')
            @case('active') bg-emerald-50 text-emerald-700 @break
            @case('inactive') bg-gray-100 text-gray-700 @break
            @case('banned') bg-error-container text-on-error-container @break
        @endswitch">
        @switch($user->status ?? 'active')
            @case('active') نشط @break
            @case('inactive') غير نشط @break
            @case('banned') محظور @break
        @endswitch
    </span>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        {{-- Info --}}
        <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5">
            <h2 class="font-bold text-lg mb-4"><span class="material-symbols-outlined text-primary ml-2">info</span>معلومات الحساب</h2>
            <dl class="grid md:grid-cols-2 gap-4 text-sm">
                <div><dt class="text-xs text-on-surface-variant">الاسم</dt><dd class="font-semibold">{{ $user->name }}</dd></div>
                <div><dt class="text-xs text-on-surface-variant">البريد</dt><dd>{{ $user->email }}</dd></div>
                <div><dt class="text-xs text-on-surface-variant">الهاتف</dt><dd>{{ $user->phone ?? '—' }}</dd></div>
                <div><dt class="text-xs text-on-surface-variant">الدولة</dt><dd>{{ $user->country_code ?? '—' }}</dd></div>
                <div><dt class="text-xs text-on-surface-variant">المحافظة</dt><dd>{{ $user->state_code ?? '—' }}</dd></div>
                <div><dt class="text-xs text-on-surface-variant">تاريخ التسجيل</dt><dd>{{ $user->created_at->format('Y-m-d H:i') }}</dd></div>
                @if($user->email_verified_at)
                    <div><dt class="text-xs text-on-surface-variant">البريد مُفعّل</dt><dd><span class="material-symbols-outlined text-green-500">check_circle</span> {{ $user->email_verified_at->format('Y-m-d') }}</dd></div>
                @endif
            </dl>
        </div>

        {{-- Orders --}}
        <div class="bg-surface-container-lowest rounded-xl shadow-sm">
            <div class="p-5 border-b">
                <h2 class="font-bold text-lg"><span class="material-symbols-outlined text-primary ml-2">shopping_bag</span>الطلبات ({{ $ordersCount }})</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-surface-container-low text-on-surface-variant text-xs">
                        <tr>
                            <th class="px-3 py-2 text-right">رقم الطلب</th>
                            <th class="px-3 py-2 text-right">المنتجات</th>
                            <th class="px-3 py-2 text-right">الإجمالي</th>
                            <th class="px-3 py-2 text-right">الحالة</th>
                            <th class="px-3 py-2 text-right">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->orders()->latest()->limit(10)->get() as $order)
                            <tr class="border-t hover:bg-surface-container-low">
                                <td class="px-3 py-2">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="font-mono text-primary hover:underline">{{ $order->order_number }}</a>
                                </td>
                                <td class="px-3 py-2">{{ $order->items->count() }}</td>
                                <td class="px-3 py-2 font-bold">{{ number_format($order->grand_total, 0) }}</td>
                                <td class="px-3 py-2">
                                    <span class="px-2 py-0.5 rounded text-xs
                                        @switch($order->status)
                                            @case('pending') bg-yellow-100 text-yellow-700 @break
                                            @case('confirmed') bg-blue-100 text-blue-700 @break
                                            @case('processing') bg-indigo-100 text-indigo-700 @break
                                            @case('shipped') bg-purple-100 text-purple-700 @break
                                            @case('delivered') bg-emerald-50 text-emerald-700 @break
                                            @case('cancelled') bg-error-container text-on-error-container @break
                                        @endswitch">{{ $order->status_name }}</span>
                                </td>
                                <td class="px-3 py-2 text-xs text-on-surface-variant">{{ $order->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-3 py-6 text-center text-on-surface-variant">لا توجد طلبات</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Addresses --}}
        <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5">
            <h2 class="font-bold text-lg mb-4"><span class="material-symbols-outlined text-primary ml-2">location_on</span>العناوين ({{ $addressesCount }})</h2>
            @forelse($user->addresses as $addr)
                <div class="border rounded-lg p-3 mb-3 {{ $addr->is_default ? 'border-blue-500 bg-blue-50' : '' }}">
                    <div class="flex items-center justify-between mb-2">
                        <div class="font-semibold">{{ $addr->name }} <span class="text-on-surface-variant text-sm font-normal">— {{ $addr->phone }}</span></div>
                        @if($addr->is_default)<span class="bg-primary text-white text-xs px-2 py-0.5 rounded">افتراضي</span>@endif
                    </div>
                    <p class="text-sm text-on-surface">{{ $addr->address }}</p>
                    <p class="text-sm text-on-surface-variant">{{ $addr->city }} - {{ $addr->state_name }} - {{ $addr->country_name }}</p>
                </div>
            @empty
                <p class="text-center text-on-surface-variant py-4">لا توجد عناوين</p>
            @endforelse
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5">
            <h2 class="font-bold text-lg mb-4"><span class="material-symbols-outlined text-primary ml-2">pie_chart</span>إحصائيات</h2>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded">
                    <dt class="text-on-surface-variant">إجمالي الطلبات</dt><dd class="font-bold text-primary text-lg">{{ $ordersCount }}</dd>
                </div>
                <div class="flex justify-between items-center p-3 bg-green-50 rounded">
                    <dt class="text-on-surface-variant">إجمالي الإنفاق</dt><dd class="font-bold text-green-600 text-lg">{{ number_format($totalSpent, 0) }}</dd>
                </div>
                <div class="flex justify-between items-center p-3 bg-purple-50 rounded">
                    <dt class="text-on-surface-variant">العناوين</dt><dd class="font-bold text-purple-600 text-lg">{{ $addressesCount }}</dd>
                </div>
                <div class="flex justify-between items-center p-3 bg-orange-50 rounded">
                    <dt class="text-on-surface-variant">عضو منذ</dt><dd class="font-bold text-orange-600 text-lg">{{ $memberDays }} يوم</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
