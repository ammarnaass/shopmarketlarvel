@extends('admin.layout')

@section('title', 'تعديل ' . $coupon->code)

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold">تعديل الكوبون</h1>
    <p class="text-on-surface-variant text-sm mt-1">
        <a href="{{ route('admin.coupons.index') }}" class="text-primary hover:underline">الكوبونات</a>
        <span class="mx-1">/</span>
        <span class="font-mono">{{ $coupon->code }}</span>
    </p>
</div>

<form method="POST" action="{{ route('admin.coupons.update', $coupon) }}">
    @csrf
    @method('PUT')
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5 max-w-3xl">
        <h2 class="font-bold text-lg mb-4"><span class="material-symbols-outlined text-primary ml-2">confirmation_number</span>معلومات الكوبون</h2>

        <div class="bg-blue-50 border border-blue-200 p-3 rounded-lg mb-4 text-sm">
            <span class="material-symbols-outlined text-primary ml-1">info</span>
            تم استخدام هذا الكوبون <strong>{{ $coupon->used_count }}</strong> مرة
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">كود الكوبون <span class="text-red-500">*</span></label>
                <input type="text" name="code" value="{{ old('code', $coupon->code) }}" required style="text-transform: uppercase" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 font-mono @error('code') border-red-500 @enderror">
                @error('code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">النوع <span class="text-red-500">*</span></label>
                <select name="type" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('type') border-red-500 @enderror">
                    <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>مبلغ ثابت</option>
                    <option value="percent" {{ old('type', $coupon->type) === 'percent' ? 'selected' : '' }}>نسبة مئوية %</option>
                </select>
                @error('type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">قيمة الخصم <span class="text-red-500">*</span></label>
                <input type="number" name="value" value="{{ old('value', $coupon->value) }}" required min="0" step="0.01" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('value') border-red-500 @enderror">
                @error('value')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">الحد الأدنى للطلب</label>
                <input type="number" name="min_order" value="{{ old('min_order', $coupon->min_order) }}" min="0" step="0.01" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('min_order') border-red-500 @enderror">
                @error('min_order')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">الحد الأقصى للخصم</label>
                <input type="number" name="max_discount" value="{{ old('max_discount', $coupon->max_discount) }}" min="0" step="0.01" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('max_discount') border-red-500 @enderror">
                @error('max_discount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">حد الاستخدام</label>
                <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('usage_limit') border-red-500 @enderror">
                <p class="text-xs text-on-surface-variant mt-1">اتركه فارغاً لاستخدام غير محدود</p>
                @error('usage_limit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">تاريخ الانتهاء</label>
                <input type="date" name="expiry_date" value="{{ old('expiry_date', $coupon->expiry_date ? \Carbon\Carbon::parse($coupon->expiry_date)->format('Y-m-d') : '') }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('expiry_date') border-red-500 @enderror">
                @error('expiry_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">الحالة <span class="text-red-500">*</span></label>
                <select name="status" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                    <option value="active" {{ old('status', $coupon->status) === 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ old('status', $coupon->status) === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                </select>
                @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div class="mt-6 flex items-center gap-3">
        <button type="submit" class="bg-primary hover:bg-primary text-white px-6 py-2.5 rounded-lg font-semibold flex items-center gap-2">
            <span class="material-symbols-outlined">save</span>تحديث الكوبون
        </button>
        <a href="{{ route('admin.coupons.index') }}" class="bg-gray-200 hover:bg-gray-300 text-on-surface px-6 py-2.5 rounded-lg font-semibold">إلغاء</a>
    </div>
</form>
@endsection
