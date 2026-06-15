<?php

namespace App\Providers;

use App\Support\SiteSettings;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class SiteSettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share site settings with all views so we can use {{ site('key') }} and {{ site_settings() }}
        View::share('siteSettings', $this->getSettingsSafely());

        // Inject dynamic <style> tag for primary/accent colors into the head
        // (only on the frontend layout — we'll add the yield in the view itself).
    }

    private function getSettingsSafely(): array
    {
        try {
            if (Schema::hasTable('settings')) {
                return SiteSettings::all();
            }
        } catch (\Throwable $e) {
            // ignore during install
        }
        // Return hard-coded defaults
        return [
            'store_name' => 'Amar Store',
            'store_email' => 'info@amarstore.com',
            'store_phone' => '+249 90 000 0000',
            'store_address' => 'الخرطوم، السودان',
            'store_description' => 'متجر إلكتروني متكامل',
            'primary_color' => '#2563eb',
            'accent_color' => '#f59e0b',
            'hero_title' => 'تسوق بذكاء، عش تجربة فريدة',
            'hero_subtitle' => 'اكتشف أحدث المنتجات بأسعار مميزة',
            'hero_badge' => 'جديد',
            'hero_image' => '',
            'show_newsletter' => '1',
            'show_featured' => '1',
            'show_latest' => '1',
            'show_categories' => '1',
            'footer_about' => 'متجر إلكتروني متكامل',
            'footer_copyright' => 'جميع الحقوق محفوظة',
            'site_theme' => 'light',
        ];
    }
}
