<?php

namespace App\Http\Middleware;

use App\Services\TranslationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    protected TranslationService $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        // 1. Route parameter ({locale?} prefix)
        $locale = $request->route('locale');

        // 2. Query parameter (?lang=en)
        if (!$locale) {
            $locale = $request->get('lang');
        }

        if ($locale && in_array($locale, config('ecommerce.languages.supported', ['ar', 'en', 'fr']))) {
            $this->translationService->setLocale($locale);
        } else {
            $detected = $this->translationService->detectLocale();
            $this->translationService->setLocale($detected);
        }

        $isRtl = $this->translationService->isRtl();
        $languages = $this->translationService->getLanguages();
        $currentLocale = app()->getLocale();

        view()->share('languages', $languages);
        view()->share('current_locale', $currentLocale);
        view()->share('is_rtl', $isRtl);

        app('url')->defaults(['locale' => $currentLocale]);

        return $next($request);
    }
}
