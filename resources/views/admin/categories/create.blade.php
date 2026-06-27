@extends('admin.layout')

@section('title', __t('admin.categories.add_new'))

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold">{{ __t('admin.categories.add_new') }}</h1>
    <p class="text-on-surface-variant text-sm mt-1">
        <a href="{{ route('admin.categories.index') }}" class="text-primary hover:underline">{{ __t('admin.categories.title') }}</a>
        <span class="mx-1">/</span>
        <span>{{ __t('common.create') }}</span>
    </p>
</div>

<form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5">
                <h2 class="font-bold text-lg mb-4"><span class="material-symbols-outlined text-primary ml-2">info</span>{{ __t('admin.categories.basic_info') }}</h2>

                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-1">{{ __t('admin.categories.name') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-1">{{ __t('admin.categories.icon') }}</label>
                    <input type="text" name="icon" value="{{ old('icon') }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('icon') border-red-500 @enderror" placeholder="{{ __t('admin.categories.icon_placeholder') }}">
                    <p class="text-xs text-on-surface-variant mt-1">{{ __t('admin.categories.icon_help') }}</p>
                    @error('icon')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-1">{{ __t('admin.categories.parent') }}</label>
                    <select name="parent_id" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('parent_id') border-red-500 @enderror">
                        <option value="">{{ __t('admin.categories.none') }}</option>
                        @foreach($parents as $p)
                            <option value="{{ $p->id }}" {{ old('parent_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-on-surface-variant mt-1">{{ __t('admin.categories.parent_help') }}</p>
                    @error('parent_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-1">{{ __t('admin.categories.description') }}</label>
                    <textarea name="description" rows="4" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5">
                <h2 class="font-bold text-lg mb-4"><span class="material-symbols-outlined text-primary ml-2">settings</span>{{ __t('admin.categories.settings') }}</h2>
                <div class="mb-3">
                    <label class="block text-sm font-semibold mb-1">{{ __t('admin.categories.order') }}</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('order') border-red-500 @enderror">
                    <p class="text-xs text-on-surface-variant mt-1">{{ __t('admin.categories.order_help') }}</p>
                    @error('order')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">{{ __t('common.status') }} <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>{{ __t('common.active') }}</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>{{ __t('common.inactive') }}</option>
                    </select>
                    @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5">
                <h2 class="font-bold text-lg mb-4"><span class="material-symbols-outlined text-primary ml-2">image</span>{{ __t('admin.categories.image') }}</h2>
                <input type="file" name="image" accept="image/*" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('image') border-red-500 @enderror">
                <p class="text-xs text-on-surface-variant mt-1">{{ __t('admin.categories.image_help') }}</p>
                @error('image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div class="mt-6 flex items-center gap-3">
        <button type="submit" class="bg-primary hover:bg-primary text-white px-6 py-2.5 rounded-lg font-semibold flex items-center gap-2">
            <span class="material-symbols-outlined">save</span>{{ __t('admin.categories.save') }}
        </button>
        <a href="{{ route('admin.categories.index') }}" class="bg-gray-200 hover:bg-gray-300 text-on-surface px-6 py-2.5 rounded-lg font-semibold">{{ __t('common.cancel') }}</a>
    </div>
</form>
@endsection
