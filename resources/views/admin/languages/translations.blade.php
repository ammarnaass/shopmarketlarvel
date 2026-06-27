@extends('admin.layout')

@section('title', __t('admin.languages.translations_title'))
@section('page_title', '📝 ' . __t('admin.languages.translations_title'))

@section('content')
<nav class="flex items-center gap-2 text-on-surface-variant text-sm mb-3">
    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors">{{ __t('admin.dashboard.title') }}</a>
    <span class="material-symbols-outlined text-xs">chevron_left</span>
    <a href="{{ route('admin.languages.index') }}" class="hover:text-primary transition-colors">{{ __t('admin.languages.title') }}</a>
    <span class="material-symbols-outlined text-xs">chevron_left</span>
    <span class="text-primary font-semibold">{{ __t('admin.languages.translations') }}</span>
</nav>

{{-- Filters --}}
<div class="bg-white rounded-xl shadow-sm border border-outline-variant p-4 mb-6">
    <form method="GET" class="flex items-center gap-4 flex-wrap">
        <div>
            <label class="block text-xs font-medium text-on-surface-variant mb-1">{{ __t('admin.languages.language') }}</label>
            <select name="language_id" class="rounded-lg border border-outline-variant bg-white p-2 text-sm" onchange="this.form.submit()">
                <option value="">{{ __t('admin.languages.select_language') }}</option>
                @foreach($languages as $lang)
                    <option value="{{ $lang->id }}" {{ $selectedLang == $lang->id ? 'selected' : '' }}>
                        {{ $lang->flag ?? '' }} {{ $lang->native_name }} ({{ $lang->code }})
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-on-surface-variant mb-1">{{ __t('admin.languages.group') }}</label>
            <select name="group" class="rounded-lg border border-outline-variant bg-white p-2 text-sm" onchange="this.form.submit()">
                @foreach($groups as $g)
                    <option value="{{ $g }}" {{ $selectedGroup === $g ? 'selected' : '' }}>{{ $g }}</option>
                @endforeach
                <option value="instant_buy" {{ $selectedGroup === 'instant_buy' ? 'selected' : '' }}>instant_buy</option>
            </select>
        </div>
        <div class="flex-1"></div>
        @if($language)
            <div class="flex items-center gap-2 text-sm">
                <span class="text-xl">{{ $language->flag }}</span>
                <span class="font-medium">{{ $language->native_name }}</span>
                <span class="badge {{ $language->direction === 'rtl' ? 'badge-primary' : 'badge-gray' }}">{{ strtoupper($language->direction) }}</span>
            </div>
        @endif
    </form>
</div>

@if($language && $translations->count() > 0)
    <form method="POST" action="{{ route('admin.languages.translations.bulk-update') }}">
        @csrf
        <div class="bg-white rounded-xl shadow-sm border border-outline-variant overflow-hidden">
            <div class="p-4 border-b border-outline-variant flex items-center justify-between">
                <h3 class="font-bold text-on-surface">
                    {{ __t('admin.languages.group_translations_prefix') }} "{{ $selectedGroup }}" 
                    <span class="text-sm font-normal text-on-surface-variant">({{ $translations->count() }} {{ __t('admin.languages.translation_count') }})</span>
                </h3>
                <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-white text-sm font-medium hover:bg-primary-container transition-all flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">save</span>
                    {{ __t('admin.languages.save_all') }}
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant">
                            <th class="text-right py-3 px-4 font-medium text-on-surface-variant">{{ __t('admin.languages.key') }}</th>
                            <th class="text-right py-3 px-4 font-medium text-on-surface-variant">{{ __t('admin.languages.value') }}</th>
                            <th class="text-center py-3 px-4 font-medium text-on-surface-variant">{{ __t('admin.languages.custom') }}</th>
                            <th class="text-center py-3 px-4 font-medium text-on-surface-variant">{{ __t('admin.languages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($translations as $translation)
                        <tr class="border-b border-outline-variant/50 hover:bg-surface-container-low/50">
                            <td class="py-3 px-4">
                                <code class="bg-surface-container-high px-2 py-0.5 rounded text-xs font-bold">{{ $translation->key }}</code>
                            </td>
                            <td class="py-3 px-4">
                                <input type="hidden" name="translations[{{ $loop->index }}][id]" value="{{ $translation->id }}">
                                <input type="text" name="translations[{{ $loop->index }}][value]" 
                                       value="{{ $translation->value }}" 
                                       class="w-full rounded-lg border border-outline-variant bg-white p-2 text-sm"
                                       dir="{{ $language->direction === 'rtl' ? 'rtl' : 'ltr' }}">
                            </td>
                            <td class="text-center py-3 px-4">
                                @if($translation->is_custom)
                                    <span class="material-symbols-outlined text-amber-500" style="font-variation-settings:'FILL' 1">check</span>
                                @else
                                    <span class="text-on-surface-variant/40">—</span>
                                @endif
                            </td>
                            <td class="text-center py-3 px-4">
                                <button type="button" onclick="if(confirm('{{ __t('admin.languages.delete_translation_confirm') }}')) { document.getElementById('delete-{{ $translation->id }}').submit(); }"
                                        class="p-1.5 rounded-lg hover:bg-red-50 text-on-surface-variant hover:text-red-600" title="{{ __t('admin.languages.delete') }}">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-outline-variant flex justify-between items-center">
                <span class="text-xs text-on-surface-variant">{{ $translations->count() }} ترجمة</span>
                <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-white text-sm font-medium hover:bg-primary-container transition-all flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">save</span>
                    {{ __t('admin.languages.save_all') }}
                </button>
            </div>
        </div>
    </form>

    {{-- Delete forms --}}
    @foreach($translations as $translation)
        <form id="delete-{{ $translation->id }}" method="POST" action="{{ route('admin.languages.translations.delete', $translation) }}" class="hidden">
            @csrf @method('DELETE')
        </form>
    @endforeach

@elseif($language)
    <div class="bg-white rounded-xl shadow-sm border border-outline-variant p-8 text-center">
        <span class="material-symbols-outlined text-4xl text-on-surface-variant/30">translate</span>
        <p class="text-on-surface-variant mt-2">{{ __t('admin.languages.no_translations_prefix') }} "{{ $selectedGroup }}" لهذه اللغة</p>
        <a href="{{ route('admin.languages.translations') }}" class="text-primary text-sm hover:underline mt-2 inline-block">{{ __t('admin.languages.select_another_group') }}</a>
    </div>
@else
    <div class="bg-white rounded-xl shadow-sm border border-outline-variant p-8 text-center">
        <span class="material-symbols-outlined text-4xl text-on-surface-variant/30">translate</span>
        <p class="text-on-surface-variant mt-2">{{ __t('admin.languages.select_lang_and_group') }}</p>
    </div>
@endif
@endsection
