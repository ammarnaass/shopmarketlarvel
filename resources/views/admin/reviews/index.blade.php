@extends('admin.layout')

@section('title', 'التقييمات')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold">التقييمات</h1>
        <p class="text-on-surface-variant text-sm mt-1">إدارة تقييمات العملاء للمنتجات</p>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-6">
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-4">
        <p class="text-on-surface-variant text-xs">الإجمالي</p>
        <p class="text-2xl font-bold mt-1">{{ number_format($stats['total']) }}</p>
    </div>
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-4">
        <p class="text-on-surface-variant text-xs">في الانتظار</p>
        <p class="text-2xl font-bold mt-1 text-yellow-600">{{ number_format($stats['pending']) }}</p>
    </div>
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-4">
        <p class="text-on-surface-variant text-xs">معتمد</p>
        <p class="text-2xl font-bold mt-1 text-green-600">{{ number_format($stats['approved']) }}</p>
    </div>
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-4">
        <p class="text-on-surface-variant text-xs">مرفوض</p>
        <p class="text-2xl font-bold mt-1 text-red-600">{{ number_format($stats['rejected']) }}</p>
    </div>
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-4">
        <p class="text-on-surface-variant text-xs">متوسط التقييم</p>
        <p class="text-2xl font-bold mt-1 text-orange-600">
            <span class="material-symbols-outlined text-yellow-500">star</span> {{ $stats['avg_rating'] }}
        </p>
    </div>
</div>

{{-- Filters --}}
<div class="bg-surface-container-lowest rounded-xl shadow-sm p-4 mb-6">
    <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-xs font-semibold mb-1 text-on-surface-variant">الحالة</label>
            <select name="status" class="px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="">الكل</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>في الانتظار</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>معتمد</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>مرفوض</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold mb-1 text-on-surface-variant">التقييم</label>
            <select name="rating" class="px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="">الكل</option>
                @for($i=5; $i>=1; $i--)
                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} نجوم</option>
                @endfor
            </select>
        </div>
        <button type="submit" class="bg-primary hover:bg-primary text-white px-4 py-2 rounded-lg text-sm">
            <span class="material-symbols-outlined ml-1">filter_list</span>تطبيق
        </button>
    </form>
</div>

<div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-surface-container-low text-on-surface-variant text-xs">
                <tr>
                    <th class="px-4 py-3 text-right">العميل</th>
                    <th class="px-4 py-3 text-right">المنتج</th>
                    <th class="px-4 py-3 text-right">التقييم</th>
                    <th class="px-4 py-3 text-right">التعليق</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right">التاريخ</th>
                    <th class="px-4 py-3 text-right">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr class="border-t hover:bg-surface-container-low">
                        <td class="px-4 py-3">
                            <div class="font-semibold">{{ $review->user?->name ?? 'مستخدم محذوف' }}</div>
                            <div class="text-xs text-on-surface-variant">{{ $review->user?->email ?? '' }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.products.show', $review->product) }}" class="text-primary hover:underline">
                                {{ Str::limit($review->product?->name ?? 'منتج محذوف', 30) }}
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex text-yellow-500 text-sm">
                                @for($i=1; $i<=5; $i++)
                                    <span class="material-symbols-outlined {{ $i <= $review->rating ? '' : 'text-gray-300' }}">star</span>
                                @endfor
                            </div>
                        </td>
                        <td class="px-4 py-3 text-on-surface-variant max-w-xs">
                            <div class="line-clamp-2">{{ $review->comment ?: '—' }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded text-xs
                                @switch($review->status)
                                    @case('pending') bg-yellow-100 text-yellow-700 @break
                                    @case('approved') bg-emerald-50 text-emerald-700 @break
                                    @case('rejected') bg-error-container text-on-error-container @break
                                @endswitch">
                                @switch($review->status)
                                    @case('pending') في الانتظار @break
                                    @case('approved') معتمد @break
                                    @case('rejected') مرفوض @break
                                @endswitch
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-on-surface-variant">{{ $review->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <form method="POST" action="{{ route('admin.reviews.updateStatus', $review) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="text-green-600 hover:text-green-800 p-1" title="اعتماد">
                                        <span class="material-symbols-outlined">check</span>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.reviews.updateStatus', $review) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="text-orange-600 hover:text-orange-800 p-1" title="رفض">
                                        <span class="material-symbols-outlined">block</span>
                                    </button>
                                </form>
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline" onsubmit="return confirm('حذف التقييم نهائياً؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 p-1" title="حذف">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl text-gray-300 mb-3">star</span>
                            <p>لا توجد تقييمات</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($reviews->hasPages())
        <div class="p-4 border-t">{{ $reviews->links() }}</div>
    @endif
</div>
@endsection
