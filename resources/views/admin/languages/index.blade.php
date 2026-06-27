@extends('admin.layout')

@section('title', __t('admin.languages.title'))
@section('page_title', '🌍 ' . __t('admin.languages.title'))

@section('content')
<nav class="flex items-center gap-2 text-on-surface-variant text-sm mb-3">
    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors">{{ __t('admin.dashboard.title') }}</a>
    <span class="material-symbols-outlined text-xs">chevron_left</span>
    <span class="text-primary font-semibold">اللغات</span>
</nav>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-outline-variant overflow-hidden">
            <div class="p-5 border-b border-outline-variant flex items-center justify-between">
                <h3 class="font-bold text-on-surface">🌍 {{ __t('admin.languages.active_languages') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant">
                            <th class="text-right py-3 px-4 font-medium text-on-surface-variant">{{ __t('admin.languages.table_flag') }}</th>
                            <th class="text-right py-3 px-4 font-medium text-on-surface-variant">{{ __t('admin.languages.table_language') }}</th>
                            <th class="text-right py-3 px-4 font-medium text-on-surface-variant">{{ __t('admin.languages.table_code') }}</th>
                            <th class="text-right py-3 px-4 font-medium text-on-surface-variant">{{ __t('admin.languages.table_direction') }}</th>
                            <th class="text-center py-3 px-4 font-medium text-on-surface-variant">{{ __t('admin.languages.table_default') }}</th>
                            <th class="text-center py-3 px-4 font-medium text-on-surface-variant">{{ __t('admin.languages.table_active') }}</th>
                            <th class="text-center py-3 px-4 font-medium text-on-surface-variant">{{ __t('admin.languages.table_sort') }}</th>
                            <th class="text-center py-3 px-4 font-medium text-on-surface-variant">{{ __t('admin.languages.table_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($languages as $lang)
                        <tr class="border-b border-outline-variant/50 hover:bg-surface-container-low/50">
                            <td class="py-3 px-4">
                                <span class="text-xl">{{ $lang->flag ?? '🏳️' }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-medium">{{ $lang->native_name }}</span>
                                <span class="text-xs text-on-surface-variant mr-2">{{ $lang->name_en }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <code class="bg-surface-container-high px-2 py-0.5 rounded text-xs font-bold">{{ $lang->code }}</code>
                            </td>
                            <td class="py-3 px-4">
                                <span class="badge {{ $lang->direction === 'rtl' ? 'badge-primary' : 'badge-gray' }}">
                                    {{ $lang->direction === 'rtl' ? 'RTL' : 'LTR' }}
                                </span>
                            </td>
                            <td class="text-center py-3 px-4">
                                @if($lang->is_default)
                                    <span class="material-symbols-outlined text-amber-500">star</span>
                                @else
                                    <a href="{{ route('admin.languages.set-default', $lang) }}" class="text-on-surface-variant hover:text-amber-500"
                                       onclick="return confirm('{{ __t('admin.languages.confirm_set_default') }}')">
                                        <span class="material-symbols-outlined">star_outline</span>
                                    </a>
                                @endif
                            </td>
                            <td class="text-center py-3 px-4">
                                <a href="{{ route('admin.languages.toggle-active', $lang) }}"
                                   class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $lang->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    <span class="material-symbols-outlined" style="font-size:14px">{{ $lang->is_active ? 'check_circle' : 'cancel' }}</span>
                                    {{ $lang->is_active ? __t('admin.languages.active') : __t('admin.languages.inactive') }}
                                </a>
                            </td>
                            <td class="text-center py-3 px-4 text-on-surface-variant">{{ $lang->sort_order }}</td>
                            <td class="text-center py-3 px-4">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('admin.languages.edit', $lang) }}" class="p-1.5 rounded-lg hover:bg-surface-container-low text-on-surface-variant hover:text-primary" title="{{ __t('admin.languages.edit') }}">
                                        <span class="material-symbols-outlined">edit</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-white rounded-xl shadow-sm border border-outline-variant p-5">
            <h4 class="font-bold text-on-surface mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">info</span>
                معلومات اللغات
            </h4>
            <p class="text-sm text-on-surface-variant">
                {{ __t('admin.languages.info_description') }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-outline-variant p-5">
            <h4 class="font-bold text-on-surface mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">link</span>
                روابط سريعة
            </h4>
            <div class="space-y-2">
                <a href="{{ route('admin.languages.translations') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-surface-container-low text-sm text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined">translate</span>
                    {{ __t('admin.languages.manage_translations') }}
                </a>
                <a href="{{ route('admin.languages.settings') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-surface-container-low text-sm text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined">settings</span>
                    {{ __t('admin.languages.format_settings') }}
                </a>
                <a href="{{ route('admin.languages.translations', ['group' => 'instant_buy']) }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-surface-container-low text-sm text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined">bolt</span>
                    {{ __t('admin.languages.instant_buy_translations') }}
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-outline-variant p-5">
            <h4 class="font-bold text-on-surface mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">palette</span>
                دليل الألوان
            </h4>
            <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                    <span class="badge badge-primary">RTL</span>
                    <span>{{ __t('admin.languages.rtl_description') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="badge badge-gray">LTR</span>
                    <span>{{ __t('admin.languages.ltr_description') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
