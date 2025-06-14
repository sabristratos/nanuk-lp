<?php

namespace Tests\Feature\Services;

use App\Enums\ExperimentStatus;
use App\Models\Experiment;
use App\Services\ExperimentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

/**
 * @group ab-testing
 */
class ExperimentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ExperimentService $experimentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->experimentService = $this->app->make(ExperimentService::class);
        $this->seed(\Database\Seeders\ExperimentSeeder::class);
    }

    public function test_can_get_active_experiment_for_view(): void
    {
        $experiment = $this->experimentService->getActiveExperiment('components.landing.hero');

        $this->assertNotNull($experiment);
        $this->assertEquals('Hero Section Test', $experiment->name);
        $this->assertEquals(ExperimentStatus::Active, $experiment->status);
    }

    public function test_returns_null_if_no_active_experiment_for_view(): void
    {
        $experiment = $this->experimentService->getActiveExperiment('non.existent.view');
        $this->assertNull($experiment);
    }

    public function test_assigns_variation_and_sets_cookie(): void
    {
        $experiment = Experiment::where('name', 'Hero Section Test')->first();
        
        $variation = $this->experimentService->getAssignedVariation($experiment->id);

        $this->assertNotNull($variation);
        $this->assertDatabaseHas('variations', ['id' => $variation->id, 'experiment_id' => $experiment->id]);
        
        $cookie = collect(Cookie::getQueuedCookies())->first(function ($c) use ($experiment) {
            return $c->getName() === "experiment_{$experiment->id}_variation";
        });

        $this->assertNotNull($cookie);
        $this->assertEquals($variation->id, $cookie->getValue());
    }

    public function test_respects_existing_variation_cookie(): void
    {
        $experiment = Experiment::where('name', 'Hero Section Test')->first();
        $controlVariation = $experiment->variations()->where('is_control', true)->first();
        
        $cookieName = "experiment_{$experiment->id}_variation";
        Cookie::queue($cookieName, $controlVariation->id, 60);
        $this->withCookie($cookieName, $controlVariation->id);

        $assignedVariation = $this->experimentService->getAssignedVariation($experiment->id);
        
        $this->assertEquals($controlVariation->id, $assignedVariation->id);
    }
    
    public function test_it_throws_validation_exception_for_invalid_data(): void
    {
        $this->expectException(ValidationException::class);

        $invalidData = [
            'name' => '', // Invalid: name is required
            'status' => 'invalid-status',
            'variations' => [
                [
                    'name' => 'Test',
                    'weight' => 150 // Invalid: weight > 100
                ]
            ]
        ];

        $this->experimentService->createExperiment($invalidData);
    }
} 