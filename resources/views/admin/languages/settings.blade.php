@extends('admin.layout')

@section('title', __t('admin.languages.settings_title'))
@section('page_title', '⚙️ ' . __t('admin.languages.settings_title'))

@section('content')
<nav class="flex items-center gap-2 text-on-surface-variant text-sm mb-3">
    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors">{{ __t('admin.dashboard.title') }}</a>
    <span class="material-symbols-outlined text-xs">chevron_left</span>
    <a href="{{ route('admin.languages.index') }}" class="hover:text-primary transition-colors">{{ __t('admin.languages.title') }}</a>
    <span class="material-symbols-outlined text-xs">chevron_left</span>
    <span class="text-primary font-semibold">{{ __t('admin.languages.format_settings') }}</span>
</nav>

<div class="space-y-6">
    @foreach($languages as $language)
        <div class="bg-white rounded-xl shadow-sm border border-outline-variant overflow-hidden">
            <div class="p-5 border-b border-outline-variant flex items-center gap-3">
                <span class="text-2xl">{{ $language->flag }}</span>
                <div>
                    <h3 class="font-bold text-on-surface">{{ $language->native_name }}</h3>
                    <p class="text-xs text-on-surface-variant">
                        {{ $language->name_en }} · 
                        <code class="bg-surface-container-high px-1.5 py-0.5 rounded text-xs">{{ $language->code }}</code> · 
                        <span class="badge {{ $language->direction === 'rtl' ? 'badge-primary' : 'badge-gray' }}">{{ strtoupper($language->direction) }}</span>
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.languages.update-settings', $language) }}">
                @csrf
                <div class="p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.languages.date_format') }}</label>
                        <select name="date_format" class="w-full rounded-lg border border-outline-variant bg-white p-2.5 text-sm" dir="ltr">
                            <option value="Y-m-d" {{ $language->date_format === 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                            <option value="m/d/Y" {{ $language->date_format === 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                            <option value="d/m/Y" {{ $language->date_format === 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                            <option value="d-m-Y" {{ $language->date_format === 'd-m-Y' ? 'selected' : '' }}>DD-MM-YYYY</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.languages.time_format') }}</label>
                        <select name="time_format" class="w-full rounded-lg border border-outline-variant bg-white p-2.5 text-sm" dir="ltr">
                            <option value="H:i" {{ $language->time_format === 'H:i' ? 'selected' : '' }}>24 ساعة (14:30)</option>
                            <option value="h:i A" {{ $language->time_format === 'h:i A' ? 'selected' : '' }}>12 ساعة (02:30 PM)</option>
                            <option value="h:i a" {{ $language->time_format === 'h:i a' ? 'selected' : '' }}>12 ساعة (02:30 pm)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.languages.decimal_separator') }}</label>
                        <select name="decimal_separator" class="w-full rounded-lg border border-outline-variant bg-white p-2.5 text-sm">
                            <option value="." {{ $language->decimal_separator === '.' ? 'selected' : '' }}>نقطة (.)</option>
                            <option value="," {{ $language->decimal_separator === ',' ? 'selected' : '' }}>فاصلة (,)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.languages.thousands_separator') }}</label>
                        <select name="thousands_separator" class="w-full rounded-lg border border-outline-variant bg-white p-2.5 text-sm">
                            <option value="," {{ $language->thousands_separator === ',' ? 'selected' : '' }}>فاصلة (,)</option>
                            <option value=" " {{ $language->thousands_separator === ' ' ? 'selected' : '' }}>مسافة ( )</option>
                            <option value="." {{ $language->thousands_separator === '.' ? 'selected' : '' }}>نقطة (.)</option>
                            <option value="" {{ $language->thousands_separator === '' ? 'selected' : '' }}>بدون فاصل</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.languages.currency_position') }}</label>
                        <select name="currency_position" class="w-full rounded-lg border border-outline-variant bg-white p-2.5 text-sm">
                            <option value="before" {{ $language->currency_position === 'before' ? 'selected' : '' }}>قبل المبلغ (ر.س 150)</option>
                            <option value="after" {{ $language->currency_position === 'after' ? 'selected' : '' }}>بعد المبلغ (150 ر.س)</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2.5 rounded-lg bg-primary text-white text-sm font-medium hover:bg-primary-container transition-all flex items-center justify-center gap-1">
                            <span class="material-symbols-outlined text-sm">save</span>
                            {{ __t('admin.languages.save') }}
                        </button>
                    </div>
                </div>
            </form>

            <div class="px-5 pb-4">
                <div class="bg-surface-container-low rounded-lg p-3 text-sm">
                    <span class="font-medium text-on-surface-variant">{{ __t('admin.languages.format_preview') }} </span>
                    <span class="mr-2" dir="ltr">
                        التاريخ: <code class="bg-surface-container-high px-1.5 py-0.5 rounded text-xs">{{ date($language->date_format) }}</code>
                        · الوقت: <code class="bg-surface-container-high px-1.5 py-0.5 rounded text-xs">{{ date($language->time_format) }}</code>
                        · الرقم: <code class="bg-surface-container-high px-1.5 py-0.5 rounded text-xs">1{{ $language->thousands_separator }}234{{ $language->decimal_separator }}56</code>
                    </span>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
