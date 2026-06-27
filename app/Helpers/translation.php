<?php

use App\Services\TranslationService;

if (!function_exists('__t')) {
    function __t(string $key, array $replace = [], ?string $locale = null): string
    {
        return app(TranslationService::class)->get($key, $locale, $replace);
    }
}

if (!function_exists('current_locale')) {
    function current_locale(): string
    {
        return app()->getLocale() ?: config('ecommerce.languages.default', 'ar');
    }
}

if (!function_exists('is_rtl')) {
    function is_rtl(?string $locale = null): bool
    {
        return app(TranslationService::class)->isRtl($locale);
    }
}

if (!function_exists('current_direction')) {
    function current_direction(): string
    {
        return is_rtl() ? 'rtl' : 'ltr';
    }
}

if (!function_exists('lang_url')) {
    function lang_url(string $path = '/', ?string $locale = null): string
    {
        $locale = $locale ?: current_locale();
        $defaultLocale = config('ecommerce.languages.default', 'ar');
        $hideDefault = config('ecommerce.languages.hide_default_prefix', true);

        if ($hideDefault && $locale === $defaultLocale) {
            return url($path);
        }

        return url($locale . '/' . ltrim($path, '/'));
    }
}

if (!function_exists('lang_route')) {
    function lang_route(string $name, array $parameters = [], bool $absolute = true): string
    {
        $locale = $parameters['locale'] ?? current_locale();
        $defaultLocale = config('ecommerce.languages.default', 'ar');
        $hideDefault = config('ecommerce.languages.hide_default_prefix', true);

        if (!isset($parameters['locale'])) {
            if ($hideDefault && $locale === $defaultLocale) {
                unset($parameters['locale']);
            } else {
                $parameters['locale'] = $locale;
            }
        }

        return route($name, $parameters, $absolute);
    }
}
