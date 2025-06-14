<?php

declare(strict_types=1);

namespace App\Services;

use App\Facades\Settings;
use Illuminate\Support\Facades\Cache;

class LocaleService
{
    /**
     * The cache key for available locales.
     */
    protected const CACHE_KEY = 'locales.available';

    /**
     * The cache duration in seconds (24 hours).
     */
    protected const CACHE_TTL = 86400;

    /**
     * Get the locales that are configured as active in the settings.
     *
     * This method fetches the 'available_languages' setting, caches it,
     * and returns an array of language codes.
     *
     * @return array
     */
    public function getAvailableLocales(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            $locales = Settings::get('available_languages');

            if (is_array($locales)) {
                return $locales;
            }

            // Fallback to the default locale from the app config if setting is missing or invalid.
            return [config('app.locale', 'fr')];
        });
    }

    /**
     * Get the default application locale.
     *
     * It prioritizes the locale set in the environment configuration.
     *
     * @return string
     */
    public function getDefaultLocale(): string
    {
        return config('app.locale', 'fr');
    }

    /**
     * Get an associative array of available locales with their names.
     *
     * This is useful for populating dropdowns and other UI elements.
     * It uses the available locales and gets their display names from the main app config.
     *
     * @return array<string, string>
     */
    public function getAvailableLocalesForSelect(): array
    {
        $allLocales = config('app.available_locales', []);
        $activeLocales = $this->getAvailableLocales();
 
        // Ensure that only string values are passed to array_flip.
        $safeActiveLocales = array_filter($activeLocales, 'is_string');
 
        return array_intersect_key($allLocales, array_flip($safeActiveLocales));
    }

    /**
     * Clear the cached list of available locales.
     *
     * This should be called whenever the language settings are updated.
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
} 