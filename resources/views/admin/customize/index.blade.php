@extends('admin.layout')

@section('title', 'التخصيص')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold">التخصيص</h1>
        <p class="text-on-surface-variant text-sm mt-1">تخصيص مظهر المتجر: الثيم، الألوان، البنر الرئيسي، البانرات</p>
    </div>
    <form method="POST" action="{{ route('admin.customize.reset') }}" onsubmit="return confirm('استعادة الإعدادات الافتراضية؟')">
        @csrf
        <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-on-surface px-4 py-2 rounded-lg text-sm">
            <span class="material-symbols-outlined ml-1">undo</span>استعادة الافتراضي
        </button>
    </form>
</div>

<form method="POST" action="{{ route('admin.customize.update') }}" enctype="multipart/form-data">
    @csrf

    {{-- Theme --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5 mb-6">
        <h2 class="font-bold text-lg mb-4"><span class="material-symbols-outlined text-purple-600 ml-2">palette</span>الثيم</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($themes as $key => $theme)
                <label class="cursor-pointer relative block group">
                    <input type="radio" name="theme" value="{{ $key }}" {{ old('theme', $current['theme']) === $key ? 'checked' : '' }} class="peer hidden">
                    <div class="border-2 border-outline-variant peer-checked:border-primary peer-checked:bg-primary/5 rounded-xl p-4 hover:border-primary/50 transition duration-200 shadow-sm relative h-full flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="material-symbols-outlined text-xl text-primary">{{ $theme['icon'] }}</span>
                                <span class="font-bold text-sm">{{ $theme['name'] }}</span>
                            </div>
                            <p class="text-xs text-on-surface-variant leading-relaxed">{{ $theme['description'] }}</p>
                        </div>
                        <div class="flex gap-1.5 mt-4">
                            @foreach($theme['colors'] as $color)
                                <div class="w-6 h-6 rounded-md shadow-sm border border-black/10" style="background: {{ $color }}" title="{{ $color }}"></div>
                            @endforeach
                        </div>
                        {{-- Checked Indicator --}}
                        <div class="absolute top-2 left-2 bg-primary text-white w-5 h-5 rounded-full flex items-center justify-between opacity-0 scale-75 peer-checked:opacity-100 peer-checked:scale-100 transition duration-200">
                            <span class="material-symbols-outlined text-xs font-bold mx-auto">check</span>
                        </div>
                    </div>
                </label>
            @endforeach
        </div>
    </div>

    {{-- Colors --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5 mb-6">
        <h2 class="font-bold text-lg mb-4"><span class="material-symbols-outlined text-primary ml-2">colorize</span>الألوان</h2>
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">اللون الأساسي</label>
                <div class="flex items-center gap-3">
                                    <input type="color" id="primary_color_picker" value="{{ old('primary_color', $current['primary_color']) }}" class="w-16 h-12 rounded border-2 cursor-pointer">
                                    <input type="text" id="primary_color_display" value="{{ old('primary_color', $current['primary_color']) }}" class="flex-1 px-3 py-2 border rounded-lg font-mono text-sm" pattern="^#[0-9A-Fa-f]{6}$" maxlength="7">
                                    <input type="hidden" name="primary_color" id="primary_color" value="{{ old('primary_color', $current['primary_color']) }}">
                                </div>
                                <p class="text-xs text-on-surface-variant mt-1">يستخدم للأزرار والروابط والعناصر التفاعلية</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1">اللون الثانوي (Accent)</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" id="accent_color_picker" value="{{ old('accent_color', $current['accent_color']) }}" class="w-16 h-12 rounded border-2 cursor-pointer">
                                    <input type="text" id="accent_color_display" value="{{ old('accent_color', $current['accent_color']) }}" class="flex-1 px-3 py-2 border rounded-lg font-mono text-sm" pattern="^#[0-9A-Fa-f]{6}$" maxlength="7">
                                    <input type="hidden" name="accent_color" id="accent_color" value="{{ old('accent_color', $current['accent_color']) }}">
                                </div>
                                <p class="text-xs text-on-surface-variant mt-1">يستخدم للتخفيضات والشارات والعروض</p>
                            </div>
                        </div>
                        @push('scripts')
                        <script>
                            // Color picker <-> text <-> hidden, all in sync
                            function wireColor(pickerId, displayId, hiddenId) {
                                const picker = document.getElementById(pickerId);
                                const display = document.getElementById(displayId);
                                const hidden = document.getElementById(hiddenId);
                                if (!picker || !display || !hidden) return;
                                // picker -> text + hidden
                                picker.addEventListener('input', e => {
                                    display.value = e.target.value;
                                    hidden.value = e.target.value;
                                });
                                // text -> picker + hidden
                                display.addEventListener('input', e => {
                                    let v = e.target.value.trim();
                                    if (!v.startsWith('#')) v = '#' + v;
                                    if (/^#[0-9A-Fa-f]{6}$/.test(v)) {
                                        picker.value = v;
                                        hidden.value = v;
                                        display.value = v.toUpperCase();
                                    } else {
                                        hidden.value = v; // let server validation catch
                                    }
                                });
                            }
                            wireColor('primary_color_picker', 'primary_color_display', 'primary_color');
                            wireColor('accent_color_picker', 'accent_color_display', 'accent_color');
                            wireColor('top_bar_bg_color_picker', 'top_bar_bg_color_display', 'top_bar_bg_color');
                            wireColor('top_bar_text_color_picker', 'top_bar_text_color_display', 'top_bar_text_color');
                        </script>
        @endpush
        <script>
        // We need the hidden color inputs named "primary_color" / "accent_color" to be submitted, not the text
        </script>
    </div>

    {{-- Hero section --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5 mb-6">
        <h2 class="font-bold text-lg mb-4"><span class="material-symbols-outlined text-green-600 ml-2">image</span>القسم الرئيسي (Hero)</h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">الشارة (Badge)</label>
                <input type="text" name="hero_badge" value="{{ old('hero_badge', $current['hero_badge']) }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="جديد">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">العنوان <span class="text-red-500">*</span></label>
                <input type="text" name="hero_title" value="{{ old('hero_title', $current['hero_title']) }}" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('hero_title') border-red-500 @enderror">
                @error('hero_title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-1">العنوان الفرعي</label>
                <textarea name="hero_subtitle" rows="2" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('hero_subtitle', $current['hero_subtitle']) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-1">صورة خلفية الـ Hero</label>

                @php
                    $heroVal = $current['hero_image'];
                    $heroUrl = $heroVal && !preg_match('#^https?://#i', $heroVal) ? asset('storage/' . $heroVal) : $heroVal;
                @endphp

                @if($heroVal)
                    <div class="bg-surface-container-low border-2 border-dashed border-outline-variant rounded-lg p-3 mb-2 flex items-center gap-3">
                        <img src="{{ $heroUrl }}" alt="hero" class="h-20 w-32 object-cover rounded border">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-on-surface-variant truncate" dir="ltr">{{ $heroVal }}</p>
                            <p class="text-xs text-green-600 mt-0.5"><span class="material-symbols-outlined">check_circle</span> صورة حالية</p>
                        </div>
                        <button type="button" onclick="if(confirm('حذف صورة الـ Hero؟')) document.getElementById('remove-hero-form').submit()" class="bg-error-container hover:bg-error-container text-on-error-container px-3 py-1.5 rounded text-xs">
                            <span class="material-symbols-outlined">delete</span> حذف
                        </button>
                    </div>
                @endif

                <div class="grid md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-on-surface-variant">رفع ملف من الجهاز</label>
                        <input type="file" name="hero_image_file" accept="image/jpeg,image/jpg,image/png,image/webp" class="w-full text-sm @error('hero_image_file') border-red-500 @enderror">
                        <p class="text-xs text-on-surface-variant mt-1">
                            <span class="material-symbols-outlined ml-1">info</span>JPEG, PNG, WEBP — حتى 2MB<br>
                            <span class="inline-block bg-blue-50 text-blue-700 px-1.5 py-0.5 rounded mt-0.5">موصى به: 1920×900 بكسل</span>
                        </p>
                        @error('hero_image_file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-on-surface-variant">أو رابط URL خارجي</label>
                        <input type="url" name="hero_image" value="{{ old('hero_image', $heroVal && preg_match('#^https?://#i', $heroVal) ? $heroVal : '') }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 font-mono text-sm" placeholder="https://...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Banners --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5 mb-6">
        <h2 class="font-bold text-lg mb-4"><span class="material-symbols-outlined text-orange-600 ml-2">campaign</span>البانرات الإعلانية</h2>
        <div class="grid md:grid-cols-2 gap-6">
            @for($i=1; $i<=2; $i++)
                <div class="border-2 border-dashed border-outline-variant rounded-xl p-4">
                    <h3 class="font-bold text-sm text-on-surface mb-3">
                        <span class="material-symbols-outlined ml-1">image</span>بانر {{ $i }}
                    </h3>
                    <div class="space-y-3">
                        <input type="text" name="banner_{{ $i }}_title" value="{{ old("banner_{$i}_title", $current["banner_{$i}_title"]) }}" placeholder="عنوان البانر" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                        <input type="text" name="banner_{{ $i }}_subtitle" value="{{ old("banner_{$i}_subtitle", $current["banner_{$i}_subtitle"]) }}" placeholder="العنوان الفرعي" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">

                        @php
                            $bVal = $current["banner_{$i}_image"];
                            $bUrl = $bVal && !preg_match('#^https?://#i', $bVal) ? asset('storage/' . $bVal) : $bVal;
                        @endphp

                        @if($bVal)
                            <div class="bg-surface-container-low border border-dashed border-outline-variant rounded p-2 flex items-center gap-2">
                                <img src="{{ $bUrl }}" alt="banner {{ $i }}" class="h-12 w-20 object-cover rounded border">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-on-surface-variant truncate" dir="ltr">{{ $bVal }}</p>
                                </div>
                                <button type="button" onclick="if(confirm('حذف صورة البانر {{ $i }}؟')) document.getElementById('remove-banner-{{ $i }}-form').submit()" class="bg-error-container hover:bg-error-container text-on-error-container px-2 py-1 rounded text-xs">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-on-surface-variant">رفع ملف</label>
                                <input type="file" name="banner_{{ $i }}_image_file" accept="image/jpeg,image/jpg,image/png,image/webp" class="w-full text-xs @error("banner_{$i}_image_file") border-red-500 @enderror">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-on-surface-variant">أو URL</label>
                                <input type="url" name="banner_{{ $i }}_image" value="{{ old("banner_{$i}_image", $bVal && preg_match('#^https?://#i', $bVal) ? $bVal : '') }}" placeholder="https://..." class="w-full px-2 py-1.5 border rounded focus:ring-2 focus:ring-blue-500 text-xs font-mono">
                            </div>
                        </div>
                        <input type="url" name="banner_{{ $i }}_link" value="{{ old("banner_{$i}_link", $current["banner_{$i}_link"]) }}" placeholder="رابط عند النقر (اختياري)" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                </div>
            @endfor
        </div>
    </div>

    {{-- Sections visibility --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5 mb-6">
        <h2 class="font-bold text-lg mb-4"><span class="material-symbols-outlined text-indigo-600 ml-2">visibility</span>إظهار الأقسام</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-surface-container-low">
                <input type="checkbox" name="show_featured" value="1" {{ (old('_token') ? old('show_featured') : $current['show_featured']) == '1' ? 'checked' : '' }} class="w-5 h-5 text-primary rounded">
                <span class="text-sm font-semibold">المنتجات المميزة</span>
            </label>
            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-surface-container-low">
                <input type="checkbox" name="show_latest" value="1" {{ (old('_token') ? old('show_latest') : $current['show_latest']) == '1' ? 'checked' : '' }} class="w-5 h-5 text-primary rounded">
                <span class="text-sm font-semibold">أحدث المنتجات</span>
            </label>
            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-surface-container-low">
                <input type="checkbox" name="show_categories" value="1" {{ (old('_token') ? old('show_categories') : $current['show_categories']) == '1' ? 'checked' : '' }} class="w-5 h-5 text-primary rounded">
                <span class="text-sm font-semibold">التصنيفات</span>
            </label>
            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-surface-container-low">
                <input type="checkbox" name="show_newsletter" value="1" {{ (old('_token') ? old('show_newsletter') : $current['show_newsletter']) == '1' ? 'checked' : '' }} class="w-5 h-5 text-primary rounded">
                <span class="text-sm font-semibold">النشرة البريدية</span>
            </label>
        </div>
    </div>

    {{-- Header links visibility --}}
    @php
        $selectedCategories = json_decode($current['nav_categories_list'] ?? '[]', true) ?: [];
        $selectedPages = json_decode($current['nav_pages_list'] ?? '[]', true) ?: [];
    @endphp
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5 mb-6">
        <h2 class="font-bold text-lg mb-4"><span class="material-symbols-outlined text-indigo-600 ml-2">link</span>روابط الهيدر (Header Navigation)</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-surface-container-low">
                <input type="checkbox" name="nav_show_home" value="1" {{ (old('_token') ? old('nav_show_home') : $current['nav_show_home']) == '1' ? 'checked' : '' }} class="w-5 h-5 text-primary rounded">
                <span class="text-sm font-semibold">الرئيسية</span>
            </label>
            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-surface-container-low">
                <input type="checkbox" name="nav_show_products" value="1" {{ (old('_token') ? old('nav_show_products') : $current['nav_show_products']) == '1' ? 'checked' : '' }} class="w-5 h-5 text-primary rounded">
                <span class="text-sm font-semibold">المنتجات</span>
            </label>
            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-surface-container-low">
                <input type="checkbox" name="nav_show_categories" value="1" {{ (old('_token') ? old('nav_show_categories') : $current['nav_show_categories']) == '1' ? 'checked' : '' }} class="w-5 h-5 text-primary rounded">
                <span class="text-sm font-semibold">التصنيفات</span>
            </label>
            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-surface-container-low">
                <input type="checkbox" name="nav_show_contact" value="1" {{ (old('_token') ? old('nav_show_contact') : $current['nav_show_contact']) == '1' ? 'checked' : '' }} class="w-5 h-5 text-primary rounded">
                <span class="text-sm font-semibold">اتصل بنا</span>
            </label>
        </div>

        <div class="mt-5 grid md:grid-cols-2 gap-6 pt-4 border-t border-outline-variant/30">
            <div>
                <label class="block text-sm font-semibold mb-1">الحد الأقصى لعدد التصنيفات المعروضة في الهيدر</label>
                <input type="number" name="nav_categories_limit" value="{{ old('nav_categories_limit', $current['nav_categories_limit']) }}" min="1" max="10" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="mt-5 pt-4 border-t border-outline-variant/30">
            <label class="block text-sm font-semibold mb-2">اختر التصنيفات التي تظهر في الهيدر (في حال تفعيل قسم التصنيفات):</label>
            <div class="flex flex-wrap gap-2">
                @foreach($categories as $cat)
                    <label class="flex items-center gap-2 p-2.5 border rounded-lg cursor-pointer hover:bg-gray-50 text-xs">
                        <input type="checkbox" name="nav_categories_list[]" value="{{ $cat->id }}" {{ in_array($cat->id, $selectedCategories) ? 'checked' : '' }} class="w-4 h-4 text-primary rounded">
                        <span>{{ $cat->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="mt-5 pt-4 border-t border-outline-variant/30">
            <label class="block text-sm font-semibold mb-2">اختر الصفحات الثابتة التي تظهر في الهيدر:</label>
            <div class="flex flex-wrap gap-2">
                @foreach($pages as $p)
                    <label class="flex items-center gap-2 p-2.5 border rounded-lg cursor-pointer hover:bg-gray-50 text-xs">
                        <input type="checkbox" name="nav_pages_list[]" value="{{ $p->id }}" {{ in_array($p->id, $selectedPages) ? 'checked' : '' }} class="w-4 h-4 text-primary rounded">
                        <span>{{ $p->title }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Top Announcement Bar --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5 mb-6">
        <h2 class="font-bold text-lg mb-4"><span class="material-symbols-outlined text-blue-600 ml-2">ad_units</span>شريط الإعلان العلوي (Announcement Bar)</h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-surface-container-low max-w-xs">
                    <input type="checkbox" name="top_bar_show" value="1" {{ (old('_token') ? old('top_bar_show') : $current['top_bar_show']) == '1' ? 'checked' : '' }} class="w-5 h-5 text-primary rounded">
                    <span class="text-sm font-semibold">تفعيل شريط الإعلان العلوي</span>
                </label>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-1">نص الإعلان</label>
                <input type="text" name="top_bar_text" value="{{ old('top_bar_text', $current['top_bar_text']) }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="شحن مجاني للطلبات فوق 200 ريال!">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2">لون الخلفية</label>
                <div class="flex items-center gap-3">
                    <input type="color" id="top_bar_bg_color_picker" value="{{ old('top_bar_bg_color', $current['top_bar_bg_color']) }}" class="w-16 h-12 rounded border-2 cursor-pointer">
                    <input type="text" id="top_bar_bg_color_display" value="{{ old('top_bar_bg_color', $current['top_bar_bg_color']) }}" class="flex-1 px-3 py-2 border rounded-lg font-mono text-sm" pattern="^#[0-9A-Fa-f]{6}$" maxlength="7">
                    <input type="hidden" name="top_bar_bg_color" id="top_bar_bg_color" value="{{ old('top_bar_bg_color', $current['top_bar_bg_color']) }}">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2">لون النص</label>
                <div class="flex items-center gap-3">
                    <input type="color" id="top_bar_text_color_picker" value="{{ old('top_bar_text_color', $current['top_bar_text_color']) }}" class="w-16 h-12 rounded border-2 cursor-pointer">
                    <input type="text" id="top_bar_text_color_display" value="{{ old('top_bar_text_color', $current['top_bar_text_color']) }}" class="flex-1 px-3 py-2 border rounded-lg font-mono text-sm" pattern="^#[0-9A-Fa-f]{6}$" maxlength="7">
                    <input type="hidden" name="top_bar_text_color" id="top_bar_text_color" value="{{ old('top_bar_text_color', $current['top_bar_text_color']) }}">
                </div>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-1">رابط الإعلان (اختياري)</label>
                <input type="url" name="top_bar_link" value="{{ old('top_bar_link', $current['top_bar_link']) }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 font-mono text-sm" placeholder="https://...">
            </div>
        </div>
    </div>

    {{-- WhatsApp Floating Button --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5 mb-6">
        <h2 class="font-bold text-lg mb-4"><span class="material-symbols-outlined text-green-600 ml-2">chat</span>زر الواتساب العائم (WhatsApp Button)</h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-surface-container-low max-w-xs">
                    <input type="checkbox" name="whatsapp_btn_show" value="1" {{ (old('_token') ? old('whatsapp_btn_show') : $current['whatsapp_btn_show']) == '1' ? 'checked' : '' }} class="w-5 h-5 text-primary rounded">
                    <span class="text-sm font-semibold">تفعيل زر الواتساب العائم</span>
                </label>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">رقم الواتساب <span class="text-xs text-on-surface-variant">(مع رمز الدولة، مثلاً: 966500000000)</span></label>
                <input type="text" name="whatsapp_btn_phone" value="{{ old('whatsapp_btn_phone', $current['whatsapp_btn_phone']) }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 font-mono text-sm" placeholder="966500000000">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">موضع الزر في الشاشة</label>
                <select name="whatsapp_btn_position" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="right" {{ old('whatsapp_btn_position', $current['whatsapp_btn_position']) === 'right' ? 'selected' : '' }}>أسفل اليمين</option>
                    <option value="left" {{ old('whatsapp_btn_position', $current['whatsapp_btn_position']) === 'left' ? 'selected' : '' }}>أسفل اليسار</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-1">نص الرسالة الافتراضي عند النقر</label>
                <input type="text" name="whatsapp_btn_text" value="{{ old('whatsapp_btn_text', $current['whatsapp_btn_text']) }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="مرحباً، أود الاستفسار عن المنتجات">
            </div>
        </div>
    </div>

    {{-- Instant Buy settings (Notice) --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5 mb-6 border border-outline-variant">
        <h2 class="font-bold text-lg mb-2 flex items-center gap-2 text-purple-600">
            <span class="material-symbols-outlined text-purple-600">bolt</span>
            إعدادات فورم الطلب الفوري وطرق الدفع
        </h2>
        <p class="text-sm text-on-surface-variant mb-3">لقد تم نقل إعدادات حقول فورم الطلب الفوري وتفعيل التحويل البنكي إلى قسم الإعدادات العامة لسهولة الإدارة.</p>
        <a href="{{ route('admin.settings.index', ['tab' => 'checkout']) }}" class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm transition">
            <span class="material-symbols-outlined text-sm">settings</span>
            الذهاب لإعدادات الفورم
        </a>
    </div>

    {{-- Footer --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5 mb-6">
        <h2 class="font-bold text-lg mb-4"><span class="material-symbols-outlined text-teal-600 ml-2">directions_walk</span>التذييل (Footer)</h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-1">نبذة عن المتجر (في التذييل)</label>
                <textarea name="footer_about" rows="3" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('footer_about', $current['footer_about']) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-1">نص حقوق الملكية</label>
                <input type="text" name="footer_copyright" value="{{ old('footer_copyright', $current['footer_copyright']) }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            
            {{-- Contact info --}}
            <div class="md:col-span-2 mt-4 pt-4 border-t border-outline-variant/30">
                <h3 class="font-bold text-sm text-gray-800 mb-1 flex items-center gap-1.5"><span class="material-symbols-outlined text-base text-teal-500">call</span>بيانات الاتصال بالتذييل</h3>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">رقم هاتف الاتصال</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone', $current['contact_phone']) }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 font-mono text-sm" placeholder="+249 90 000 0000">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">البريد الإلكتروني للاتصال</label>
                <input type="email" name="contact_email" value="{{ old('contact_email', $current['contact_email']) }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" placeholder="info@store.com">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">رقم واتساب للتواصل</label>
                <input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp', $current['contact_whatsapp']) }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 font-mono text-sm" placeholder="966500000000">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">عنوان المقر / المحل</label>
                <input type="text" name="contact_address" value="{{ old('contact_address', $current['contact_address']) }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" placeholder="الخرطوم، السودان">
            </div>

            {{-- Social links --}}
            <div class="md:col-span-2 mt-4 pt-4 border-t border-outline-variant/30">
                <h3 class="font-bold text-sm text-gray-800 mb-1 flex items-center gap-1.5"><span class="material-symbols-outlined text-base text-teal-500">share</span>روابط التواصل الاجتماعي</h3>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">رابط فيسبوك (Facebook)</label>
                <input type="url" name="facebook_url" value="{{ old('facebook_url', $current['facebook_url']) }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 font-mono text-sm" placeholder="https://facebook.com/...">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">رابط تويتر / إكس (Twitter/X)</label>
                <input type="url" name="twitter_url" value="{{ old('twitter_url', $current['twitter_url']) }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 font-mono text-sm" placeholder="https://x.com/...">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">رابط إنستغرام (Instagram)</label>
                <input type="url" name="instagram_url" value="{{ old('instagram_url', $current['instagram_url']) }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 font-mono text-sm" placeholder="https://instagram.com/...">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">رقم واتساب العادي للدردشة (رقم مختلف أو نفس رقم الزر)</label>
                <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $current['whatsapp_number']) }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 font-mono text-sm" placeholder="966500000000">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-1">رابط يوتيوب (YouTube)</label>
                <input type="url" name="youtube_url" value="{{ old('youtube_url', $current['youtube_url']) }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 font-mono text-sm" placeholder="https://youtube.com/...">
            </div>
        </div>
    </div>

    <div class="bg-surface-container-lowest rounded-xl shadow-sm p-5 flex items-center justify-between">
        <p class="text-sm text-on-surface-variant">التغييرات تُحفظ فوراً وتظهر في الواجهة الأمامية للمتجر</p>
        <button type="submit" class="bg-primary hover:bg-primary text-white px-6 py-2.5 rounded-lg font-semibold flex items-center gap-2">
            <span class="material-symbols-outlined">save</span>حفظ كل التخصيصات
        </button>
    </div>
</form>

{{-- Standalone forms for image removal (must live outside the main form) --}}
<form id="remove-hero-form" method="POST" action="{{ route('admin.customize.removeImage') }}" style="display:none">
    @csrf
    <input type="hidden" name="key" value="hero_image">
</form>
@for($i = 1; $i <= 2; $i++)
    <form id="remove-banner-{{ $i }}-form" method="POST" action="{{ route('admin.customize.removeImage') }}" style="display:none">
        @csrf
        <input type="hidden" name="key" value="banner_{{ $i }}_image">
    </form>
@endfor

@endsection

@push('scripts')
<script>
    // Sync color input with text preview (no extra submit value)
    document.querySelectorAll('input[type=color]').forEach(colorInput => {
        const form = colorInput.closest('form');
        const textInput = colorInput.parentElement.querySelector('input[type=text]');
        colorInput.addEventListener('input', e => {
            if (textInput) textInput.value = e.target.value;
        });
    });
</script>
@endpush
