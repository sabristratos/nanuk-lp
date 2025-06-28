<?php

namespace App\Listeners;

use App\Events\SubmissionCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendSubmissionToWebhook implements ShouldQueue
{
    use InteractsWithQueue;

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
    public function handle(SubmissionCreated $event): void
    {
        $submission = $event->submission;
        $webhookUrl = setting('webhook_url');

        if (!$webhookUrl) {
            return;
        }

        $experiment = $submission->experiment;
        $variation = $submission->variation;

        $payload = [
            'event_type' => 'form_submission',
            'timestamp' => $submission->created_at->toIso8601String(),
            'submission_id' => $submission->id,
            'experiment_id' => $experiment?->id,
            'experiment_name' => $experiment?->name,
            'variation_id' => $variation?->id,
            'variation_name' => $variation?->name,
            'form_data' => [
                'firstName' => $submission->first_name,
                'lastName' => $submission->last_name,
                'email' => $submission->email,
                'phone' => $submission->phone,
                'website' => $submission->website,
                'businessYears' => $submission->business_years,
                'mainObjective' => $submission->main_objective,
                'onlineAdvertisingExperience' => $submission->online_advertising_experience,
                'monthlyBudget' => $submission->monthly_budget,
                'readyToInvest' => $submission->ready_to_invest,
                'consent' => $submission->consent,
            ],
            'meta' => [
                'ip' => $submission->ip_address,
                'user_agent' => $submission->user_agent,
                'headers' => $submission->meta['headers'] ?? [],
                'submitted_at' => $submission->created_at->toIso8601String(),
            ],
        ];

        try {
            $webhookMethod = setting('webhook_method', 'POST');
            $response = Http::timeout(30)->send($webhookMethod, $webhookUrl, [
                'json' => $payload,
            ]);

            if (!$response->successful()) {
                Log::error('Webhook submission failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'webhook_url' => $webhookUrl,
                    'submission_id' => $submission->id,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Webhook submission exception', [
                'message' => $e->getMessage(),
                'webhook_url' => $webhookUrl,
                'submission_id' => $submission->id,
            ]);
        }
    }
}
