<?php

namespace App\Providers;

use App\Events\ConversionRecorded;
use App\Events\SubmissionCreated;
use App\Events\VariationAssigned;
use App\Listeners\LogConversionRecorded;
use App\Listeners\LogFailedLoginAttempt;
use App\Listeners\LogVariationAssigned;
use App\Listeners\SendEventToGoogleAnalytics;
use App\Listeners\SendSubmissionToWebhook;
use App\Models\Experiment;
use App\Models\Variation;
use App\Observers\ExperimentObserver;
use App\Observers\VariationObserver;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Failed::class => [
            LogFailedLoginAttempt::class,
        ],
        VariationAssigned::class => [
            LogVariationAssigned::class,
        ],
        ConversionRecorded::class => [
            LogConversionRecorded::class,
        ],
        SubmissionCreated::class => [
            SendSubmissionToWebhook::class,
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        SendEventToGoogleAnalytics::class,
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Experiment::observe(ExperimentObserver::class);
        Variation::observe(VariationObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
