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

    private ?string $measurementId;
    private ?string $apiSecret;

    public function __construct()
    {
        $this->measurementId = setting('experiments.ga4_measurement_id');
        $this->apiSecret = setting('experiments.ga4_api_secret');
    }

    public function handleVariationAssigned(VariationAssigned $event): void
    {
        $this->sendEvent(
            $event->visitorId,
            'view_experiment_variation',
            [
                'experiment_name' => $event->experiment->name,
                'variation_name' => $event->variation->name,
            ]
        );
    }

    public function handleConversionRecorded(ConversionRecorded $event): void
    {
        $this->sendEvent(
            $event->visitorId,
            'conversion',
            [
                'experiment_name' => $event->experiment->name,
                'variation_name' => $event->variation->name,
                'conversion_type' => $event->conversionType,
            ]
        );
    }

    private function sendEvent(string $clientId, string $eventName, array $params): void
    {
        if (empty($this->measurementId) || empty($this->apiSecret)) {
            return;
        }

        try {
            Http::post("https://www.google-analytics.com/mp/collect?api_secret={$this->apiSecret}&measurement_id={$this->measurementId}", [
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
                'measurement_id' => $this->measurementId,
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
