<?php

use App\Support\SiteSettings;

if (!function_exists('site')) {
    /**
     * Get a site setting value.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function site(string $key, $default = null)
    {
        return SiteSettings::get($key, $default);
    }
}

if (!function_exists('site_settings')) {
    /**
     * Get all site settings as an array.
     *
     * @return array
     */
    function site_settings(): array
    {
        return SiteSettings::all();
    }
}

if (!function_exists('site_flush')) {
    /**
     * Flush the settings cache. Called automatically when a Setting is written.
     */
    function site_flush(): void
    {
        SiteSettings::flush();
    }
}

if (!function_exists('countryCurrency')) {
    /**
     * Get currency symbol for a country code (e.g. SD -> ج.س, EG -> ج.م).
     * Reads from config('ecommerce.countries.{CODE}.currency_symbol').
     */
    function countryCurrency(string $countryCode): string
    {
        $countries = config('ecommerce.countries', []);
        return $countries[$countryCode]['currency_symbol'] ?? 'ج.س';
    }
}

if (!function_exists('countryDialCode')) {
    /**
     * Get dial code for a country (e.g. SD -> 249, EG -> 20).
     */
    function countryDialCode(string $countryCode): string
    {
        $countries = config('ecommerce.countries', []);
        return (string) ($countries[$countryCode]['dial_code'] ?? '249');
    }
}

if (!function_exists('currentCountry')) {
    /**
     * Get the user's currently selected country (from session) or the store default.
     */
    function currentCountry(): string
    {
        return session('selected_country', config('ecommerce.store.default_country', 'SD'));
    }
}

if (!function_exists('currentCurrency')) {
    /**
     * Get the current currency code (e.g. SDG, EGP) for the user's selected country.
     */
    function currentCurrency(): string
    {
        $country = currentCountry();
        $countries = config('ecommerce.countries', []);
        return $countries[$country]['currency'] ?? config('ecommerce.store.currency', 'SDG');
    }
}

if (!function_exists('currentCurrencySymbol')) {
    /**
     * Get the current currency symbol (e.g. ج.س, ج.م) for the user's selected country.
     */
    function currentCurrencySymbol(): string
    {
        $country = currentCountry();
        $countries = config('ecommerce.countries', []);
        return $countries[$country]['currency_symbol'] ?? config('ecommerce.store.currency_symbol', 'ج.س');
    }
}
