<?php

namespace Tests\Feature\Livewire\Admin\Experiments;

use App\Livewire\Admin\Experiments\ManageExperiment;
use App\Models\User;
use App\Models\Experiment;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ManageExperimentTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Role $role;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->role = Role::create(['name' => 'Test Role', 'slug' => 'test-role']);
        $permissions = [
            Permission::create(['name' => 'create-experiments', 'slug' => 'create-experiments']),
            Permission::create(['name' => 'edit-experiments', 'slug' => 'edit-experiments']),
        ];
        $this->role->permissions()->attach(collect($permissions)->pluck('id'));

        $this->user = User::factory()->create();
        $this->user->roles()->attach($this->role);
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_render_the_create_experiment_page()
    {
        Livewire::test(ManageExperiment::class)
            ->assertSuccessful()
            ->assertSee(__('Create Experiment'));
    }

    /** @test */
    public function it_can_render_the_edit_experiment_page()
    {
        $experiment = Experiment::factory()->create();

        Livewire::test(ManageExperiment::class, ['experiment' => $experiment])
            ->assertSuccessful()
            ->assertSee(__('Edit Experiment'));
    }

    /** @test */
    public function it_can_create_a_new_experiment()
    {
        $response = Livewire::test(ManageExperiment::class)
            ->set('name', 'New Landing Page Test')
            ->set('description', 'A test for the new landing page.')
            ->set('status', 'draft')
            ->set('variations', [
                ['id' => null, 'name' => 'Control', 'description' => '', 'weight' => 50, 'content_variations' => []],
                ['id' => null, 'name' => 'Variation A', 'description' => '', 'weight' => 50, 'content_variations' => []],
            ])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('experiments', [
            'name' => 'New Landing Page Test',
        ]);

        $experiment = Experiment::where('name', 'New Landing Page Test')->first();
        $response->assertRedirect(route('admin.experiments.edit', $experiment));
    }

    /** @test */
    public function it_validates_that_variation_weights_must_sum_to_100()
    {
        Livewire::test(ManageExperiment::class)
            ->set('name', 'Weight Test')
            ->set('variations', [
                ['id' => null, 'name' => 'Control', 'weight' => 50, 'description' => '', 'content_variations' => []],
                ['id' => null, 'name' => 'Variation A', 'weight' => 40, 'description' => '', 'content_variations' => []],
            ])
            ->call('save')
            ->assertHasErrors(['variations' => 'The sum of variation weights must be 100.']);
    }

    /** @test */
    public function it_can_update_an_existing_experiment()
    {
        $experiment = Experiment::factory()->hasVariations(1)->create();
        $variation = $experiment->variations->first();

        Livewire::test(ManageExperiment::class, ['experiment' => $experiment])
            ->set('name', 'Updated Experiment Name')
            ->set('variations.0.name', 'Updated Variation Name')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.experiments.index'));

        $this->assertDatabaseHas('experiments', [
            'id' => $experiment->id,
            'name' => 'Updated Experiment Name',
        ]);
        $this->assertDatabaseHas('variations', [
            'id' => $variation->id,
            'name' => 'Updated Variation Name',
        ]);
    }

    /** @test */
    public function it_can_add_and_remove_variations()
    {
        Livewire::test(ManageExperiment::class)
            ->call('addVariation')
            ->assertCount('variations', 2)
            ->call('removeVariation', 0)
            ->assertCount('variations', 1);
    }

    /** @test */
    public function users_without_permission_cannot_create_or_edit_experiments()
    {
        $this->user->roles()->detach();

        $this->expectException(\Illuminate\Auth\Access\AuthorizationException::class);
        Livewire::test(ManageExperiment::class)->call('save');

        $experiment = Experiment::factory()->create();
        $this->expectException(\Illuminate\Auth\Access\AuthorizationException::class);
        Livewire::test(ManageExperiment::class, ['experiment' => $experiment])->call('save');
    }
} 