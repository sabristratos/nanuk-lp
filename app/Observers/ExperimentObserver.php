<?php

namespace App\Observers;

use App\Models\Experiment;
use Illuminate\Support\Facades\Cache;

class ExperimentObserver
{
    public function created(Experiment $experiment): void
    {
        $this->clearExperimentCache($experiment);
    }

    public function updated(Experiment $experiment): void
    {
        $this->clearExperimentCache($experiment);
    }

    public function deleted(Experiment $experiment): void
    {
        $this->clearExperimentCache($experiment);
    }

    protected function clearExperimentCache(Experiment $experiment): void
    {
        Cache::forget("experiment.{$experiment->id}");
        Cache::forget("experiment.{$experiment->id}.variations");
        Cache::forget("experiment.{$experiment->id}.configurations");
        Cache::forget("experiment.{$experiment->id}.content");
    }
} 