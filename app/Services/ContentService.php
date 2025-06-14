<?php

namespace App\Services;

use App\Models\ContentVariation;
use Illuminate\Support\Facades\Cache;
use App\Models\Variation;

class ContentService
{
    public function get(int $variationId, string $key, ?string $default = null): ?string
    {
        $cacheKey = "content.item.{$variationId}.{$key}";

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($variationId, $key) {
            $item = ContentVariation::where('variation_id', $variationId)
                ->where('element_selector', $key)
                ->first();

            if (! $item) {
                return null;
            }

            // Get the current locale's content or fallback to the first available
            return $item->getTranslation('content_value', app()->getLocale())
                ?? $item->getTranslation('content_value', config('app.fallback_locale'));
        });
    }

    public function getContentForVariation(int $variationId): array
    {
        $cacheKey = "content.collection.{$variationId}";

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($variationId) {
            return ContentVariation::where('variation_id', $variationId)
                ->get()
                ->mapWithKeys(function (ContentVariation $item) {
                    $contentValue = $item->getTranslation('content_value', app()->getLocale())
                        ?? $item->getTranslation('content_value', config('app.fallback_locale'));

                    return [$item->element_selector => $contentValue];
                })
                ->toArray();
        });
    }
} 