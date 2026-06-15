<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CustomizeController extends Controller
{
    public const HERO_IMAGE_MAX_KB = 2048;   // 2MB
    public const HERO_IMAGE_MIMES = 'jpeg,jpg,png,webp';
    public const HERO_IMAGE_RECOMMENDED = '1920×900 بكسل (عريض، 16:9 تقريباً)';

    public function index(): View
    {
        $themes = $this->themes();
        $banners = $this->banners();
        $current = [
            'theme' => Setting::get('site_theme', 'light'),
            'primary_color' => Setting::get('primary_color', '#2563eb'),
            'accent_color' => Setting::get('accent_color', '#f59e0b'),
            'hero_title' => Setting::get('hero_title', 'تسوق بذكاء، عش تجربة فريدة'),
            'hero_subtitle' => Setting::get('hero_subtitle', 'اكتشف أحدث المنتجات بأسعار مميزة مع شحن سريع لجميع الدول العربية'),
            'hero_badge' => Setting::get('hero_badge', 'جديد'),
            'hero_image' => Setting::get('hero_image', ''),
            'banner_1_title' => Setting::get('banner_1_title', ''),
            'banner_1_subtitle' => Setting::get('banner_1_subtitle', ''),
            'banner_1_image' => Setting::get('banner_1_image', ''),
            'banner_1_link' => Setting::get('banner_1_link', ''),
            'banner_2_title' => Setting::get('banner_2_title', ''),
            'banner_2_subtitle' => Setting::get('banner_2_subtitle', ''),
            'banner_2_image' => Setting::get('banner_2_image', ''),
            'banner_2_link' => Setting::get('banner_2_link', ''),
            'show_newsletter' => Setting::get('show_newsletter', '1'),
            'show_featured' => Setting::get('show_featured', '1'),
            'show_latest' => Setting::get('show_latest', '1'),
            'show_categories' => Setting::get('show_categories', '1'),
            'footer_about' => Setting::get('footer_about', 'متجر إلكتروني متكامل يوفر لك تجربة تسوق فريدة مع شحن سريع ودفع آمن عند الاستلام.'),
            'footer_copyright' => Setting::get('footer_copyright', 'جميع الحقوق محفوظة'),
        ];
        return view('admin.customize.index', compact('themes', 'banners', 'current'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'theme' => 'required|in:light,dark,colorful,minimal',
            'primary_color' => ['required', 'string', function ($attr, $value, $fail) {
                $v = strtoupper(trim($value));
                if (!preg_match('/^#[0-9A-F]{6}$/', $v)) {
                    $fail('اللون الأساسي يجب أن يكون بصيغة #RRGGBB (مثال: #2563EB)');
                }
            }],
            'accent_color' => ['required', 'string', function ($attr, $value, $fail) {
                $v = strtoupper(trim($value));
                if (!preg_match('/^#[0-9A-F]{6}$/', $v)) {
                    $fail('اللون الثانوي يجب أن يكون بصيغة #RRGGBB (مثال: #F59E0B)');
                }
            }],
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'nullable|string|max:500',
            'hero_badge' => 'nullable|string|max:50',
            'hero_image' => 'nullable|string|max:500',
            'hero_image_file' => 'nullable|file|mimes:' . self::HERO_IMAGE_MIMES . '|max:' . self::HERO_IMAGE_MAX_KB,
            'banner_1_title' => 'nullable|string|max:255',
            'banner_1_subtitle' => 'nullable|string|max:500',
            'banner_1_image' => 'nullable|string|max:500',
            'banner_1_image_file' => 'nullable|file|mimes:' . self::HERO_IMAGE_MIMES . '|max:' . self::HERO_IMAGE_MAX_KB,
            'banner_1_link' => 'nullable|url',
            'banner_2_title' => 'nullable|string|max:255',
            'banner_2_subtitle' => 'nullable|string|max:500',
            'banner_2_image' => 'nullable|string|max:500',
            'banner_2_image_file' => 'nullable|file|mimes:' . self::HERO_IMAGE_MIMES . '|max:' . self::HERO_IMAGE_MAX_KB,
            'banner_2_link' => 'nullable|url',
            'show_newsletter' => 'boolean',
            'show_featured' => 'boolean',
            'show_latest' => 'boolean',
            'show_categories' => 'boolean',
            'footer_about' => 'nullable|string|max:1000',
            'footer_copyright' => 'nullable|string|max:255',
        ]);

        $checkboxKeys = ['show_newsletter', 'show_featured', 'show_latest', 'show_categories'];

        // Persist text fields first
        foreach ($data as $key => $value) {
            if (str_ends_with($key, '_file')) continue;
            // Normalize color values to uppercase #RRGGBB
            if (in_array($key, ['primary_color', 'accent_color'], true)) {
                $value = strtoupper(trim($value));
            }
            if (in_array($key, $checkboxKeys)) {
                Setting::set($key, $value ? '1' : '0', 'customize');
            } else {
                Setting::set($key, (string) $value, 'customize');
            }
        }

        // Then handle file uploads (only if user picked a file, it overrides the text)
        $imageMap = [
            'hero_image_file'       => ['key' => 'hero_image',     'folder' => 'hero'],
            'banner_1_image_file'   => ['key' => 'banner_1_image', 'folder' => 'banners'],
            'banner_2_image_file'   => ['key' => 'banner_2_image', 'folder' => 'banners'],
        ];
        foreach ($imageMap as $input => $info) {
            if ($request->hasFile($input)) {
                $path = $this->uploadImage(
                    $request->file($input),
                    $info['folder'],
                    self::HERO_IMAGE_MAX_KB,
                    self::HERO_IMAGE_MIMES,
                    Setting::get($info['key'])
                );
                if ($path) Setting::set($info['key'], $path, 'customize');
            }
        }

        Cache::forget('site_settings');

        return redirect()->route('admin.customize.index')->with('success', 'تم حفظ التخصيصات');
    }

    public function removeImage(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'key' => 'required|in:hero_image,banner_1_image,banner_2_image',
        ]);
        $current = Setting::get($data['key']);
        if ($current && !preg_match('#^https?://#i', $current) && Storage::disk('public')->exists($current)) {
            Storage::disk('public')->delete($current);
        }
        Setting::set($data['key'], '', 'customize');
        Cache::forget('site_settings');
        return back()->with('success', 'تم حذف الصورة');
    }

    public function reset(): RedirectResponse
    {
        $defaults = [
            'site_theme' => 'light',
            'primary_color' => '#2563eb',
            'accent_color' => '#f59e0b',
            'hero_title' => 'تسوق بذكاء، عش تجربة فريدة',
            'hero_subtitle' => 'اكتشف أحدث المنتجات بأسعار مميزة',
            'show_newsletter' => '1',
            'show_featured' => '1',
            'show_latest' => '1',
            'show_categories' => '1',
        ];
        foreach ($defaults as $k => $v) {
            Setting::set($k, $v, 'customize');
        }
        Cache::forget('site_settings');
        return redirect()->route('admin.customize.index')->with('success', 'تم استعادة الإعدادات الافتراضية');
    }

    private function uploadImage($file, string $folder, int $maxKb, string $mimes, ?string $oldPath = null): ?string
    {
        if (!$file || !$file->isValid()) return null;
        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $filename = $folder . '/' . Str::random(20) . '.' . $ext;
        $file->storeAs(dirname($filename), basename($filename), 'public');
        if ($oldPath && !preg_match('#^https?://#i', $oldPath) && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
        return $filename;
    }

    private function themes(): array
    {
        return [
            'light'    => ['name' => 'فاتح', 'icon' => 'fa-sun', 'description' => 'تصميم مشرق وألوان هادئة', 'colors' => ['#ffffff', '#f3f4f6', '#2563eb', '#f59e0b']],
            'dark'     => ['name' => 'داكن', 'icon' => 'fa-moon', 'description' => 'تصميم ليلي عصري', 'colors' => ['#0f172a', '#1e293b', '#3b82f6', '#f59e0b']],
            'colorful' => ['name' => 'ملون', 'icon' => 'fa-palette', 'description' => 'ألوان زاهية وجريئة', 'colors' => ['#fef3c7', '#fce7f3', '#ec4899', '#8b5cf6']],
            'minimal'  => ['name' => 'بسيط', 'icon' => 'fa-square', 'description' => 'تصميم نظيف وأبسط', 'colors' => ['#ffffff', '#f5f5f5', '#18181b', '#71717a']],
        ];
    }

    private function banners(): array
    {
        return [
            ['id' => 1, 'name' => 'بانر الشحن المجاني'],
            ['id' => 2, 'name' => 'بانر العروض الخاصة'],
        ];
    }
}
