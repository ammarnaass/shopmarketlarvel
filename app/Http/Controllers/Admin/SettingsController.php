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

class SettingsController extends Controller
{
    /**
     * Image upload limits (logos / favicon).
     */
    public const LOGO_MAX_KB  = 1024; // 1MB
    public const FAVICON_MAX_KB = 256; // 256KB
    public const LOGO_MIMES    = 'jpeg,jpg,png,webp,svg';
    public const FAVICON_MIMES = 'ico,png,svg';
    public const LOGO_RECOMMENDED = '500×500 بكسل (PNG شفاف موصى به)';
    public const FAVICON_RECOMMENDED = '64×64 أو 32×32 بكسل';

    private array $defaults = [
        'store' => [
            'store_name' => 'Amar Store',
            'store_email' => 'info@amarstore.com',
            'store_phone' => '+249 90 000 0000',
            'store_address' => 'الخرطوم، السودان',
            'store_description' => 'متجر إلكتروني متكامل يوفر لك تجربة تسوق فريدة',
            'store_logo' => '',
            'store_favicon' => '',
        ],
        'social' => [
            'facebook_url' => '',
            'twitter_url' => '',
            'instagram_url' => '',
            'whatsapp_number' => '',
            'youtube_url' => '',
        ],
        'contact' => [
            'contact_email' => 'info@amarstore.com',
            'contact_phone' => '+249 90 000 0000',
            'contact_whatsapp' => '',
            'contact_address' => 'الخرطوم، السودان',
            'contact_hours' => '24/7 - متاحون دائماً',
        ],
        'seo' => [
            'seo_meta_title' => '',
            'seo_meta_description' => '',
            'seo_meta_keywords' => '',
            'seo_og_image' => '',
        ],
        'checkout' => [
            'instant_enable_bank_transfer' => '0',
            'instant_show_email' => '1',
            'instant_req_email' => '0',
            'instant_show_state' => '1',
            'instant_req_state' => '0',
            'instant_show_district' => '1',
            'instant_req_district' => '0',
            'instant_show_zip' => '1',
            'instant_req_zip' => '0',
            'instant_show_notes' => '1',
            'instant_show_coupon' => '1',
        ],
    ];

    public function index(): View
    {
        $settings = [];
        foreach ($this->defaults as $group => $fields) {
            $settings[$group] = [];
            foreach ($fields as $key => $default) {
                $settings[$group][$key] = Setting::get($key, $default);
            }
        }

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'group' => 'required|in:store,social,contact,seo,currency,checkout',
        ]);

        $group = $data['group'];

        if ($group === 'checkout') {
            $checkboxKeys = [
                'instant_enable_bank_transfer',
                'instant_show_email', 'instant_req_email',
                'instant_show_state', 'instant_req_state',
                'instant_show_district', 'instant_req_district',
                'instant_show_zip', 'instant_req_zip',
                'instant_show_notes', 'instant_show_coupon'
            ];
            foreach ($checkboxKeys as $key) {
                Setting::set($key, $request->boolean($key) ? '1' : '0', 'checkout');
            }
        } else {
            $rules = $this->rulesFor($group);
            $validated = $request->validate($rules);

            // Persist each key
            foreach ($validated as $key => $value) {
                if (in_array($key, ['group', '_token'])) continue;
                
                // Skip overwriting local image paths with empty values
                if (in_array($key, ['store_logo', 'store_favicon', 'seo_og_image'], true) && empty($value)) {
                    continue;
                }
                
                Setting::set($key, (string) $value, $group);
            }
        }

        // For currency group: also update the env file default_country so it sticks
        if ($group === 'currency' && $request->filled('default_country')) {
            $this->updateEnvDefaultCountry($request->input('default_country'));
            // Also flush the SiteSettings cache completely so the change takes effect everywhere
            \App\Support\SiteSettings::flush();
        }

        // Handle file uploads (logo, favicon)
        if ($group === 'store') {
            if ($request->hasFile('store_logo_file')) {
                $path = $this->uploadImage(
                    $request->file('store_logo_file'),
                    'logos',
                    self::LOGO_MAX_KB,
                    self::LOGO_MIMES,
                    Setting::get('store_logo')
                );
                if ($path) Setting::set('store_logo', $path, 'store');
            }
            if ($request->hasFile('store_favicon_file')) {
                $path = $this->uploadImage(
                    $request->file('store_favicon_file'),
                    'favicons',
                    self::FAVICON_MAX_KB,
                    self::FAVICON_MIMES,
                    Setting::get('store_favicon')
                );
                if ($path) Setting::set('store_favicon', $path, 'store');
            }

            // If user typed an external URL in the text field, that wins
            if ($request->filled('store_logo') && $request->hasFile('store_logo_file') === false) {
                // user used URL field only — already saved above
            }
        }

        // SEO OG image
        if ($group === 'seo' && $request->hasFile('seo_og_image_file')) {
            $path = $this->uploadImage(
                $request->file('seo_og_image_file'),
                'seo',
                self::LOGO_MAX_KB,
                self::LOGO_MIMES,
                Setting::get('seo_og_image')
            );
            if ($path) Setting::set('seo_og_image', $path, 'seo');
        }

        Cache::forget('site_settings');

        return redirect()->route('admin.settings.index', ['#' . $group])
            ->with('success', 'تم حفظ الإعدادات بنجاح');
    }

    public function removeImage(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'key' => 'required|in:store_logo,store_favicon,seo_og_image',
        ]);
        $current = Setting::get($data['key']);
        if ($current && !preg_match('#^https?://#i', $current) && Storage::disk('public')->exists($current)) {
            Storage::disk('public')->delete($current);
        }
        Setting::set($data['key'], '', 'store');
        Cache::forget('site_settings');
        return back()->with('success', 'تم حذف الصورة');
    }

    private function uploadImage($file, string $folder, int $maxKb, string $mimes, ?string $oldPath = null): ?string
    {
        if (!$file || !$file->isValid()) return null;

        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $filename = $folder . '/' . Str::random(20) . '.' . $ext;
        $file->storeAs(dirname($filename), basename($filename), 'public');

        // Delete the old file if it was a local upload
        if ($oldPath && !preg_match('#^https?://#i', $oldPath) && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        return $filename;
    }

    private function rulesFor(string $group): array
    {
        return match ($group) {
            'store' => [
                'store_name' => 'required|string|max:255',
                'store_email' => 'required|email',
                'store_phone' => 'required|string|max:50',
                'store_address' => 'nullable|string|max:500',
                'store_description' => 'nullable|string|max:1000',
                'store_logo' => 'nullable|string|max:500',
                'store_favicon' => 'nullable|string|max:500',
                'store_logo_file' => 'nullable|file|mimes:' . self::LOGO_MIMES . '|max:' . self::LOGO_MAX_KB,
                'store_favicon_file' => 'nullable|file|mimes:' . self::FAVICON_MIMES . '|max:' . self::FAVICON_MAX_KB,
            ],
            'social' => [
                'facebook_url' => 'nullable|url',
                'twitter_url' => 'nullable|url',
                'instagram_url' => 'nullable|url',
                'youtube_url' => 'nullable|url',
                'whatsapp_number' => 'nullable|string|max:50',
            ],
            'contact' => [
                'contact_email' => 'required|email',
                'contact_phone' => 'required|string|max:50',
                'contact_whatsapp' => 'nullable|string|max:50',
                'contact_address' => 'nullable|string|max:500',
                'contact_hours' => 'nullable|string|max:100',
            ],
            'seo' => [
                'seo_meta_title' => 'nullable|string|max:255',
                'seo_meta_description' => 'nullable|string|max:500',
                'seo_meta_keywords' => 'nullable|string|max:500',
                'seo_og_image' => 'nullable|string|max:500',
                'seo_og_image_file' => 'nullable|file|mimes:' . self::LOGO_MIMES . '|max:' . self::LOGO_MAX_KB,
            ],
            'currency' => [
                'default_country' => 'required|string|size:2',
                'fallback_currency' => 'required|string|size:3',
            ],
            'checkout' => [
                'instant_enable_bank_transfer' => 'boolean',
                'instant_show_email' => 'boolean',
                'instant_req_email' => 'boolean',
                'instant_show_state' => 'boolean',
                'instant_req_state' => 'boolean',
                'instant_show_district' => 'boolean',
                'instant_req_district' => 'boolean',
                'instant_show_zip' => 'boolean',
                'instant_req_zip' => 'boolean',
                'instant_show_notes' => 'boolean',
                'instant_show_coupon' => 'boolean',
            ],
        };
    }

    /**
     * Update the APP_DEFAULT_COUNTRY in the .env file so config('ecommerce.default_country') picks it up.
     */
    private function updateEnvDefaultCountry(string $countryCode): void
    {
        $countryCode = strtoupper($countryCode);
        $envPath = base_path('.env');
        if (!file_exists($envPath)) return;
        $content = file_get_contents($envPath);
        // Try both APP_DEFAULT_COUNTRY and STORE_DEFAULT_COUNTRY
        if (preg_match('/^STORE_DEFAULT_COUNTRY=.*$/m', $content)) {
            $content = preg_replace('/^STORE_DEFAULT_COUNTRY=.*$/m', 'STORE_DEFAULT_COUNTRY=' . $countryCode, $content);
        } elseif (preg_match('/^APP_DEFAULT_COUNTRY=.*$/m', $content)) {
            $content = preg_replace('/^APP_DEFAULT_COUNTRY=.*$/m', 'APP_DEFAULT_COUNTRY=' . $countryCode, $content);
        } else {
            $content .= "\nSTORE_DEFAULT_COUNTRY=" . $countryCode . "\n";
        }
        file_put_contents($envPath, $content);
    }
}
