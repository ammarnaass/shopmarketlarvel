@extends('admin.layout')

@section('title', __t('admin.pages.title'))

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold">{{ __t('admin.pages.title') }}</h1>
        <p class="text-on-surface-variant text-sm mt-1">{{ __t('admin.pages.manage_pages') }}</p>
    </div>
    <a href="{{ route('admin.pages.create') }}" class="bg-primary hover:bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold">
        <span class="material-symbols-outlined ml-1">add</span> {{ __t('admin.pages.add_new') }}
    </a>
</div>

<div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-surface-container-low text-on-surface-variant text-xs">
                <tr>
                    <th class="px-4 py-3 text-right">{{ __t('admin.pages.title_field') }}</th>
                    <th class="px-4 py-3 text-right">{{ __t('admin.pages.slug') }}</th>
                    <th class="px-4 py-3 text-right">{{ __t('admin.pages.status') }}</th>
                    <th class="px-4 py-3 text-right">{{ __t('admin.pages.sort_order') }}</th>
                    <th class="px-4 py-3 text-right">{{ __t('common.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                    <tr class="border-t hover:bg-surface-container-low">
                        <td class="px-4 py-3 font-semibold">{{ $page->title }}</td>
                        <td class="px-4 py-3 text-on-surface-variant font-mono text-xs">/page/{{ $page->slug }}</td>
                        <td class="px-4 py-3">
                            @if($page->is_active)
                                <span class="bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded text-xs">{{ __t('admin.pages.published') }}</span>
                            @else
                                <span class="bg-error-container text-on-error-container px-2 py-0.5 rounded text-xs">{{ __t('admin.pages.draft') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-on-surface-variant">{{ $page->sort_order }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.pages.edit', $page) }}" class="text-primary hover:text-primary">
                                    <span class="material-symbols-outlined">edit</span>
                                </a>
                                <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="inline" onsubmit="return confirm('{{ __t('admin.pages.delete_confirm') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl text-gray-300 mb-3">description</span>
                            <p>{{ __t('admin.pages.no_pages') }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pages->hasPages())
        <div class="p-4 border-t">{{ $pages->links() }}</div>
    @endif
</div>
@endsection