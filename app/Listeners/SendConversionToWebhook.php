<?php

namespace App\Listeners;

use App\Events\ConversionRecorded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SendConversionToWebhook implements ShouldQueue
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
    public function handle(ConversionRecorded $event): void
    {
        $webhookUrl = setting('webhook_url');
        $method = Str::lower(setting('webhook_method', 'POST'));
        $payloadTemplate = setting('webhook_payload');

        Log::debug('SendConversionToWebhook: Handling event.', [
            'webhook_url' => $webhookUrl,
            'method' => $method,
            'event' => $event,
        ]);

        if (empty($webhookUrl) || empty($payloadTemplate)) {
            Log::debug('SendConversionToWebhook: Webhook URL or payload template is empty. Exiting.');
            return;
        }

        $payloadString = $this->replacePlaceholders($payloadTemplate, $event);
        
        try {
            $payload = json_decode($payloadString, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            Log::error('Invalid JSON in webhook payload setting.', [
                'error' => $e->getMessage(),
                'payload_string' => $payloadString
            ]);
            return;
        }

        try {
            Log::debug('SendConversionToWebhook: Sending payload.', ['payload' => $payload]);
            Http::$method($webhookUrl, $payload);
            Log::debug('SendConversionToWebhook: Payload sent successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to send conversion to webhook.', [
                'webhook_url' => $webhookUrl,
                'method' => $method,
                'error' => $e->getMessage(),
            ]);
        }
    }
    
    private function replacePlaceholders(string $template, ConversionRecorded $event): string
    {
        // First, handle the payload replacement separately to ensure it's not treated as a simple string.
        $payloadJson = json_encode($event->payload);
        // We look for "{payload}" (with quotes) to ensure we're replacing a string value in the template.
        $template = str_replace('"{payload}"', $payloadJson, $template);
        
        $replacements = [
            '{visitorId}' => $event->visitorId,
            '{experimentId}' => $event->experiment->id,
            '{experimentName}' => $event->experiment->name,
            '{variationId}' => $event->variation->id,
            '{variationName}' => $event->variation->name,
            '{conversionType}' => $event->conversionType,
            '{timestamp}' => now()->toIso8601String(),
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }
}
