<?php

namespace Tests\Feature\Services;

use App\Models\Experiment;
use App\Services\MetricsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ab-testing
 */
class MetricsServiceTest extends TestCase
{
    use RefreshDatabase;

    protected MetricsService $metricsService;
    protected Experiment $experiment;

    protected function setUp(): void
    {
        parent::setUp();
        // Mock the request to control the IP and fingerprint
        $this->mock(Request::class, function ($mock) {
            $mock->shouldReceive('ip')->andReturn('127.0.0.1');
            $mock->shouldReceive('fingerprint')->andReturn('test-fingerprint');
        });

        $this->metricsService = $this->app->make(MetricsService::class);
        
        $this->seed(\Database\Seeders\ExperimentSeeder::class);
        $this->experiment = Experiment::where('name', 'Hero Section Test')->first();
    }

    public function test_can_record_a_conversion_event(): void
    {
        $variation = $this->experiment->variations->first();
        
        $result = $this->metricsService->recordConversion($this->experiment, $variation, 'cta_click');

        $this->assertDatabaseHas('experiment_results', [
            'experiment_id' => $this->experiment->id,
            'variation_id' => $variation->id,
            'conversion_type' => 'cta_click',
            'visitor_id' => 'test-fingerprint',
        ]);
    }

    public function test_can_record_a_metric(): void
    {
        $variation = $this->experiment->variations->first();

        $metric = $this->metricsService->recordMetric($this->experiment, $variation, 'page_load_time', 1.23);

        $this->assertDatabaseHas('experiment_metrics', [
            'experiment_id' => $this->experiment->id,
            'variation_id' => $variation->id,
            'metric_name' => 'page_load_time',
            'metric_value' => 1.23,
        ]);
    }

    public function test_it_uses_ip_address_as_fallback_for_visitor_id(): void
    {
        // Create a mock that only returns an IP
        $this->mock(Request::class, function ($mock) {
            $mock->shouldReceive('ip')->andReturn('192.168.1.1');
            $mock->shouldReceive('fingerprint')->andReturn(null);
        });
        
        $metricsService = $this->app->make(MetricsService::class);
        $variation = $this->experiment->variations->first();

        $metricsService->recordConversion($this->experiment, $variation, 'cta_click');

        $this->assertDatabaseHas('experiment_results', [
            'visitor_id' => '192.168.1.1',
        ]);
    }
} 