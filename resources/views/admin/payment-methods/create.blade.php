@extends('admin.layout')

@section('title', __t('admin.payment_methods.add_method'))

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.payment-methods.index') }}" class="inline-flex items-center gap-1 text-sm text-on-surface-variant hover:text-primary transition">
        <span class="material-symbols-outlined text-sm">arrow_forward</span>
        {{ __t('admin.payment_methods.back_to_list') }}
    </a>
    <h1 class="text-3xl font-bold mt-2">{{ __t('admin.payment_methods.add_method') }}</h1>
</div>

<form method="POST" action="{{ route('admin.payment-methods.store') }}">
    @csrf

    {{-- Basic Info --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5 mb-6">
        <h2 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">info</span>
            {{ __t('admin.payment_methods.basic_info') }}
        </h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">{{ __t('admin.payment_methods.name') }} <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary @error('name') border-red-500 @enderror">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">{{ __t('admin.payment_methods.code') }} <span class="text-red-500">*</span></label>
                <input type="text" name="code" value="{{ old('code') }}" required class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary font-mono text-sm" placeholder="cod, bank_transfer, myfatoorah..." dir="ltr">
                @error('code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">{{ __t('admin.payment_methods.type') }} <span class="text-red-500">*</span></label>
                <select name="type" required class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary">
                    <option value="manual" {{ old('type') === 'manual' ? 'selected' : '' }}>{{ __t('admin.payment_methods.type_manual') }}</option>
                    <option value="gateway" {{ old('type') === 'gateway' ? 'selected' : '' }}>{{ __t('admin.payment_methods.type_gateway') }}</option>
                    <option value="wallet" {{ old('type') === 'wallet' ? 'selected' : '' }}>{{ __t('admin.payment_methods.type_wallet') }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">{{ __t('admin.payment_methods.sort_order') }}</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-1">{{ __t('admin.payment_methods.description') }}</label>
                <textarea name="description" rows="2" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary">{{ old('description') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Appearance --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5 mb-6">
        <h2 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">palette</span>
            {{ __t('admin.payment_methods.appearance') }}
        </h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">{{ __t('admin.payment_methods.icon') }} <span class="text-red-500">*</span></label>
                <input type="text" name="icon" value="{{ old('icon', 'payments') }}" required class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary font-mono text-sm" placeholder="payments, credit_card, account_balance..." dir="ltr">
                <p class="text-xs text-on-surface-variant mt-1">
                    <span class="material-symbols-outlined ml-1 text-xs">info</span>
                    {{ __t('admin.payment_methods.icon_hint') }}
                </p>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">{{ __t('admin.payment_methods.color') }} <span class="text-red-500">*</span></label>
                <select name="color" required class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary">
                    @foreach(['green','blue','purple','orange','red','indigo','teal','pink','cyan','yellow'] as $c)
                        <option value="{{ $c }}" {{ old('color') === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Fees --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5 mb-6">
        <h2 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">calculate</span>
            {{ __t('admin.payment_methods.fees_section') }}
        </h2>
        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">{{ __t('admin.payment_methods.fees_type') }} <span class="text-red-500">*</span></label>
                <select name="fees_type" required class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary">
                    <option value="fixed" {{ old('fees_type') === 'fixed' ? 'selected' : '' }}>{{ __t('admin.payment_methods.fees_fixed') }}</option>
                    <option value="percent" {{ old('fees_type') === 'percent' ? 'selected' : '' }}>{{ __t('admin.payment_methods.fees_percent') }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">{{ __t('admin.payment_methods.fees_value') }}</label>
                <input type="number" name="fees_value" value="{{ old('fees_value', 0) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary">
            </div>
            <div></div>
            <div>
                <label class="block text-sm font-semibold mb-1">{{ __t('admin.payment_methods.min_order') }}</label>
                <input type="number" name="min_order" value="{{ old('min_order') }}" min="0" step="0.01" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary" placeholder="0">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">{{ __t('admin.payment_methods.max_order') }}</label>
                <input type="number" name="max_order" value="{{ old('max_order') }}" min="0" step="0.01" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary" placeholder="{{ __t('admin.payment_methods.no_limit') }}">
            </div>
        </div>
    </div>

    {{-- Instructions --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5 mb-6">
        <h2 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">description</span>
            {{ __t('admin.payment_methods.instructions_section') }}
        </h2>
        <div>
            <label class="block text-sm font-semibold mb-1">{{ __t('admin.payment_methods.instructions') }}</label>
            <textarea name="instructions" rows="4" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary" placeholder="{{ __t('admin.payment_methods.instructions_placeholder') }}">{{ old('instructions') }}</textarea>
            <p class="text-xs text-on-surface-variant mt-1">{{ __t('admin.payment_methods.instructions_hint') }}</p>
        </div>
    </div>

    {{-- Status --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5 mb-6">
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }} class="w-5 h-5 text-primary rounded">
            <div>
                <span class="text-sm font-semibold">{{ __t('admin.payment_methods.is_active') }}</span>
                <p class="text-xs text-on-surface-variant">{{ __t('admin.payment_methods.is_active_hint') }}</p>
            </div>
        </label>
    </div>

    {{-- Actions --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5 flex items-center justify-between">
        <a href="{{ route('admin.payment-methods.index') }}" class="text-on-surface-variant hover:text-primary transition text-sm">{{ __t('common.cancel') }}</a>
        <button type="submit" class="bg-primary hover:bg-primary text-white px-6 py-2.5 rounded-lg font-semibold flex items-center gap-2 transition active:scale-95">
            <span class="material-symbols-outlined">save</span>
            {{ __t('admin.payment_methods.add_method') }}
        </button>
    </div>
</form>
@endsection
