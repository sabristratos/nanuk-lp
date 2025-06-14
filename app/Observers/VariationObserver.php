<?php

namespace App\Observers;

use App\Models\Variation;
use Illuminate\Support\Facades\Cache;

class VariationObserver
{
    public function created(Variation $variation): void
    {
        $this->clearVariationCache($variation);
    }

    public function updated(Variation $variation): void
    {
        $this->clearVariationCache($variation);
    }

    public function deleted(Variation $variation): void
    {
        $this->clearVariationCache($variation);
    }

    protected function clearVariationCache(Variation $variation): void
    {
        Cache::forget("variation.{$variation->id}");
        Cache::forget("variation.{$variation->id}.configurations");
        Cache::forget("variation.{$variation->id}.content");
        Cache::forget("experiment.{$variation->experiment_id}.variations");
    }
} 