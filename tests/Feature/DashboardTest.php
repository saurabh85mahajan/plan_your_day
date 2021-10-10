<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Livewire\Livewire;
use App\Http\Livewire\Tasks;
use App\Http\Livewire\TasksChild;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_task_is_visible()
    {
        $project = Project::factory()->for($this->user)->create();
        $task = Task::factory()
            ->for($project)
            ->highestComplexity()
            ->create();
        Livewire::test(Tasks::class)
            ->assertSeeInOrder([$task->name, $task->project->name, 'Complexity:3'])
            ->assertMethodWired('toggleComplete')
            ->assertSeeInOrder(['Mark Complete', 'Edit', 'Delete']);
    }

    public function test_other_user_tasks_are_not_visible()
    {
        $task = Task::factory()
            ->create();

        Livewire::test(Tasks::class)
            ->assertDontSee($task->nme);
    }

    public function test_tasks_are_grouped_by_project()
    {
        $projectA = Project::factory()->for($this->user)->create(['name' => 'Banana']);
        $projectB = Project::factory()->for($this->user)->create(['name' => 'Apple']);

        $tasksA = Task::factory()
            ->for($projectA)
            ->create();

        $tasksB = Task::factory()
            ->for($projectB)
            ->create();

        Livewire::test(Tasks::class)
            ->assertPropertyWired('sortBy')
            ->set('sortBy', 'project.name')
            ->assertSeeInOrder([$tasksA->name, $tasksB->name]);
    }

    public function test_tasks_are_grouped_by_complexity()
    {
        $projectA = Project::factory()->for($this->user)->create();

        $tasksA = Task::factory()
            ->for($projectA)
            ->highestComplexity()
            ->create();

        $tasksB = Task::factory()
            ->for($projectA)
            ->lowestComplexity()
            ->create();

        Livewire::test(Tasks::class)
            ->assertPropertyWired('sortBy')
            ->set('sortBy', 'complexity')
            ->assertSeeInOrder([$tasksA->name, $tasksB->name]);
    }

    public function test_tasks_are_grouped_by_priority()
    {
        $projectA = Project::factory()->for($this->user)->create();

        $tasksA = Task::factory()
            ->for($projectA)
            ->lowestPriority()
            ->create();

        $tasksB = Task::factory()
            ->for($projectA)
            ->highestPriority()
            ->create();

        $tasksC = Task::factory()
            ->for($projectA)
            ->lowestPriority()
            ->create();

        Livewire::test(Tasks::class)
            ->assertPropertyWired('sortBy')
            ->set('sortBy', 'complexity')
            ->assertSeeInOrder([$tasksB->name, $tasksA->name])
            ->assertSeeInOrder([$tasksB->name, $tasksC->name]);
    }

    public function test_only_pending_tasks_are_shown()
    {
        $projectA = Project::factory()->for($this->user)->create();

        $tasksA = Task::factory()
            ->for($projectA)
            ->create();
        $tasksB = Task::factory()
            ->for($projectA)
            ->create(['is_completed' => date('Y-m-d H:i:s')]);

        Livewire::test(Tasks::class)
            ->assertSee($tasksA->name)
            ->assertSee($tasksB->name)
            ->assertPropertyWired('pendingTasksOnly')
            ->set('pendingTasksOnly', 1)
            ->assertSee($tasksA->name)
            ->assertDontSee($tasksB->name);
    }

    public function test_task_can_be_completed()
    {
        $projectA = Project::factory()->for($this->user)->create();

        $tasksA = Task::factory()
            ->for($projectA)
            ->create();

        Livewire::test(Tasks::class)
            ->assertSee('Mark Complete')
            ->call('toggleComplete', $tasksA->id)
            ->assertSee('Mark Pending');

        $tasksA = $tasksA->refresh();
        $this->assertTrue($tasksA->isCompleted());
    }

    public function tes_task_can_be_marked_pending()
    {
        $projectA = Project::factory()->for($this->user)->create();

        $tasksA = Task::factory()
            ->for($projectA)
            ->create(['is_completed' => date('Y-m-d H:i:s')]);

        Livewire::test(Tasks::class)
            ->assertSee('Mark Pending')
            ->call('toggleComplete', $tasksA->id)
            ->assertSee('Mark Complete');

        $tasksA = $tasksA->refresh();
        $this->assertFalse($tasksA->isCompleted());
    }

    public function test_delete_is_working()
    {
        $projectA = Project::factory()->for($this->user)->create();

        $tasksA = Task::factory()
            ->for($projectA)
            ->create();

        Livewire::test(Tasks::class)
            ->assertContainsLivewireComponent(TasksChild::class);

        Livewire::test(TasksChild::class)
            ->call('showDeleteForm', $tasksA->id)
            ->assertSet('confirmingItemDeletion', true)
            ->assertSet('primaryKey', $tasksA->id)
            ->call('deleteItem')
            ->assertEmitted('refresh');

        $tasks = $projectA->tasks()->get();
        $this->assertCount(0, $tasks);

        $this->assertSoftDeleted($tasksA);
    }

    public function test_validations_are_working()
    {
        $projectA = Project::factory()->for($this->user)->create();

        Livewire::test(TasksChild::class)
            ->call('showCreateForm')
            ->set('item.title', '')
            ->set('item.project_id', '')
            ->call('createItem')
            ->assertHasErrors('item.title')
            ->assertHasErrors('item.project_id')
            ->assertSee('The Task field is required.')
            ->assertSee('The Project field is required.');
    }

    public function test_task_can_be_added()
    {
        $projectA = Project::factory()->for($this->user)->create();

        Livewire::test(TasksChild::class)
            ->call('showCreateForm')
            ->assertPropertyWired('item.title')
            ->assertPropertyWired('item.project_id')
            ->assertPropertyWired('item.complexity')
            ->assertPropertyWired('item.priority')
            ->set('item.title', 'Complete Testing')
            ->set('item.project_id', $projectA->id)
            ->set('item.complexity', 2)
            ->set('item.priority', 2)
            ->call('createItem')
            ->assertEmitted('refresh');

        $tasks = $projectA->tasks()->get();
        $this->assertCount(1, $tasks);

        $task = $tasks->first();
        $this->assertEquals('Complete Testing', $task->title);
        $this->assertEquals($projectA->id, $task->project_id);
        $this->assertEquals(2, $task->complexity);
        $this->assertEquals(2, $task->priority);
        $this->assertEquals(null, $task->is_completed);
    }

    public function test_task_can_be_updated()
    {
        $projectA = Project::factory()->for($this->user)->create();

        $tasksA = Task::factory()
            ->for($projectA)
            ->create();
        Livewire::test(TasksChild::class)
            ->call('showEditForm', $tasksA->id)
            ->assertPropertyWired('item.title')
            ->assertPropertyWired('item.project_id')
            ->assertPropertyWired('item.complexity')
            ->assertPropertyWired('item.priority')
            ->set('item.title', 'Updated Task')
            ->set('item.complexity', 3)
            ->set('item.priority', 2)
            ->call('editItem')
            ->assertEmitted('refresh');

        $task = $projectA->tasks()->get()->first();

        $this->assertEquals('Updated Task', $task->title);
        $this->assertEquals('2', $task->priority);
        $this->assertEquals('3', $task->complexity);
        $this->assertEquals($projectA->id, $task->project_id);
    }
}
