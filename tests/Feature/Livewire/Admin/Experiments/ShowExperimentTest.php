<?php

namespace Tests\Feature\Livewire\Admin\Experiments;

use App\Livewire\Admin\Experiments\ShowExperiment;
use App\Models\Experiment;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ShowExperimentTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Experiment $experiment;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create(['name' => 'Test Role', 'slug' => 'test-role']);
        $permission = Permission::create(['name' => 'view-experiments', 'slug' => 'view-experiments']);
        $role->permissions()->attach($permission);

        $this->user = User::factory()->create();
        $this->user->roles()->attach($role);
        $this->actingAs($this->user);

        $this->experiment = Experiment::factory()->create();
    }

    /** @test */
    public function it_can_render_the_component()
    {
        Livewire::test(ShowExperiment::class, ['experiment' => $this->experiment])
            ->assertSuccessful()
            ->assertSee($this->experiment->name);
    }

    /** @test */
    public function it_prepares_chart_data_correctly()
    {
        $variationA = $this->experiment->variations()->create(['name' => 'A', 'weight' => 50]);
        $variationB = $this->experiment->variations()->create(['name' => 'B', 'weight' => 50]);
        
        // Add some results
        $variationA->results()->create(['experiment_id' => $this->experiment->id, 'visitor_id' => 'visitor1', 'created_at' => now()->subDay()]);
        $variationB->results()->create(['experiment_id' => $this->experiment->id, 'visitor_id' => 'visitor2', 'created_at' => now()]);
        
        Livewire::test(ShowExperiment::class, ['experiment' => $this->experiment])
            ->assertSet('chartData', function ($chartData) {
                $this->assertCount(2, $chartData); // Two days with data
                $this->assertEquals(1, $chartData[0]['variation_0_conversions']);
                $this->assertEquals(1, $chartData[1]['variation_1_conversions']);
                return true;
            });
    }

    /** @test */
    public function users_without_permission_cannot_view_results()
    {
        $this->user->roles()->detach();

        $this->expectException(\Illuminate\Auth\Access\AuthorizationException::class);
        Livewire::test(ShowExperiment::class, ['experiment' => $this->experiment]);
    }
} 