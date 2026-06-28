@extends('admin.layout')

@section('title', __t('admin.slider.page_title'))

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold">{{ __t('admin.slider.page_title') }}</h1>
        <p class="text-on-surface-variant text-sm mt-1">{{ __t('admin.slider.subtitle') }}</p>
    </div>
    <a href="{{ route('admin.slider.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-white font-medium hover:bg-primary-container shadow-sm transition-all active:scale-95">
        <span class="material-symbols-outlined text-sm">add</span>
        {{ __t('admin.slider.add_slide') }}
    </a>
</div>

@if($slides->count() > 0)
<div class="space-y-4">
    @foreach($slides as $slide)
    <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden {{ $slide->is_active ? '' : 'opacity-60' }}">
        <div class="flex items-center gap-4 p-4">
            {{-- Image --}}
            <div class="w-24 h-16 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0 border border-outline-variant">
                @if($slide->image)
                    <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-gray-300 text-2xl">image</span>
                    </div>
                @endif
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    @if($slide->badge)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-primary-container text-on-primary-container">{{ $slide->badge }}</span>
                    @endif
                    <h3 class="font-bold text-sm truncate">{{ $slide->title }}</h3>
                </div>
                @if($slide->subtitle)
                    <p class="text-xs text-on-surface-variant truncate">{{ $slide->subtitle }}</p>
                @endif
                @if($slide->link)
                    <p class="text-xs text-primary truncate mt-0.5" dir="ltr">{{ $slide->link }}</p>
                @endif
            </div>

            {{-- Meta --}}
            <div class="flex items-center gap-2 flex-shrink-0">
                <span class="text-xs text-on-surface-variant">#{{ $slide->sort_order }}</span>

                {{-- Toggle active --}}
                <form method="POST" action="{{ route('admin.slider.toggle', $slide) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="p-1.5 rounded-lg transition {{ $slide->is_active ? 'text-green-600 bg-green-50 hover:bg-green-100' : 'text-gray-400 bg-gray-50 hover:bg-gray-100' }}" title="{{ $slide->is_active ? __t('admin.slider.deactivate') : __t('admin.slider.activate') }}">
                        <span class="material-symbols-outlined text-lg">{{ $slide->is_active ? 'toggle_on' : 'toggle_off' }}</span>
                    </button>
                </form>

                {{-- Edit --}}
                <a href="{{ route('admin.slider.edit', $slide) }}" class="p-1.5 rounded-lg text-primary hover:bg-primary-container/10 transition" title="{{ __t('common.edit') }}">
                    <span class="material-symbols-outlined text-lg">edit</span>
                </a>

                {{-- Delete --}}
                <form method="POST" action="{{ route('admin.slider.destroy', $slide) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('{{ __t('admin.slider.delete_confirm') }}')" class="p-1.5 rounded-lg text-error hover:bg-error-container/10 transition" title="{{ __t('common.delete') }}">
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
    <span class="material-symbols-outlined text-6xl text-outline mb-3 block">slideshow</span>
    <h3 class="font-bold text-lg mb-2">{{ __t('admin.slider.empty_title') }}</h3>
    <p class="text-on-surface-variant text-sm mb-6">{{ __t('admin.slider.empty_desc') }}</p>
    <a href="{{ route('admin.slider.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-white font-medium hover:bg-primary-container shadow-sm transition-all active:scale-95">
        <span class="material-symbols-outlined text-sm">add</span>
        {{ __t('admin.slider.add_slide') }}
    </a>
</div>
@endif
@endsection
