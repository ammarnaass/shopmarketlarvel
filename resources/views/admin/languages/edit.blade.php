@extends('admin.layout')

@section('title', __t('admin.languages.edit_title') . ' - ' . $language->native_name)
@section('page_title', '✏️ ' . __t('admin.languages.edit_title') . ': ' . $language->native_name)

@section('content')
<nav class="flex items-center gap-2 text-on-surface-variant text-sm mb-3">
    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors">لوحة التحكم</a>
    <span class="material-symbols-outlined text-xs">chevron_left</span>
    <a href="{{ route('admin.languages.index') }}" class="hover:text-primary transition-colors">اللغات</a>
    <span class="material-symbols-outlined text-xs">chevron_left</span>
    <span class="text-primary font-semibold">{{ $language->native_name }}</span>
</nav>

<form method="POST" action="{{ route('admin.languages.update', $language) }}">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-outline-variant p-6">
                <h4 class="text-base font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">info</span>
                    {{ __t('admin.languages.basic_info') }}
                </h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.languages.name_arabic') }}</label>
                        <input type="text" name="name" value="{{ old('name', $language->name) }}" class="w-full rounded-lg border border-outline-variant bg-white p-2.5 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.languages.name_english') }}</label>
                        <input type="text" name="name_en" value="{{ old('name_en', $language->name_en) }}" class="w-full rounded-lg border border-outline-variant bg-white p-2.5 text-sm" dir="ltr">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.languages.native_name') }}</label>
                        <input type="text" name="native_name" value="{{ old('native_name', $language->native_name) }}" class="w-full rounded-lg border border-outline-variant bg-white p-2.5 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.languages.code') }}</label>
                        <input type="text" value="{{ $language->code }}" class="w-full rounded-lg border border-outline-variant bg-gray-50 p-2.5 text-sm" disabled>
                        <p class="text-xs text-on-surface-variant mt-1">{{ __t('admin.languages.code_readonly') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.languages.flag_emoji') }}</label>
                        <input type="text" name="flag" value="{{ old('flag', $language->flag) }}" class="w-full rounded-lg border border-outline-variant bg-white p-2.5 text-sm" placeholder="🇸🇦">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.languages.locale') }}</label>
                        <input type="text" name="locale" value="{{ old('locale', $language->locale) }}" class="w-full rounded-lg border border-outline-variant bg-white p-2.5 text-sm" dir="ltr" placeholder="ar_SA">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-outline-variant p-6">
                <h4 class="text-base font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">tune</span>
                    {{ __t('admin.languages.language_settings') }}
                </h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.languages.direction') }}</label>
                        <select name="direction" class="w-full rounded-lg border border-outline-variant bg-white p-2.5 text-sm">
                            <option value="rtl" {{ $language->direction === 'rtl' ? 'selected' : '' }}>RTL (من اليمين لليسار)</option>
                            <option value="ltr" {{ $language->direction === 'ltr' ? 'selected' : '' }}>LTR (من اليسار لليمين)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.languages.sort_order') }}</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $language->sort_order) }}" min="0" class="w-full rounded-lg border border-outline-variant bg-white p-2.5 text-sm">
                    </div>
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ $language->is_active ? 'checked' : '' }} class="w-4 h-4 text-primary rounded">
                            <span class="text-sm">{{ __t('admin.languages.active') }}</span>
                        </label>
                    </div>
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="is_default" value="0">
                            <input type="checkbox" name="is_default" value="1" {{ $language->is_default ? 'checked' : '' }} class="w-4 h-4 text-primary rounded">
                            <span class="text-sm">{{ __t('admin.languages.default_language') }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-outline-variant p-6">
                <h4 class="text-base font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">format_list_bulleted</span>
                    {{ __t('admin.languages.number_date_format') }}
                </h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.languages.date_format') }}</label>
                        <select name="date_format" class="w-full rounded-lg border border-outline-variant bg-white p-2.5 text-sm" dir="ltr">
                            <option value="Y-m-d" {{ $language->date_format === 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD (2026-06-26)</option>
                            <option value="m/d/Y" {{ $language->date_format === 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY (06/26/2026)</option>
                            <option value="d/m/Y" {{ $language->date_format === 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY (26/06/2026)</option>
                            <option value="d-m-Y" {{ $language->date_format === 'd-m-Y' ? 'selected' : '' }}>DD-MM-YYYY (26-06-2026)</option>
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
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-outline-variant p-5">
                <h4 class="font-bold text-on-surface mb-3">💡 {{ __t('admin.languages.info') }}</h4>
                <ul class="text-xs text-on-surface-variant space-y-1.5">
                    <li>• {{ __t('admin.languages.rule_code_unique') }}</li>
                    <li>• {{ __t('admin.languages.rule_one_default') }}</li>
                    <li>• {{ __t('admin.languages.rule_inactive_hidden') }}</li>
                    <li>• {{ __t('admin.languages.rule_sort_order') }}</li>
                </ul>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-outline-variant p-5">
                <div class="flex flex-col gap-3">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white font-medium hover:bg-primary-container shadow-sm transition-all active:scale-95 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-sm">save</span>
                        {{ __t('admin.languages.save_changes') }}
                    </button>
                    <a href="{{ route('admin.languages.index') }}" class="px-6 py-2.5 rounded-xl border border-outline-variant text-on-surface-variant font-medium hover:bg-surface-container-low transition-all text-center">
                        {{ __t('admin.languages.cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
