<?php

namespace App\Providers;

use App\View\Composers\ExperimentViewComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Services\ExperimentService;
use App\Services\ContentService;
use App\Models\Experiment;
use App\Models\Variation;

class ExperimentServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(
            '*',
            ExperimentViewComposer::class
        );
    }
} 