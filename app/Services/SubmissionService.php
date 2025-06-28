<?php

namespace App\Services;

use App\Events\SubmissionCreated;
use App\Models\Experiment;
use App\Models\Submission;
use App\Models\Variation;
use Illuminate\Http\Request;

class SubmissionService
{
    public function __construct(protected MetricsService $metricsService)
    {
    }

    public function create(array $data, Request $request, ?Experiment $experiment = null, ?Variation $variation = null): Submission
    {
        $ip = $request->ip();
        $userAgent = $request->header('User-Agent');
        $headers = collect($request->headers->all())->map(function ($v) {
            return is_array($v) && count($v) === 1 ? $v[0] : $v;
        })->toArray();

        $submission = Submission::create(array_merge($data, [
            'experiment_id' => $experiment?->id,
            'variation_id' => $variation?->id,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'meta' => [
                'headers' => $headers,
                'submitted_at' => now()->toIso8601String(),
            ],
        ]));

        // Record conversion for experiment tracking
        if ($experiment && $variation) {
            $this->metricsService->recordConversion(
                experiment: $experiment,
                variation: $variation,
                conversionType: 'form_submission',
                payload: $submission->toArray()
            );
        }

        // Dispatch general submission event
        event(new SubmissionCreated($submission));

        return $submission;
    }
} 