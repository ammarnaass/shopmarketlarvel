@extends('admin.layout')

@section('title', __t('admin.users.edit_title') . ' ' . $user->name)

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold">{{ __t('admin.users.edit_user') }}</h1>
    <p class="text-on-surface-variant text-sm mt-1">
        <a href="{{ route('admin.users.index') }}" class="text-primary hover:underline">{{ __t('admin.users.page_title') }}</a>
        <span class="mx-1">/</span>
        <span>{{ $user->name }}</span>
    </p>
</div>

<form method="POST" action="{{ route('admin.users.update', $user) }}">
    @csrf
    @method('PUT')
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5">
                <h2 class="font-bold text-lg mb-4"><span class="material-symbols-outlined text-primary ml-2">person</span>{{ __t('admin.users.basic_info') }}</h2>

                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-1">{{ __t('admin.users.name') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">{{ __t('admin.users.email') }} <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">{{ __t('admin.users.phone') }}</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror">
                        @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5">
                <h2 class="font-bold text-lg mb-4"><span class="material-symbols-outlined text-primary ml-2">lock</span>{{ __t('admin.users.change_password') }}</h2>
                <div class="bg-yellow-50 border border-yellow-200 p-3 rounded-lg mb-4 text-sm text-yellow-700">
                    <span class="material-symbols-outlined ml-1">info</span>
                    {{ __t('admin.users.keep_password_hint') }}
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">{{ __t('admin.users.new_password') }}</label>
                        <input type="password" name="password" minlength="6" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">{{ __t('admin.users.confirm_password') }}</label>
                        <input type="password" name="password_confirmation" minlength="6" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5">
                <h2 class="font-bold text-lg mb-4"><span class="material-symbols-outlined text-primary ml-2">admin_panel_settings</span>{{ __t('admin.users.permissions') }}</h2>
                <div class="mb-3">
                    <label class="block text-sm font-semibold mb-1">{{ __t('admin.users.role') }} <span class="text-red-500">*</span></label>
                    <select name="role" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('role') border-red-500 @enderror">
                        <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>{{ __t('admin.users.customer') }}</option>
                        <option value="manager" {{ old('role', $user->role) === 'manager' ? 'selected' : '' }}>{{ __t('admin.users.manager') }}</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>{{ __t('admin.users.admin') }}</option>
                    </select>
                    @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">{{ __t('admin.users.status') }} <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                        <option value="active" {{ old('status', $user->status ?? 'active') === 'active' ? 'selected' : '' }}>{{ __t('admin.common.active') }}</option>
                        <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>{{ __t('admin.common.inactive') }}</option>
                        <option value="banned" {{ old('status', $user->status) === 'banned' ? 'selected' : '' }}>{{ __t('admin.users.banned') }}</option>
                    </select>
                    @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 flex items-center gap-3">
        <button type="submit" class="bg-primary hover:bg-primary text-white px-6 py-2.5 rounded-lg font-semibold flex items-center gap-2">
            <span class="material-symbols-outlined">save</span>{{ __t('admin.users.update') }}
        </button>
        <a href="{{ route('admin.users.show', $user) }}" class="bg-gray-200 hover:bg-gray-300 text-on-surface px-6 py-2.5 rounded-lg font-semibold">{{ __t('admin.common.cancel') }}</a>
    </div>
</form>
@endsection
