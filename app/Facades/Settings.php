<?php

namespace App\Facades;

use App\Services\SettingsService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Support\Collection all()
 * @method static mixed get(string $key, mixed $default = null)
 * @method static bool set(string $key, mixed $value)
 * @method static \Illuminate\Support\Collection group(string $groupSlug)
 * @method static \Illuminate\Support\Collection public()
 * @method static void clearCache()
 * @method static \Illuminate\Support\Collection allGroups()
 *
 * @see \App\Services\SettingsService
 */
class Settings extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SettingsService::class;
    }

    /**
     * Get the logo URL, falling back to /logo.png if not set.
     */
    public static function getLogoUrl(): string
    {
        $logo = static::get('logo');
        return $logo ?: asset('logo.png');
    }

    /**
     * Get the favicon URL, falling back to /favicon.png if not set.
     */
    public static function getFaviconUrl(): string
    {
        $favicon = static::get('favicon');
        return $favicon ?: asset('favicon.png');
    }

    /**
     * Get the Google Analytics 4 API Secret, if set.
     */
    public static function getGoogleAnalyticsApiSecret(): ?string
    {
        return static::get('google_analytics_api_secret');
    }
}
