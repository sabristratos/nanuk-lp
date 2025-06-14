<?php

namespace Tests\Feature\Livewire\Admin\Experiments;

use App\Livewire\Admin\Experiments\Index;
use App\Models\Experiment;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create(['name' => 'Test Role', 'slug' => 'test-role']);
        $permissions = [
            Permission::create(['name' => 'view-experiments', 'slug' => 'view-experiments']),
            Permission::create(['name' => 'delete-experiments', 'slug' => 'delete-experiments']),
        ];
        $role->permissions()->attach(collect($permissions)->pluck('id'));

        $this->user = User::factory()->create();
        $this->user->roles()->attach($role);
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_render_the_component()
    {
        Livewire::test(Index::class)->assertSuccessful();
    }

    /** @test */
    public function it_lists_experiments()
    {
        Experiment::factory()->create(['name' => 'Test A']);
        Experiment::factory()->create(['name' => 'Test B']);

        Livewire::test(Index::class)
            ->assertSee('Test A')
            ->assertSee('Test B');
    }

    /** @test */
    public function it_can_search_for_experiments()
    {
        Experiment::factory()->create(['name' => 'Searchable Experiment']);
        Experiment::factory()->create(['name' => 'Another Experiment']);

        Livewire::test(Index::class)
            ->set('search', 'Searchable')
            ->assertSee('Searchable Experiment')
            ->assertDontSee('Another Experiment');
    }

    /** @test */
    public function it_can_sort_experiments()
    {
        Experiment::factory()->create(['name' => 'A Experiment']);
        Experiment::factory()->create(['name' => 'Z Experiment']);

        $component = Livewire::test(Index::class);

        // Initial sort is asc by name
        $this->assertEquals(
            ['A Experiment', 'Z Experiment'],
            $component->get('experiments')->pluck('name')->toArray()
        );

        // Sort desc by name
        $component->call('sort', 'name');
        $this->assertEquals(
            ['Z Experiment', 'A Experiment'],
            $component->get('experiments')->pluck('name')->toArray()
        );
    }

    /** @test */
    public function it_can_delete_an_experiment()
    {
        $experiment = Experiment::factory()->create();

        Livewire::test(Index::class)
            ->dispatch('confirm-delete-experiment', experiment: $experiment->id)
            ->call('delete', $experiment->id);

        $this->assertDatabaseMissing('experiments', ['id' => $experiment->id]);
    }

    /** @test */
    public function users_without_view_permission_are_forbidden()
    {
        $this->user->roles()->detach();
        $this->expectException(\Illuminate\Auth\Access\AuthorizationException::class);
        Livewire::test(Index::class);
    }
} 