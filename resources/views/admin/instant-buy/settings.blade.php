@extends('admin.layout')

@section('title', __t('admin.instant_buy.title'))

@php
$activeTab = request('tab', 'general');
$s = $settings;
$tabs = [
    'general' => ['icon' => 'bolt', 'title' => __t('admin.instant_buy.general')],
    'colors' => ['icon' => 'palette', 'title' => __t('admin.instant_buy.colors')],
    'fields' => ['icon' => 'list_alt', 'title' => __t('admin.instant_buy.fields')],
    'buttons' => ['icon' => 'smart_button', 'title' => __t('admin.instant_buy.buttons')],
    'success' => ['icon' => 'check_circle', 'title' => __t('admin.instant_buy.success')],
    'orders' => ['icon' => 'shopping_cart', 'title' => __t('admin.instant_buy.orders')],
];
@endphp

@push('styles')
<style>
    .settings-card { background: white; border: 1px solid #e1e2ed; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); }
    input:focus, select:focus, textarea:focus { outline: none !important; border-color: #004ac6 !important; box-shadow: 0 0 0 2px rgba(0, 74, 198, 0.2) !important; }
    .color-preview { width: 36px; height: 36px; border-radius: 8px; border: 1px solid #e1e2ed; flex-shrink: 0; }
    .preview-box { background: white; border: 1px solid #e1e2ed; border-radius: 16px; padding: 20px; max-width: 380px; }
    .field-row { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
    .field-row:last-child { border-bottom: none; }
    .stat-card { background: white; border: 1px solid #e1e2ed; border-radius: 12px; padding: 16px; text-align: center; }
</style>
@endpush

@section('content')
<nav class="flex items-center gap-2 text-on-surface-variant text-sm mb-3">
    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors">{{ __t('admin.instant_buy.dashboard') }}</a>
    <span class="material-symbols-outlined text-xs">chevron_left</span>
    <span class="text-primary font-semibold">{{ __t('admin.instant_buy.title') }}</span>
</nav>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <h3 class="text-2xl font-bold text-on-surface">⚡ {{ __t('admin.instant_buy.title') }}</h3>
    <div class="flex items-center gap-2">
        @if($activeTab !== 'orders')
        <button type="submit" form="settings-form"
                class="px-6 py-2.5 rounded-xl bg-primary text-white font-medium hover:bg-primary-container shadow-sm transition-all active:scale-95 flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">save</span>
            {{ __t('admin.instant_buy.save') }}
        </button>
        @endif
        @if($activeTab === 'general')
        <form action="{{ route('admin.instant-buy.settings.reset') }}" method="POST" class="inline" onsubmit="return confirm('{{ __t('admin.instant_buy.reset_confirm') }}')">
            @csrf
            <button type="submit" class="px-4 py-2.5 rounded-xl border border-outline-variant text-on-surface-variant font-medium hover:bg-surface-container-low transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">restart_alt</span>
                {{ __t('admin.instant_buy.reset_defaults') }}
            </button>
        </form>
        @endif
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
    <div class="flex border-b border-outline-variant overflow-x-auto">
        @foreach($tabs as $key => $tab)
            <a href="{{ route('admin.instant-buy.settings', ['tab' => $key]) }}#{{ $key }}"
               class="flex items-center gap-2 px-5 py-3.5 font-medium text-sm whitespace-nowrap transition-all {{ $activeTab === $key ? 'border-b-2 border-primary text-primary bg-primary-fixed/30' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-low' }}">
                <span class="material-symbols-outlined text-lg">{{ $tab['icon'] }}</span>
                {{ $tab['title'] }}
            </a>
        @endforeach
    </div>
</div>

@if($activeTab === 'general')
<form id="settings-form" method="POST" action="{{ route('admin.instant-buy.settings.general') }}">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="settings-card rounded-xl p-6">
                <h4 class="text-base font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">toggle_on</span>
                    {{ __t('admin.instant_buy.enable_toggle') }}
                </h4>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="is_enabled" value="0">
                    <input type="checkbox" name="is_enabled" value="1" {{ $s->is_enabled ? 'checked' : '' }} class="w-5 h-5 text-primary rounded">
                    <span class="text-sm">{{ __t('admin.instant_buy.enable_label') }}</span>
                </label>
                <p class="text-xs text-on-surface-variant mt-2 mr-8">{{ __t('admin.instant_buy.enable_hint') }}</p>
            </div>

            <div class="settings-card rounded-xl p-6">
                <h4 class="text-base font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">edit_note</span>
                    {{ __t('admin.instant_buy.title_subtitle') }}
                </h4>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.instant_buy.form_title') }}</label>
                        <input type="text" name="title" value="{{ old('title', $s->title) }}" class="w-full rounded-lg border border-outline-variant bg-white p-2.5 text-body-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.instant_buy.form_subtitle') }}</label>
                        <input type="text" name="subtitle" value="{{ old('subtitle', $s->subtitle) }}" class="w-full rounded-lg border border-outline-variant bg-white p-2.5 text-body-md">
                    </div>
                </div>
            </div>

            <div class="settings-card rounded-xl p-6">
                <h4 class="text-base font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">inventory_2</span>
                    {{ __t('admin.instant_buy.product_settings') }}
                </h4>
                <div class="space-y-3">
                    @foreach([
                        ['show_product_summary', __t('admin.instant_buy.show_product_summary')],
                        ['show_quantity_selector', __t('admin.instant_buy.show_quantity_selector')],
                        ['show_price_breakdown', __t('admin.instant_buy.show_price_breakdown')],
                        ['show_shipping_calculator', __t('admin.instant_buy.show_shipping_calculator')],
                        ['auto_select_cheapest_shipping', __t('admin.instant_buy.auto_select_cheapest_shipping')],
                    ] as [$field, $label])
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="{{ $field }}" value="0">
                        <input type="checkbox" name="{{ $field }}" value="1" {{ $s->$field ? 'checked' : '' }} class="w-4 h-4 text-primary rounded">
                        <span class="text-sm">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="settings-card rounded-xl p-6">
                <h4 class="text-base font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">lock</span>
                    {{ __t('admin.instant_buy.trust_message') }}
                </h4>
                <div>
                    <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.instant_buy.trust_message_text') }}</label>
                    <input type="text" name="trust_message" value="{{ old('trust_message', $s->trust_message) }}" class="w-full rounded-lg border border-outline-variant bg-white p-2.5 text-body-md">
                </div>
            </div>

            <div class="settings-card rounded-xl p-6">
                <h4 class="text-base font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">credit_card</span>
                    {{ __t('admin.instant_buy.payment_methods') }}
                </h4>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="show_bank_transfer" value="0">
                    <input type="checkbox" name="show_bank_transfer" value="1" {{ $s->show_bank_transfer ? 'checked' : '' }} class="w-4 h-4 text-primary rounded">
                    <span class="text-sm">{{ __t('admin.instant_buy.show_bank_transfer') }}</span>
                </label>
                <p class="text-xs text-on-surface-variant mt-2 mr-8">{{ __t('admin.instant_buy.show_bank_transfer_hint') }}</p>
            </div>
        </div>

        <div class="space-y-6">
            <div class="settings-card rounded-xl p-6">
                <h4 class="text-base font-bold text-on-surface mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">preview</span>
                    {{ __t('admin.instant_buy.preview') }}
                </h4>
                <div class="preview-box mx-auto">
                    <div style="color:{{ $s->section_title_color }}; font-size:{{ $s->section_title_size }}px; font-weight:{{ $s->section_title_weight }}">
                        <span style="color:{{ $s->section_icon_color }}">⚡</span>
                        {{ $s->title }}
                    </div>
                    <p class="text-xs text-on-surface-variant mt-1">{{ $s->subtitle }}</p>
                    <div class="mt-3 space-y-2">
                        <div style="background:{{ $s->input_bg_color }}; border:1px solid {{ $s->input_border_color }}; border-radius:{{ $s->input_border_radius }}px; height:{{ $s->input_height }}px" class="flex items-center px-3">
                            <span style="color:{{ $s->input_placeholder_color }}; font-size:13px">{{ __t('admin.instant_buy.preview_name') }}</span>
                        </div>
                        <div style="background:{{ $s->input_bg_color }}; border:1px solid {{ $s->input_border_color }}; border-radius:{{ $s->input_border_radius }}px; height:{{ $s->input_height }}px" class="flex items-center px-3">
                            <span style="color:{{ $s->input_placeholder_color }}; font-size:13px">5xx xxx xxx</span>
                        </div>
                        <div style="background:{{ $s->button_bg_color }}; color:{{ $s->button_text_color }}; border-radius:{{ $s->button_border_radius }}px; height:{{ $s->button_height }}px" class="flex items-center justify-center font-bold text-sm">
                            {{ $s->button_icon }} {{ $s->button_text }}
                        </div>
                    </div>
                    <p style="color:{{ $s->trust_message_color }}; font-size:{{ $s->trust_message_size }}px" class="mt-2 text-center">{{ $s->trust_message }}</p>
                </div>
            </div>

            <div class="settings-card rounded-xl p-4">
                <h4 class="text-sm font-bold text-on-surface mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">info</span>
                    {{ __t('admin.instant_buy.info') }}
                </h4>
                <ul class="text-xs text-on-surface-variant space-y-1.5">
                    <li>• {{ __t('admin.instant_buy.info_colors') }}</li>
                    <li>• {{ __t('admin.instant_buy.info_fields') }}</li>
                    <li>• {{ __t('admin.instant_buy.info_buttons') }}</li>
                    <li>• {{ __t('admin.instant_buy.info_success') }}</li>
                </ul>
            </div>
        </div>
    </div>
</form>

@elseif($activeTab === 'colors')
<form id="settings-form" method="POST" action="{{ route('admin.instant-buy.settings.colors') }}">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="settings-card rounded-xl p-6">
                <h4 class="text-base font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">palette</span>
                    {{ __t('admin.instant_buy.form_colors') }}
                </h4>
                <div class="grid grid-cols-2 gap-4">
                    @foreach([
                        ['form_bg_color', __t('admin.instant_buy.form_bg_color')],
                        ['form_border_color', __t('admin.instant_buy.form_border_color')],
                    ] as [$field, $label])
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ $label }}</label>
                        <div class="flex items-center gap-2">
                            <div class="color-preview" style="background:{{ $s->$field }}"></div>
                            <input type="text" name="{{ $field }}" value="{{ old($field, $s->$field) }}" class="flex-1 rounded-lg border border-outline-variant bg-white p-2 text-sm" dir="ltr">
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="grid grid-cols-3 gap-4 mt-4">
                    @foreach([
                        ['form_border_width', __t('admin.instant_buy.form_border_width'), 0, 10, 'px'],
                        ['form_border_radius', __t('admin.instant_buy.form_border_radius'), 0, 50, 'px'],
                    ] as [$field, $label, $min, $max, $unit])
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ $label }}</label>
                        <input type="number" name="{{ $field }}" value="{{ old($field, $s->$field) }}" min="{{ $min }}" max="{{ $max }}" class="w-full rounded-lg border border-outline-variant bg-white p-2 text-sm">
                    </div>
                    @endforeach
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.instant_buy.form_shadow') }}</label>
                        <input type="text" name="form_shadow" value="{{ old('form_shadow', $s->form_shadow) }}" class="w-full rounded-lg border border-outline-variant bg-white p-2 text-sm" dir="ltr">
                    </div>
                </div>
            </div>

            <div class="settings-card rounded-xl p-6">
                <h4 class="text-base font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">title</span>
                    {{ __t('admin.instant_buy.title_colors') }}
                </h4>
                <div class="grid grid-cols-3 gap-4">
                    @foreach([
                        ['section_title_color', __t('admin.instant_buy.section_title_color')],
                        ['section_icon_color', __t('admin.instant_buy.section_icon_color')],
                    ] as [$field, $label])
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ $label }}</label>
                        <div class="flex items-center gap-2">
                            <div class="color-preview" style="background:{{ $s->$field }}"></div>
                            <input type="text" name="{{ $field }}" value="{{ old($field, $s->$field) }}" class="flex-1 rounded-lg border border-outline-variant bg-white p-2 text-sm" dir="ltr">
                        </div>
                    </div>
                    @endforeach
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.instant_buy.font_size') }}</label>
                        <input type="number" name="section_title_size" value="{{ old('section_title_size', $s->section_title_size) }}" min="12" max="32" class="w-full rounded-lg border border-outline-variant bg-white p-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.instant_buy.font_weight') }}</label>
                        <select name="section_title_weight" class="w-full rounded-lg border border-outline-variant bg-white p-2 text-sm">
                            @foreach(['normal'=>__t('admin.instant_buy.normal'),'semibold'=>__t('admin.instant_buy.semibold'),'bold'=>__t('admin.instant_buy.bold')] as $v => $l)
                            <option value="{{ $v }}" {{ $s->section_title_weight === $v ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="settings-card rounded-xl p-6">
                <h4 class="text-base font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">edit</span>
                    {{ __t('admin.instant_buy.field_colors') }}
                </h4>
                <div class="grid grid-cols-3 gap-4">
                    @foreach([
                        ['input_bg_color', __t('admin.instant_buy.input_bg_color')],
                        ['input_border_color', __t('admin.instant_buy.input_border_color')],
                        ['input_focus_color', __t('admin.instant_buy.input_focus_color')],
                        ['input_text_color', __t('admin.instant_buy.input_text_color')],
                        ['input_placeholder_color', __t('admin.instant_buy.input_placeholder_color')],
                    ] as [$field, $label])
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ $label }}</label>
                        <div class="flex items-center gap-2">
                            <div class="color-preview" style="background:{{ $s->$field }}"></div>
                            <input type="text" name="{{ $field }}" value="{{ old($field, $s->$field) }}" class="flex-1 rounded-lg border border-outline-variant bg-white p-2 text-sm" dir="ltr">
                        </div>
                    </div>
                    @endforeach
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.instant_buy.input_border_radius') }}</label>
                        <input type="number" name="input_border_radius" value="{{ old('input_border_radius', $s->input_border_radius) }}" min="0" max="50" class="w-full rounded-lg border border-outline-variant bg-white p-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.instant_buy.input_height') }}</label>
                        <input type="number" name="input_height" value="{{ old('input_height', $s->input_height) }}" min="32" max="80" class="w-full rounded-lg border border-outline-variant bg-white p-2 text-sm">
                    </div>
                </div>
            </div>

            <div class="settings-card rounded-xl p-6">
                <h4 class="text-base font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">receipt_long</span>
                    {{ __t('admin.instant_buy.summary_colors') }}
                </h4>
                <div class="grid grid-cols-3 gap-4">
                    @foreach([
                        ['summary_bg_color', __t('admin.instant_buy.summary_bg_color')],
                        ['summary_border_color', __t('admin.instant_buy.summary_border_color')],
                        ['summary_text_color', __t('admin.instant_buy.summary_text_color')],
                        ['summary_total_color', __t('admin.instant_buy.summary_total_color')],
                    ] as [$field, $label])
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ $label }}</label>
                        <div class="flex items-center gap-2">
                            <div class="color-preview" style="background:{{ $s->$field }}"></div>
                            <input type="text" name="{{ $field }}" value="{{ old($field, $s->$field) }}" class="flex-1 rounded-lg border border-outline-variant bg-white p-2 text-sm" dir="ltr">
                        </div>
                    </div>
                    @endforeach
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.instant_buy.total_font_size') }}</label>
                        <input type="number" name="summary_total_size" value="{{ old('summary_total_size', $s->summary_total_size) }}" min="14" max="36" class="w-full rounded-lg border border-outline-variant bg-white p-2 text-sm">
                    </div>
                </div>
            </div>

            <div class="settings-card rounded-xl p-6">
                <h4 class="text-base font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">lock</span>
                    {{ __t('admin.instant_buy.trust_message_colors') }}
                </h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.instant_buy.trust_text_color') }}</label>
                        <div class="flex items-center gap-2">
                            <div class="color-preview" style="background:{{ $s->trust_message_color }}"></div>
                            <input type="text" name="trust_message_color" value="{{ old('trust_message_color', $s->trust_message_color) }}" class="flex-1 rounded-lg border border-outline-variant bg-white p-2 text-sm" dir="ltr">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.instant_buy.trust_font_size') }}</label>
                        <input type="number" name="trust_message_size" value="{{ old('trust_message_size', $s->trust_message_size) }}" min="10" max="20" class="w-full rounded-lg border border-outline-variant bg-white p-2 text-sm">
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="settings-card rounded-xl p-6">
                <h4 class="text-base font-bold text-on-surface mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">preview</span>
                    {{ __t('admin.instant_buy.live_preview') }}
                </h4>
                <div class="preview-box mx-auto">
                    <div style="color:{{ $s->section_title_color }}; font-size:{{ $s->section_title_size }}px; font-weight:{{ $s->section_title_weight }}">
                        <span style="color:{{ $s->section_icon_color }}">⚡</span>
                        {{ $s->title }}
                    </div>
                    <div class="mt-3 space-y-2">
                        <div style="background:{{ $s->input_bg_color }}; border:1px solid {{ $s->input_border_color }}; border-radius:{{ $s->input_border_radius }}px; height:{{ $s->input_height }}px" class="flex items-center px-3">
                            <span style="color:{{ $s->input_placeholder_color }}; font-size:13px">{{ __t('admin.instant_buy.sample_text') }}</span>
                        </div>
                        <div style="background:{{ $s->button_bg_color }}; color:{{ $s->button_text_color }}; border-radius:{{ $s->button_border_radius }}px; height:{{ $s->button_height }}px" class="flex items-center justify-center font-bold text-sm">
                            {{ $s->button_icon }} {{ $s->button_text }}
                        </div>
                    </div>
                    <p style="color:{{ $s->trust_message_color }}; font-size:{{ $s->trust_message_size }}px" class="mt-2 text-center">{{ $s->trust_message }}</p>
                </div>
            </div>

            <div class="settings-card rounded-xl p-4">
                <h4 class="text-sm font-bold text-on-surface mb-2">💡 {{ __t('admin.instant_buy.color_tips') }}</h4>
                <ul class="text-xs text-on-surface-variant space-y-1">
                    <li>• {{ __t('admin.instant_buy.color_tip_1') }}</li>
                    <li>• {{ __t('admin.instant_buy.color_tip_2') }}</li>
                    <li>• {{ __t('admin.instant_buy.color_tip_3') }}</li>
                </ul>
            </div>
        </div>
    </div>
</form>

@elseif($activeTab === 'fields')
<form id="settings-form" method="POST" action="{{ route('admin.instant-buy.settings.fields') }}">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="settings-card rounded-xl p-6">
                <h4 class="text-base font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">list_alt</span>
                    {{ __t('admin.instant_buy.field_order') }}
                </h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-outline-variant">
                                <th class="text-right py-3 px-2 font-medium text-on-surface-variant">{{ __t('admin.instant_buy.field') }}</th>
                                <th class="text-center py-3 px-2 font-medium text-on-surface-variant">{{ __t('admin.instant_buy.enable') }}</th>
                                <th class="text-center py-3 px-2 font-medium text-on-surface-variant">{{ __t('admin.instant_buy.required') }}</th>
                                <th class="text-right py-3 px-2 font-medium text-on-surface-variant">{{ __t('admin.instant_buy.label') }}</th>
                                <th class="text-right py-3 px-2 font-medium text-on-surface-variant">{{ __t('admin.instant_buy.placeholder') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach([
                                ['first_name', __t('admin.instant_buy.first_name')],
                                ['last_name', __t('admin.instant_buy.last_name')],
                                ['phone', __t('admin.instant_buy.phone')],
                                ['country', __t('admin.instant_buy.country')],
                                ['state', __t('admin.instant_buy.state')],
                                ['city', __t('admin.instant_buy.city')],
                                ['email', __t('admin.instant_buy.email')],
                                ['address', __t('admin.instant_buy.address')],
                                ['district', __t('admin.instant_buy.district')],
                                ['zip', __t('admin.instant_buy.zip')],
                                ['notes', __t('admin.instant_buy.notes')],
                                ['coupon', __t('admin.instant_buy.coupon')],
                            ] as [$key, $label])
                            <tr class="border-b border-outline-variant/50">
                                <td class="py-3 px-2 font-medium">{{ $label }}</td>
                                <td class="text-center py-3 px-2">
                                    <input type="hidden" name="fields[{{ $key }}][enabled]" value="0">
                                    <input type="checkbox" name="fields[{{ $key }}][enabled]" value="1" {{ $s->{'field_'.$key.'_enabled'} ? 'checked' : '' }} class="w-4 h-4 text-primary rounded">
                                </td>
                                <td class="text-center py-3 px-2">
                                    <input type="hidden" name="fields[{{ $key }}][required]" value="0">
                                    <input type="checkbox" name="fields[{{ $key }}][required]" value="1" {{ $s->{'field_'.$key.'_required'} ? 'checked' : '' }} class="w-4 h-4 text-primary rounded">
                                </td>
                                <td class="py-3 px-2">
                                    <input type="text" name="fields[{{ $key }}][label]" value="{{ $s->{'field_'.$key.'_label'} }}" class="w-full rounded-lg border border-outline-variant bg-white p-2 text-sm">
                                </td>
                                <td class="py-3 px-2">
                                    @php $placeholder = $s->{'field_'.$key.'_placeholder'} ?? ''; @endphp
                                    <input type="text" name="fields[{{ $key }}][placeholder]" value="{{ $placeholder }}" class="w-full rounded-lg border border-outline-variant bg-white p-2 text-sm" {{ in_array($key, ['country','state','city']) ? 'disabled' : '' }}>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="settings-card rounded-xl p-6">
                <h4 class="text-sm font-bold text-on-surface mb-2">💡 {{ __t('admin.instant_buy.info') }}</h4>
                <ul class="text-xs text-on-surface-variant space-y-1">
                    <li>• {{ __t('admin.instant_buy.fields_info_disabled') }}</li>
                    <li>• {{ __t('admin.instant_buy.fields_info_required') }}</li>
                    <li>• {{ __t('admin.instant_buy.fields_info_label') }}</li>
                    <li>• {{ __t('admin.instant_buy.fields_info_dropdown') }}</li>
                </ul>
            </div>
        </div>
    </div>
</form>

@elseif($activeTab === 'buttons')
<form id="settings-form" method="POST" action="{{ route('admin.instant-buy.settings.buttons') }}">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="settings-card rounded-xl p-6">
                <h4 class="text-base font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">smart_button</span>
                    {{ __t('admin.instant_buy.main_button') }}
                </h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.instant_buy.button_text') }}</label>
                        <input type="text" name="button_text" value="{{ old('button_text', $s->button_text) }}" class="w-full rounded-lg border border-outline-variant bg-white p-2.5 text-body-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.instant_buy.button_icon') }}</label>
                        <input type="text" name="button_icon" value="{{ old('button_icon', $s->button_icon) }}" class="w-full rounded-lg border border-outline-variant bg-white p-2.5 text-body-md" placeholder="✅">
                    </div>
                    @foreach([
                        ['button_bg_color', __t('admin.instant_buy.button_bg_color')],
                        ['button_hover_color', __t('admin.instant_buy.button_hover_color')],
                        ['button_text_color', __t('admin.instant_buy.button_text_color')],
                    ] as [$field, $label])
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ $label }}</label>
                        <div class="flex items-center gap-2">
                            <div class="color-preview" style="background:{{ $s->$field }}"></div>
                            <input type="text" name="{{ $field }}" value="{{ old($field, $s->$field) }}" class="flex-1 rounded-lg border border-outline-variant bg-white p-2 text-sm" dir="ltr">
                        </div>
                    </div>
                    @endforeach
                    @foreach([
                        ['button_text_size', __t('admin.instant_buy.button_text_size'), 12, 32],
                        ['button_border_radius', __t('admin.instant_buy.button_border_radius'), 0, 50],
                        ['button_height', __t('admin.instant_buy.button_height'), 32, 80],
                    ] as [$field, $label, $min, $max])
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ $label }}</label>
                        <input type="number" name="{{ $field }}" value="{{ old($field, $s->$field) }}" min="{{ $min }}" max="{{ $max }}" class="w-full rounded-lg border border-outline-variant bg-white p-2 text-sm">
                    </div>
                    @endforeach
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.instant_buy.font_weight') }}</label>
                        <select name="button_weight" class="w-full rounded-lg border border-outline-variant bg-white p-2 text-sm">
                            <option value="normal" {{ $s->button_weight === 'normal' ? 'selected' : '' }}>{{ __t('admin.instant_buy.normal') }}</option>
                            <option value="bold" {{ $s->button_weight === 'bold' ? 'selected' : '' }}>{{ __t('admin.instant_buy.bold') }}</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 p-4 rounded-lg" style="background:{{ $s->form_bg_color }}; border:1px solid {{ $s->form_border_color }}">
                    <p class="text-xs text-on-surface-variant mb-2">{{ __t('admin.instant_buy.preview') }}:</p>
                    <div style="background:{{ $s->button_bg_color }}; color:{{ $s->button_text_color }}; border-radius:{{ $s->button_border_radius }}px; height:{{ $s->button_height }}px; font-size:{{ $s->button_text_size }}px; font-weight:{{ $s->button_weight }}" class="flex items-center justify-center">
                        {{ $s->button_icon }} {{ $s->button_text }}
                    </div>
                </div>
            </div>

            <div class="settings-card rounded-xl p-6">
                <h4 class="text-base font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">sell</span>
                    {{ __t('admin.instant_buy.coupon_button') }}
                </h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.instant_buy.button_text') }}</label>
                        <input type="text" name="field_coupon_button_text" value="{{ old('field_coupon_button_text', $s->field_coupon_button_text) }}" class="w-full rounded-lg border border-outline-variant bg-white p-2.5 text-body-md">
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="settings-card rounded-xl p-4">
                <h4 class="text-sm font-bold text-on-surface mb-2">💡 {{ __t('admin.instant_buy.info') }}</h4>
                <ul class="text-xs text-on-surface-variant space-y-1">
                    <li>• {{ __t('admin.instant_buy.buttons_info_color') }}</li>
                    <li>• {{ __t('admin.instant_buy.buttons_info_icon') }}</li>
                    <li>• {{ __t('admin.instant_buy.buttons_info_emoji') }}</li>
                </ul>
            </div>
        </div>
    </div>
</form>

@elseif($activeTab === 'success')
<form id="settings-form" method="POST" action="{{ route('admin.instant-buy.settings.success') }}">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="settings-card rounded-xl p-6">
                <h4 class="text-base font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">check_circle</span>
                    {{ __t('admin.instant_buy.success_settings') }}
                </h4>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.instant_buy.success_title') }}</label>
                            <input type="text" name="success_title" value="{{ old('success_title', $s->success_title) }}" class="w-full rounded-lg border border-outline-variant bg-white p-2.5 text-body-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.instant_buy.success_button_text') }}</label>
                            <input type="text" name="success_button_text" value="{{ old('success_button_text', $s->success_button_text) }}" class="w-full rounded-lg border border-outline-variant bg-white p-2.5 text-body-md">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.instant_buy.success_message') }}</label>
                        <input type="text" name="success_message" value="{{ old('success_message', $s->success_message) }}" class="w-full rounded-lg border border-outline-variant bg-white p-2.5 text-body-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ __t('admin.instant_buy.success_whatsapp_message') }}</label>
                        <input type="text" name="success_whatsapp_message" value="{{ old('success_whatsapp_message', $s->success_whatsapp_message) }}" class="w-full rounded-lg border border-outline-variant bg-white p-2.5 text-body-md" dir="ltr">
                        <p class="text-xs text-on-surface-variant mt-1">{{ __t('admin.instant_buy.whatsapp_variables') }}: <code>{order_number}</code> <code>{customer_name}</code> <code>{total}</code></p>
                    </div>
                </div>
            </div>

            <div class="settings-card rounded-xl p-6">
                <h4 class="text-base font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">visibility</span>
                    {{ __t('admin.instant_buy.success_elements') }}
                </h4>
                <div class="space-y-3">
                    @foreach([
                        ['success_show_order_number', __t('admin.instant_buy.success_show_order_number')],
                        ['success_show_order_details', __t('admin.instant_buy.success_show_order_details')],
                        ['success_show_whatsapp_button', __t('admin.instant_buy.success_show_whatsapp_button')],
                    ] as [$field, $label])
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="{{ $field }}" value="0">
                        <input type="checkbox" name="{{ $field }}" value="1" {{ $s->$field ? 'checked' : '' }} class="w-4 h-4 text-primary rounded">
                        <span class="text-sm">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="settings-card rounded-xl p-6">
                <h4 class="text-base font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">palette</span>
                    {{ __t('admin.instant_buy.success_colors') }}
                </h4>
                <div class="grid grid-cols-3 gap-4">
                    @foreach([
                        ['success_icon_color', __t('admin.instant_buy.success_icon_color')],
                        ['success_title_color', __t('admin.instant_buy.success_title_color')],
                        ['success_order_number_color', __t('admin.instant_buy.success_order_number_color')],
                    ] as [$field, $label])
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ $label }}</label>
                        <div class="flex items-center gap-2">
                            <div class="color-preview" style="background:{{ $s->$field }}"></div>
                            <input type="text" name="{{ $field }}" value="{{ old($field, $s->$field) }}" class="flex-1 rounded-lg border border-outline-variant bg-white p-2 text-sm" dir="ltr">
                        </div>
                    </div>
                    @endforeach
                    @foreach([
                        ['success_icon_size', __t('admin.instant_buy.success_icon_size'), 32, 128],
                        ['success_order_number_size', __t('admin.instant_buy.success_order_number_size'), 14, 36],
                    ] as [$field, $label, $min, $max])
                    <div>
                        <label class="block text-sm font-medium text-on-surface-variant mb-1">{{ $label }}</label>
                        <input type="number" name="{{ $field }}" value="{{ old($field, $s->$field) }}" min="{{ $min }}" max="{{ $max }}" class="w-full rounded-lg border border-outline-variant bg-white p-2 text-sm">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="settings-card rounded-xl p-6">
                <h4 class="text-sm font-bold text-on-surface mb-3">✅ {{ __t('admin.instant_buy.success_preview') }}</h4>
                <div class="text-center">
                    <div style="font-size:{{ $s->success_icon_size }}px; color:{{ $s->success_icon_color }}">✅</div>
                    <h3 style="color:{{ $s->success_title_color }}; font-size:18px; font-weight:bold" class="mt-2">{{ $s->success_title }}</h3>
                    <p class="text-sm text-on-surface-variant mt-1">{{ $s->success_message }}</p>
                    @if($s->success_show_order_number)
                    <div style="color:{{ $s->success_order_number_color }}; font-size:{{ $s->success_order_number_size }}px; font-weight:bold" class="mt-2">#IB-ABC12345</div>
                    @endif
                    <div class="mt-3" style="background:{{ $s->button_bg_color }}; color:{{ $s->button_text_color }}; border-radius:{{ $s->button_border_radius }}px; height:{{ $s->button_height }}px" class="flex items-center justify-center text-sm font-bold">
                        {{ $s->success_button_text }}
                    </div>
                </div>
            </div>

            <div class="settings-card rounded-xl p-4">
                <h4 class="text-sm font-bold text-on-surface mb-2">💡 {{ __t('admin.instant_buy.info') }}</h4>
                <ul class="text-xs text-on-surface-variant space-y-1">
                    <li>• {{ __t('admin.instant_buy.success_info_message') }}</li>
                    <li>• {{ __t('admin.instant_buy.success_info_whatsapp') }}</li>
                    <li>• {{ __t('admin.instant_buy.success_info_variables') }}</li>
                </ul>
            </div>
        </div>
    </div>
</form>

@elseif($activeTab === 'orders')
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
    @foreach([
        ['total', __t('admin.instant_buy.total'), 'shopping_cart', 'text-primary'],
        ['new', __t('admin.instant_buy.new'), 'pending', 'text-amber-600'],
        ['confirmed', __t('admin.instant_buy.confirmed'), 'check_circle', 'text-emerald-600'],
        ['cancelled', __t('admin.instant_buy.cancelled'), 'cancel', 'text-red-600'],
    ] as [$key, $label, $icon, $color])
    <div class="stat-card">
        <span class="material-symbols-outlined {{ $color }}">{{ $icon }}</span>
        <div class="text-2xl font-bold mt-1">{{ $stats[$key] }}</div>
        <div class="text-xs text-on-surface-variant">{{ $label }}</div>
    </div>
    @endforeach
</div>

<div class="settings-card rounded-xl overflow-hidden">
    <div class="p-4 border-b border-outline-variant flex items-center justify-between">
        <h4 class="font-bold text-on-surface">{{ __t('admin.instant_buy.all_orders') }}</h4>
    </div>
    @if($orders->count())
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-surface-container-low border-b border-outline-variant">
                    <th class="text-right py-3 px-4 font-medium text-on-surface-variant">{{ __t('admin.instant_buy.order_number') }}</th>
                    <th class="text-right py-3 px-4 font-medium text-on-surface-variant">{{ __t('admin.instant_buy.customer') }}</th>
                    <th class="text-right py-3 px-4 font-medium text-on-surface-variant">{{ __t('admin.instant_buy.product') }}</th>
                    <th class="text-right py-3 px-4 font-medium text-on-surface-variant">{{ __t('admin.instant_buy.amount') }}</th>
                    <th class="text-center py-3 px-4 font-medium text-on-surface-variant">{{ __t('admin.instant_buy.status') }}</th>
                    <th class="text-center py-3 px-4 font-medium text-on-surface-variant">{{ __t('admin.instant_buy.date') }}</th>
                    <th class="text-center py-3 px-4 font-medium text-on-surface-variant">{{ __t('common.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="border-b border-outline-variant/50 hover:bg-surface-container-low/50">
                    <td class="py-3 px-4 font-medium text-primary">#{{ $order->order_number }}</td>
                    <td class="py-3 px-4">
                        <div class="font-medium">{{ $order->first_name }} {{ $order->last_name }}</div>
                        <div class="text-xs text-on-surface-variant" dir="ltr">{{ $order->phone }}</div>
                    </td>
                    <td class="py-3 px-4">
                        <div class="text-xs line-clamp-1">{{ $order->product?->name ?? '-' }}</div>
                        <div class="text-xs text-on-surface-variant">×{{ $order->quantity }}</div>
                    </td>
                    <td class="py-3 px-4 font-medium">{{ number_format($order->grand_total, 2) }}</td>
                    <td class="py-3 px-4 text-center">
                        @php
                        $statusClasses = ['new'=>'bg-amber-100 text-amber-800', 'confirmed'=>'bg-blue-100 text-blue-800', 'processing'=>'bg-indigo-100 text-indigo-800', 'shipped'=>'bg-purple-100 text-purple-800', 'delivered'=>'bg-emerald-100 text-emerald-800', 'cancelled'=>'bg-red-100 text-red-800'];
                        $statusLabels = ['new'=>__t('admin.instant_buy.new'), 'confirmed'=>__t('admin.instant_buy.confirmed'), 'processing'=>__t('admin.instant_buy.processing'), 'shipped'=>__t('admin.instant_buy.shipped'), 'delivered'=>__t('admin.instant_buy.delivered'), 'cancelled'=>__t('admin.instant_buy.cancelled')];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusClasses[$order->status] ?? 'bg-gray-100' }}">
                            {{ $statusLabels[$order->status] ?? $order->status }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-center text-xs text-on-surface-variant">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                    <td class="py-3 px-4 text-center">
                        <form method="POST" action="{{ route('admin.instant-buy.orders.update-status', $order) }}" class="inline-flex items-center gap-1">
                            @csrf
                            <select name="status" class="text-xs rounded border border-outline-variant p-1" onchange="this.form.submit()">
                                @foreach($statusLabels as $val => $lab)
                                <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $lab }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if(method_exists($orders, 'links'))
    <div class="p-4">{{ $orders->links() }}</div>
    @endif
    @else
    <div class="p-6 text-center text-on-surface-variant text-sm">{{ __t('admin.instant_buy.no_orders') }}</div>
    @endif
</div>
@endif
@endsection