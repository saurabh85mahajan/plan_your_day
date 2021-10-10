<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Livewire\Livewire;
use App\Http\Livewire\OldTasks;

class OldTaskTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_listing()
    {
        $project = Project::factory()->for($this->user)->create();
        $taskA = Task::factory()->for($project)->create();
        $taskB = Task::factory()->for($project)->create(['created_at' => Carbon::yesterday()]);

        Livewire::test(OldTasks::class)
            ->assertSee($taskB->name)
            ->assertDontSee($taskA->name);
    }

    public function test_completed()
    {
        $project = Project::factory()->for($this->user)->create();
        $taskA = Task::factory()->for($project)->create(['created_at' => Carbon::yesterday()]);

        Livewire::test(OldTasks::class)
            ->assertSee($taskA->name)
            ->assertMethodWired('duplicate')
            ->call('duplicate', $taskA->id)
            ->assertEmitted('show');
        
        $tasks = $project->tasks()->dueToday()->get();
        $this->assertCount(1, $tasks);
        $task = $tasks->first();
        $this->assertEquals($taskA->title, $task->title);
    }
}
