@extends('admin.layout')

@section('title', $page ? __t('admin.pages.edit_page') . ': ' . $page->title : __t('admin.pages.add_new'))

@section('content')
<nav class="flex mb-6 text-sm text-on-surface-variant">
    <a href="{{ route('admin.pages.index') }}" class="hover:text-primary">{{ __t('admin.pages.title') }}</a>
    <span class="material-symbols-outlined mx-2 text-xs mt-1">chevron_right</span>
    <span class="text-on-surface">{{ $page ? __t('common.edit') : __t('common.create') }}</span>
</nav>

<div class="mb-6 bg-gradient-to-l from-blue-600 to-indigo-600 rounded-xl p-5 text-white">
    <h1 class="text-2xl font-bold flex items-center gap-3">
        <span class="material-symbols-outlined text-3xl">description</span>
        {{ $page ? __t('admin.pages.edit_page') . ': ' . $page->title : __t('admin.pages.add_new') }}
    </h1>
</div>

<form method="POST" action="{{ $page ? route('admin.pages.update', $page) : route('admin.pages.store') }}" class="max-w-4xl">
    @csrf
    @if($page) @method('PUT') @endif

    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1">{{ __t('admin.pages.title_field') }} *</label>
                <input type="text" name="title" value="{{ old('title', $page->title ?? '') }}" required
                       class="w-full border rounded-lg px-4 py-2.5 @error('title') border-red-500 @enderror">
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1">{{ __t('admin.pages.slug') }} *</label>
                <div class="flex items-center">
                    <span class="text-on-surface-variant text-sm ml-2">/page/</span>
                    <input type="text" name="slug" value="{{ old('slug', $page->slug ?? '') }}" required
                           class="flex-1 border rounded-lg px-4 py-2.5 font-mono text-sm @error('slug') border-red-500 @enderror">
                </div>
                @error('slug')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-on-surface mb-1">{{ __t('admin.pages.content') }}</label>
            <textarea name="content" rows="15"
                      class="w-full border rounded-lg px-4 py-2.5 font-mono text-sm">{{ old('content', $page->content ?? '') }}</textarea>
            <p class="text-gray-400 text-xs mt-1">{{ __t('admin.pages.supports_markdown') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1">{{ __t('admin.pages.seo_title') }}</label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title ?? '') }}"
                       class="w-full border rounded-lg px-4 py-2.5 text-sm" placeholder="{{ __t('admin.pages.seo_title_placeholder') }}">
            </div>
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-1">{{ __t('admin.pages.seo_description') }}</label>
                <textarea name="meta_description" rows="2"
                          class="w-full border rounded-lg px-4 py-2.5 text-sm">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1"
                       {{ old('is_active', $page->is_active ?? true) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-primary">
                <span class="text-sm font-semibold text-on-surface">{{ __t('admin.pages.published') }}</span>
            </label>
            <div class="flex items-center gap-2">
                <label class="text-sm font-semibold text-on-surface">{{ __t('admin.pages.sort_order') }}:</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $page->sort_order ?? 0) }}" min="0"
                       class="w-20 border rounded-lg px-3 py-2 text-sm text-center">
            </div>
        </div>

        <div class="flex gap-3 pt-4 border-t">
            <button type="submit" class="bg-primary hover:bg-primary text-white px-6 py-2.5 rounded-lg font-semibold text-sm">
                <span class="material-symbols-outlined ml-1">save</span> {{ $page ? __t('common.update') : __t('admin.pages.save') }}
            </button>
            <a href="{{ route('admin.pages.index') }}" class="bg-surface-container-low hover:bg-surface-container-low text-on-surface px-6 py-2.5 rounded-lg text-sm">
                {{ __t('common.cancel') }}
            </a>
        </div>
    </div>
</form>
@endsection