<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Experiment;
use App\Models\ExperimentMetric;
use App\Models\ExperimentResult;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class MetricsService
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function recordMetric(Experiment $experiment, Variation $variation, string $metricName, float $value): ExperimentMetric
    {
        /** @var ExperimentMetric $metric */
        $metric = $experiment->metrics()->create([
            'variation_id' => $variation->id,
            'metric_name' => $metricName,
            'metric_value' => $value,
            'recorded_at' => now(),
        ]);
        
        return $metric;
    }

    public function recordConversion(Experiment $experiment, Variation $variation, string $conversionType = 'default', array $payload = [], ?string $visitorId = null): ExperimentResult
    {
        if ($visitorId === null) {
            $visitorId = Cookie::get('nanuk_visitor_id');
            if (!$visitorId) {
                // Fallback if cookie is not set for some reason
                $visitorId = $this->request->fingerprint() ?? $this->request->ip();
            }
        }

        /** @var ExperimentResult $result */
        $result = $experiment->results()->create([
            'variation_id' => $variation->id,
            'visitor_id' => $visitorId,
            'conversion_type' => $conversionType,
            'payload' => $payload,
        ]);

        // Dispatch an event for external services
        \App\Events\ConversionRecorded::dispatch(
            $experiment,
            $variation,
            $visitorId,
            $conversionType,
            $payload
        );

        return $result;
    }
} 