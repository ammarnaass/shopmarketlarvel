@extends('admin.layout')

@section('title', __t('admin.payment_methods.page_title'))

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold">{{ __t('admin.payment_methods.page_title') }}</h1>
        <p class="text-on-surface-variant text-sm mt-1">{{ __t('admin.payment_methods.subtitle') }}</p>
    </div>
    <a href="{{ route('admin.payment-methods.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-white font-medium hover:bg-primary-container shadow-sm transition-all active:scale-95">
        <span class="material-symbols-outlined text-sm">add</span>
        {{ __t('admin.payment_methods.add_method') }}
    </a>
</div>

@if($methods->count() > 0)
<div class="space-y-3">
    @foreach($methods as $method)
    <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden {{ $method->is_active ? '' : 'opacity-60' }}">
        <div class="flex items-center gap-4 p-4">
            <div class="w-12 h-12 rounded-xl bg-{{ $method->color }}-100 text-{{ $method->color }}-600 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined">{{ $method->icon }}</span>
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <h3 class="font-bold text-sm truncate">{{ $method->name }}</h3>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-surface-container-high text-on-surface-variant">
                        {{ __t('admin.payment_methods.type_' . $method->type) }}
                    </span>
                </div>
                @if($method->description)
                    <p class="text-xs text-on-surface-variant truncate">{{ $method->description }}</p>
                @endif
                <div class="flex items-center gap-3 mt-1.5 text-xs text-on-surface-variant">
                    <span class="font-mono bg-surface-container-high px-1.5 py-0.5 rounded">{{ $method->code }}</span>
                    @if($method->fees_value > 0)
                        <span>
                            @if($method->fees_type === 'percent') {{ $method->fees_value }}% @else {{ number_format($method->fees_value, 0) }} @endif
                            {{ __t('admin.payment_methods.fees') }}
                        </span>
                    @else
                        <span>{{ __t('admin.payment_methods.no_fees') }}</span>
                    @endif
                    <span>#{{ $method->sort_order }}</span>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                {{-- Status badge --}}
                <span class="text-xs px-2 py-0.5 rounded-full {{ $method->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ $method->is_active ? __t('admin.payment_methods.active') : __t('admin.payment_methods.inactive') }}
                </span>

                {{-- Toggle active --}}
                <form method="POST" action="{{ route('admin.payment-methods.toggle', $method) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="p-1.5 rounded-lg transition {{ $method->is_active ? 'text-green-600 bg-green-50 hover:bg-green-100' : 'text-gray-400 bg-gray-50 hover:bg-gray-100' }}" title="{{ $method->is_active ? __t('admin.payment_methods.deactivate') : __t('admin.payment_methods.activate') }}">
                        <span class="material-symbols-outlined text-lg">{{ $method->is_active ? 'toggle_on' : 'toggle_off' }}</span>
                    </button>
                </form>

                {{-- Edit --}}
                <a href="{{ route('admin.payment-methods.edit', $method) }}" class="p-1.5 rounded-lg text-primary hover:bg-primary-container/10 transition" title="{{ __t('common.edit') }}">
                    <span class="material-symbols-outlined text-lg">edit</span>
                </a>

                {{-- Delete --}}
                <form method="POST" action="{{ route('admin.payment-methods.destroy', $method) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('{{ __t('admin.payment_methods.delete_confirm') }}')" class="p-1.5 rounded-lg text-error hover:bg-error-container/10 transition" title="{{ __t('common.delete') }}">
                        <span class="material-symbols-outlined text-lg">delete</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="bg-surface-container-lowest rounded-xl shadow-sm p-12 text-center">
    <span class="material-symbols-outlined text-6xl text-outline mb-3 block">credit_card</span>
    <h3 class="font-bold text-lg mb-2">{{ __t('admin.payment_methods.empty_title') }}</h3>
    <p class="text-on-surface-variant text-sm mb-6">{{ __t('admin.payment_methods.empty_desc') }}</p>
    <a href="{{ route('admin.payment-methods.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-white font-medium hover:bg-primary-container shadow-sm transition-all active:scale-95">
        <span class="material-symbols-outlined text-sm">add</span>
        {{ __t('admin.payment_methods.add_method') }}
    </a>
</div>
@endif
@endsection
