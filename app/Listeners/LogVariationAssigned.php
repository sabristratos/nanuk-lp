<?php

namespace App\Listeners;

use App\Events\VariationAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogVariationAssigned
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
    public function handle(VariationAssigned $event): void
    {
        Log::info('Visitor assigned to experiment variation.', [
            'visitor_id' => $event->visitorId,
            'experiment_id' => $event->experiment->id,
            'experiment_name' => $event->experiment->name,
            'variation_id' => $event->variation->id,
            'variation_name' => $event->variation->name,
        ]);
    }
}
