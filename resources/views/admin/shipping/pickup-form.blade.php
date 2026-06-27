@extends('admin.layout')

@section('title', $pickup ? 'تعديل ' . $pickup->name : 'إضافة مكتب استلام')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold">{{ $pickup ? 'تعديل مكتب استلام' : 'إضافة مكتب استلام' }}</h1>
    <p class="text-gray-600 text-sm mt-1">
        <a href="{{ route('admin.shipping.index') }}" class="text-blue-600 hover:underline">الشحن</a>
        <span class="mx-1">/</span>
        <span>مكاتب الاستلام</span>
    </p>
</div>

<form method="POST" action="{{ $pickup ? route('admin.shipping.pickup.update', $pickup) : route('admin.shipping.pickup.store') }}" class="max-w-3xl">
    @csrf
    @if($pickup)@method('PUT')@endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-5 md:col-span-2">
            <h2 class="font-bold text-lg mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">storefront</span>
                المعلومات الأساسية
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">اسم المكتب <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $pickup->name ?? '') }}" required
                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">شركة الشحن <span class="text-red-500">*</span></label>
                    <select name="carrier_id" required
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('carrier_id') border-red-500 @enderror">
                        <option value="">— اختر —</option>
                        @foreach($carriers as $carrier)
                            <option value="{{ $carrier->id }}" {{ old('carrier_id', $pickup->carrier_id ?? '') == $carrier->id ? 'selected' : '' }}>{{ $carrier->name }}</option>
                        @endforeach
                    </select>
                    @error('carrier_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-bold text-lg mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">location_on</span>
                العنوان والموقع
            </h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">العنوان <span class="text-red-500">*</span></label>
                    <textarea name="address" required rows="2"
                              class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror">{{ old('address', $pickup->address ?? '') }}</textarea>
                    @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">المدينة <span class="text-red-500">*</span></label>
                    <input type="text" name="city" value="{{ old('city', $pickup->city ?? '') }}" required
                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('city') border-red-500 @enderror">
                    @error('city')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">الولاية / المحافظة</label>
                    <input type="text" name="state" value="{{ old('state', $pickup->state ?? '') }}"
                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('state') border-red-500 @enderror">
                    @error('state')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">الدولة <span class="text-red-500">*</span></label>
                    <select name="country_code" required
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('country_code') border-red-500 @enderror">
                        <option value="">— اختر —</option>
                        @foreach(config('ecommerce.countries', []) as $code => $info)
                            <option value="{{ $code }}" {{ old('country_code', $pickup->country_code ?? '') === $code ? 'selected' : '' }}>{{ $info['flag'] ?? '' }} {{ $info['name'] }}</option>
                        @endforeach
                    </select>
                    @error('country_code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold mb-1">خط العرض</label>
                        <input type="text" name="latitude" value="{{ old('latitude', $pickup->latitude ?? '') }}"
                               class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="15.5007">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">خط الطول</label>
                        <input type="text" name="longitude" value="{{ old('longitude', $pickup->longitude ?? '') }}"
                               class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="32.5599">
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-bold text-lg mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">call</span>
                التواصل
            </h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">رقم الهاتف</label>
                    <input type="text" name="phone" value="{{ old('phone', $pickup->phone ?? '') }}"
                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror"
                           placeholder="+249912345678">
                    @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email', $pickup->email ?? '') }}"
                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                           placeholder="office@example.com">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">ساعات العمل</label>
                    <div class="space-y-1">
                        @php
                            $wh = old('working_hours', $pickup->working_hours ?? []);
                            $days = ['saturday' => 'السبت', 'sunday' => 'الأحد', 'monday' => 'الإثنين', 'tuesday' => 'الثلاثاء', 'wednesday' => 'الأربعاء', 'thursday' => 'الخميس', 'friday' => 'الجمعة'];
                        @endphp
                        @foreach($days as $key => $dayLabel)
                        <div class="flex items-center gap-2 text-sm">
                            <span class="w-20 font-semibold text-gray-600">{{ $dayLabel }}</span>
                            <input type="text" name="working_hours[{{ $key }}]" value="{{ is_array($wh) ? ($wh[$key] ?? '') : '' }}"
                                   class="flex-1 px-2 py-1 border rounded focus:ring-1 focus:ring-blue-500 text-xs"
                                   placeholder="9:00 - 17:00">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5 mt-6">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $pickup->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 text-primary rounded">
            <span class="text-sm font-semibold">مكتب نشط</span>
        </label>
    </div>

    <div class="mt-6 flex items-center gap-3">
        <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg font-semibold flex items-center gap-2 hover:bg-primary-container transition-all shadow-sm active:scale-95">
            <span class="material-symbols-outlined text-sm">save</span>
            {{ $pickup ? 'تحديث' : 'حفظ' }}
        </button>
        <a href="{{ route('admin.shipping.index', ['tab' => 'pickups']) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2.5 rounded-lg font-semibold">إلغاء</a>
    </div>
</form>
@endsection