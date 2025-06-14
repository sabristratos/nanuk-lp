<?php

namespace App\Listeners;

use App\Events\ConversionRecorded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogConversionRecorded
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ConversionRecorded $event): void
    {
        Log::info('Experiment conversion recorded.', [
            'visitor_id' => $event->visitorId,
            'experiment_id' => $event->experiment->id,
            'experiment_name' => $event->experiment->name,
            'variation_id' => $event->variation->id,
            'variation_name' => $event->variation->name,
            'conversion_type' => $event->conversionType,
            'payload' => $event->payload,
        ]);
    }
}
