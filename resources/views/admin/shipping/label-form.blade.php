@extends('admin.layout')

@section('title', __t('admin.shipping.label_form_title'))

@section('content')
{{-- Breadcrumb --}}
<nav class="flex mb-6 text-sm text-gray-500">
    <a href="{{ route('admin.shipping.index', ['tab' => 'labels']) }}" class="hover:text-blue-600">{{ __t('admin.shipping.title') }}</a>
    <i class="fas fa-chevron-left mx-2 text-xs mt-1"></i>
    <span class="text-gray-800">{{ __t('admin.shipping.create_label') }}</span>
</nav>

<div class="mb-6 bg-gradient-to-l from-orange-600 to-red-600 rounded-xl p-5 text-white">
    <h1 class="text-2xl font-bold flex items-center gap-3">
        <i class="fas fa-file-invoice text-3xl"></i> إنشاء بوليصة شحن جديدة
    </h1>
</div>

<form action="{{ route('admin.shipping.label.store') }}" method="POST">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __t('admin.shipping.order') }} *</label>
                <select name="order_id" class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500" required>
                    <option value="">- {{ __t('admin.shipping.select_order') }} -</option>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>#{{ $order->order_number }} - {{ number_format($order->grand_total, 2) }} {{ currentCurrencySymbol() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __t('admin.shipping.carrier') }} *</label>
                <select name="carrier_id" class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500" required>
                    @foreach($carriers as $carrier)
                        <option value="{{ $carrier->id }}" {{ old('carrier_id') == $carrier->id ? 'selected' : '' }}>{{ $carrier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __t('admin.shipping.weight_kg') }}</label>
                    <input type="number" name="weight" step="0.01" min="0" value="{{ old('weight') }}" class="w-full border rounded-lg px-4 py-2.5" placeholder="0.5">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __t('admin.shipping.cost') }} ({{ currentCurrencySymbol() }}) *</label>
                    <input type="number" name="cost" step="0.01" min="0" value="{{ old('cost') }}" class="w-full border rounded-lg px-4 py-2.5" required placeholder="25.00">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __t('admin.shipping.tracking_number_optional') }}</label>
                <input type="text" name="tracking_number" value="{{ old('tracking_number') }}" class="w-full border rounded-lg px-4 py-2.5" placeholder="سيتم إنشاؤه تلقائياً">
                <p class="text-xs text-gray-400 mt-1">{{ __t('admin.shipping.auto_generate_hint') }}</p>
            </div>
        </div>
        <div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-semibold">
                    <i class="fas fa-save ml-1"></i> إنشاء البوليصة
                </button>
                <a href="{{ route('admin.shipping.index', ['tab' => 'labels']) }}" class="block text-center mt-3 text-sm text-gray-500 hover:text-gray-700">{{ __t('admin.shipping.cancel') }}</a>
            </div>
        </div>
    </div>
</form>
@endsection
