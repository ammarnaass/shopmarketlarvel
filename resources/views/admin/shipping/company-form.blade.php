@extends('admin.layout')

@section('title', $company ? 'تعديل ' . $company->name : 'إضافة شركة شحن')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold">{{ $company ? 'تعديل شركة شحن' : 'إضافة شركة شحن' }}</h1>
    <p class="text-gray-600 text-sm mt-1">
        <a href="{{ route('admin.shipping.index') }}" class="text-blue-600 hover:underline">الشحن</a>
        <span class="mx-1">/</span>
        <span>شركات</span>
    </p>
</div>

<form method="POST" action="{{ $company ? route('admin.shipping.company.update', $company) : route('admin.shipping.company.store') }}" class="max-w-3xl">
    @csrf
    @if($company)@method('PUT')@endif

    <div class="bg-white rounded-xl shadow-sm p-5">
        <h2 class="font-bold text-lg mb-4"><i class="fas fa-truck text-blue-600 ml-2"></i>معلومات الشركة</h2>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">اسم الشركة <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $company->name ?? '') }}" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">رابط التتبع <span class="text-red-500">*</span></label>
            <input type="url" name="tracking_url" value="{{ old('tracking_url', $company->tracking_url ?? '') }}" required
                   placeholder="https://example.com/track?id={TRACKING}"
                   class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 font-mono text-sm @error('tracking_url') border-red-500 @enderror">
            <p class="text-xs text-gray-500 mt-1">استخدم <code class="bg-gray-100 px-1 rounded">{TRACKING}</code> كرمز لرقم التتبع</p>
            @error('tracking_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">رابط الشعار (URL)</label>
            <input type="url" name="logo" value="{{ old('logo', $company->logo ?? '') }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('logo') border-red-500 @enderror">
            @error('logo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">رابط API</label>
            <input type="url" name="api_endpoint" value="{{ old('api_endpoint', $company->api_endpoint ?? '') }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('api_endpoint') border-red-500 @enderror">
            @error('api_endpoint')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="api_enabled" value="1" {{ old('api_enabled', $company->api_enabled ?? false) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded">
                <span class="text-sm font-semibold">تفعيل API للشحن التلقائي</span>
            </label>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">الحالة <span class="text-red-500">*</span></label>
            <select name="status" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                <option value="active" {{ old('status', $company->status ?? 'active') === 'active' ? 'selected' : '' }}>نشط</option>
                <option value="inactive" {{ old('status', $company->status ?? '') === 'inactive' ? 'selected' : '' }}>غير نشط</option>
            </select>
            @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="mt-6 flex items-center gap-3">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-semibold flex items-center gap-2">
            <i class="fas fa-save"></i>{{ $company ? 'تحديث' : 'حفظ' }}
        </button>
        <a href="{{ route('admin.shipping.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2.5 rounded-lg font-semibold">إلغاء</a>
    </div>
</form>
@endsection
