<?php

namespace App\Listeners;

use App\Events\ConversionRecorded;
use App\Events\VariationAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendEventToGoogleAnalytics implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        // Remove database access from constructor to prevent issues during package discovery
        // Settings will be retrieved when methods are actually called
    }

    public function handleVariationAssigned(VariationAssigned $event): void
    {
        $measurementId = setting('google_analytics_id') ?: setting('experiments.ga4_measurement_id');
        $apiSecret = setting('google_analytics_api_secret') ?: setting('experiments.ga4_api_secret');

        $this->sendEvent(
            $event->visitorId,
            'view_experiment_variation',
            [
                'experiment_name' => $event->experiment->name,
                'variation_name' => $event->variation->name,
            ],
            $measurementId,
            $apiSecret
        );
    }

    public function handleConversionRecorded(ConversionRecorded $event): void
    {
        $measurementId = setting('google_analytics_id') ?: setting('experiments.ga4_measurement_id');
        $apiSecret = setting('google_analytics_api_secret') ?: setting('experiments.ga4_api_secret');

        $this->sendEvent(
            $event->visitorId,
            'conversion',
            [
                'experiment_name' => $event->experiment->name,
                'variation_name' => $event->variation->name,
                'conversion_type' => $event->conversionType,
            ],
            $measurementId,
            $apiSecret
        );
    }

    private function sendEvent(string $clientId, string $eventName, array $params, ?string $measurementId, ?string $apiSecret): void
    {
        if (empty($measurementId) || empty($apiSecret)) {
            return;
        }

        try {
            Http::post("https://www.google-analytics.com/mp/collect?api_secret={$apiSecret}&measurement_id={$measurementId}", [
                'client_id' => $clientId,
                'events' => [
                    [
                        'name' => $eventName,
                        'params' => $params,
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send event to Google Analytics.', [
                'measurement_id' => $measurementId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            VariationAssigned::class,
            [self::class, 'handleVariationAssigned']
        );

        $events->listen(
            ConversionRecorded::class,
            [self::class, 'handleConversionRecorded']
        );
    }
}
