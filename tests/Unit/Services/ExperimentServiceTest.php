<?php

namespace Tests\Unit\Services;

use App\Models\Experiment;
use App\Models\Variation;
use App\Services\ExperimentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Tests\TestCase;

class ExperimentServiceTest extends TestCase
{
    use RefreshDatabase;

    private ExperimentService $experimentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->experimentService = new ExperimentService(new Request());
    }

    /** @test */
    public function it_returns_null_if_no_active_experiment_exists_for_a_view()
    {
        $experiment = $this->experimentService->getActiveExperiment('some.view.name');
        $this->assertNull($experiment);
    }

    /** @test */
    public function it_can_get_an_active_experiment_for_a_view()
    {
        $activeExperiment = Experiment::factory()->active()->create(['name' => 'Active Test']);
        $activeExperiment->variations()->create(['name' => 'Control', 'weight' => 100]);
        
        // This won't be picked as it's a draft
        Experiment::factory()->draft()->create();

        $experiment = $this->experimentService->getActiveExperiment('components.landing.hero');

        $this->assertNotNull($experiment);
        $this->assertEquals($activeExperiment->id, $experiment->id);
    }
    
    /** @test */
    public function it_assigns_a_variation_and_sets_a_cookie()
    {
        $experiment = Experiment::factory()->active()->create();
        $variationA = $experiment->variations()->create(['name' => 'A', 'weight' => 100]);

        // Mock the request to be able to check for cookies
        $this->app->instance('request', Request::create('/', 'GET'));
        
        $assignedVariation = $this->experimentService->getAssignedVariation($experiment->id);

        $this->assertEquals($variationA->id, $assignedVariation->id);
        
        $cookie = Cookie::get("experiment_{$experiment->id}");
        $this->assertNotNull($cookie);
        $this->assertEquals($variationA->id, $cookie);
    }

    /** @test */
    public function it_respects_an_existing_variation_cookie()
    {
        $experiment = Experiment::factory()->active()->create();
        $variationA = $experiment->variations()->create(['name' => 'A', 'weight' => 0]);
        $variationB = $experiment->variations()->create(['name' => 'B', 'weight' => 100]);

        // Mock the request and set the cookie
        $request = Request::create('/', 'GET', [], ["experiment_{$experiment->id}" => $variationA->id]);
        $this->app->instance('request', $request);

        $assignedVariation = $this->experimentService->getAssignedVariation($experiment->id);

        // It should return variation A, even though its weight is 0
        $this->assertEquals($variationA->id, $assignedVariation->id);
    }

    /** @test */
    public function it_handles_invalid_variation_id_in_cookie_gracefully()
    {
        $experiment = Experiment::factory()->active()->create();
        $variation = $experiment->variations()->create(['name' => 'Real', 'weight' => 100]);

        // Set a cookie with a variation ID that does not exist for this experiment
        $request = Request::create('/', 'GET', [], ["experiment_{$experiment->id}" => 999]);
        $this->app->instance('request', $request);
        
        $assignedVariation = $this->experimentService->getAssignedVariation($experiment->id);

        // It should assign a new, valid variation
        $this->assertEquals($variation->id, $assignedVariation->id);
    }
} 