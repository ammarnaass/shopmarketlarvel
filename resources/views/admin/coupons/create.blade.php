@extends('admin.layout')

@section('title', __t('admin.coupons.create_title'))

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold">{{ __t('admin.coupons.create_new') }}</h1>
    <p class="text-on-surface-variant text-sm mt-1">
        <a href="{{ route('admin.coupons.index') }}" class="text-primary hover:underline">{{ __t('admin.coupons.page_title') }}</a>
        <span class="mx-1">/</span>
        <span>{{ __t('admin.common.new') }}</span>
    </p>
</div>

<form method="POST" action="{{ route('admin.coupons.store') }}">
    @csrf
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5 max-w-3xl">
        <h2 class="font-bold text-lg mb-4"><span class="material-symbols-outlined text-primary ml-2">confirmation_number</span>{{ __t('admin.coupons.coupon_info') }}</h2>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">{{ __t('admin.coupons.code') }} <span class="text-red-500">*</span></label>
                <input type="text" name="code" value="{{ old('code') }}" required style="text-transform: uppercase" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 font-mono @error('code') border-red-500 @enderror" placeholder="WELCOME10">
                @error('code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">{{ __t('admin.coupons.type') }} <span class="text-red-500">*</span></label>
                <select name="type" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('type') border-red-500 @enderror">
                    <option value="fixed" {{ old('type', 'fixed') === 'fixed' ? 'selected' : '' }}>{{ __t('admin.coupons.fixed_amount') }}</option>
                    <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>{{ __t('admin.coupons.percent') }}</option>
                </select>
                @error('type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">{{ __t('admin.coupons.discount_value') }} <span class="text-red-500">*</span></label>
                <input type="number" name="value" value="{{ old('value') }}" required min="0" step="0.01" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('value') border-red-500 @enderror">
                @error('value')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">{{ __t('admin.coupons.min_order') }}</label>
                <input type="number" name="min_order" value="{{ old('min_order') }}" min="0" step="0.01" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('min_order') border-red-500 @enderror">
                @error('min_order')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">{{ __t('admin.coupons.max_discount') }}</label>
                <input type="number" name="max_discount" value="{{ old('max_discount') }}" min="0" step="0.01" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('max_discount') border-red-500 @enderror">
                @error('max_discount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">{{ __t('admin.coupons.usage_limit') }}</label>
                <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" min="1" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('usage_limit') border-red-500 @enderror">
                <p class="text-xs text-on-surface-variant mt-1">{{ __t('admin.coupons.unlimited_hint') }}</p>
                @error('usage_limit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">{{ __t('admin.coupons.expiry_date') }}</label>
                <input type="date" name="expiry_date" value="{{ old('expiry_date') }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('expiry_date') border-red-500 @enderror">
                @error('expiry_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">{{ __t('admin.coupons.status') }} <span class="text-red-500">*</span></label>
                <select name="status" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                    <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>{{ __t('admin.common.active') }}</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>{{ __t('admin.common.inactive') }}</option>
                </select>
                @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div class="mt-6 flex items-center gap-3">
        <button type="submit" class="bg-primary hover:bg-primary text-white px-6 py-2.5 rounded-lg font-semibold flex items-center gap-2">
            <span class="material-symbols-outlined">save</span>{{ __t('admin.coupons.save') }}
        </button>
        <a href="{{ route('admin.coupons.index') }}" class="bg-gray-200 hover:bg-gray-300 text-on-surface px-6 py-2.5 rounded-lg font-semibold">{{ __t('admin.common.cancel') }}</a>
    </div>
</form>
@endsection
