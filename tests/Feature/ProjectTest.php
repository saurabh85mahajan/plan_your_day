<?php

namespace Tests\Feature;

use App\Http\Livewire\Projects;
use App\Http\Livewire\ProjectsChild;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Livewire\Livewire;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_is_added_on_registration()
    {
        $user = User::factory()->create();
        $projects = $user->projects()->get();

        $this->assertCount(1, $projects);

        $project = $projects->first();
        $this->assertEquals('No Project', $project->name);
        $this->assertEquals(1, $project->is_default);
    }

    public function test_default_project_is_not_visible()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(Projects::class)
            ->assertDontSee('No Project');
    }

    public function test_user_projects_are_visible()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $projectA = Project::factory()->for($user)->create();
        $projectB = Project::factory()->for($user)->create();

        Livewire::test(Projects::class)
            ->assertSeeInOrder([$projectA->name, $projectB->name]);
    }

    public function test_other_projects_are_not_visible()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::factory()->create();

        Livewire::test(Projects::class)
            ->assertDontSee($project->name);
    }

    public function test_sort_by_id_is_working()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $projectA = Project::factory()->for($user)->create();
        $projectB = Project::factory()->for($user)->create();

        Livewire::test(Projects::class)
            ->assertMethodWired('sortBy')
            ->call('sortBy', 'id')
            ->assertSeeInOrder([$projectB->name, $projectA->name])
            ->call('sortBy', 'id')
            ->assertSeeInOrder([$projectA->name, $projectB->name]);
    }

    public function test_sort_by_name_is_working()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $projectA = Project::factory()->for($user)->create(['name' => 'Apple']);
        $projectB = Project::factory()->for($user)->create(['name' => 'Banana']);

        Livewire::test(Projects::class)
            ->call('sortBy', 'name')
            ->assertSeeInOrder([$projectA->name, $projectB->name])
            ->call('sortBy', 'name')
            ->assertSeeInOrder([$projectB->name, $projectA->name]);
    }

    public function test_search_is_working()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $projectA = Project::factory()->for($user)->create(['name' => 'Apple']);
        Project::factory()->for($user)->create(['name' => 'Banana']);

        Livewire::test(Projects::class)
            ->assertPropertyWired('q')
            ->set('q', 'App')
            ->assertSeeInOrder([$projectA->id, $projectA->name])
            ->assertDontSee('Banana');
    }

    public function test_delete_is_working()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $projectA = Project::factory()->for($user)->create(['name' => 'Apple']);

        Livewire::test(Projects::class)
            ->assertContainsLivewireComponent(ProjectsChild::class);

        Livewire::test(ProjectsChild::class)
            ->call('showDeleteForm', $projectA->id)
            ->assertSet('confirmingItemDeletion', true)
            ->assertSet('primaryKey', $projectA->id)
            ->call('deleteItem')
            ->assertEmitted('refresh');

        $projects = $user->projects()->nonDefault()->get();
        $this->assertCount(0, $projects);
    }

    public function test_project_name_is_required()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(ProjectsChild::class)
            ->call('showCreateForm')
            ->set('item.name', '')
            ->set('item.notes', 'Dummy Note')
            ->call('createItem')
            ->assertHasErrors('item.name')
            ->assertSee('Name field is required.');
    }

    public function test_project_can_be_added()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(ProjectsChild::class)
            ->call('showCreateForm')
            ->assertPropertyWired('item.name')
            ->assertPropertyWired('item.notes')
            ->set('item.name', 'Latest Project')
            ->set('item.notes', 'Dummy Note')
            ->call('createItem')
            ->assertEmitted('refresh');

        $projects = $user->projects()->nonDefault()->get();
        $this->assertCount(1, $projects);

        $project = $projects->first();
        $this->assertEquals('Latest Project', $project->name);
        $this->assertEquals(null, $project->is_default);
    }

    public function test_project_can_be_updated()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $projectA = Project::factory()->for($user)->create(['name' => 'Apple']);

        Livewire::test(ProjectsChild::class)
            ->call('showEditForm', $projectA->id)
            ->assertPropertyWired('item.name')
            ->assertPropertyWired('item.notes')
            ->set('item.name', 'Banana')
            ->call('editItem')
            ->assertEmitted('refresh');

        $projects = $user->projects()->nonDefault()->get();
        $this->assertCount(1, $projects);

        $project = $projects->first();
        $this->assertEquals('Banana', $project->name);
        $this->assertEquals(null, $project->is_default);
    }
}
