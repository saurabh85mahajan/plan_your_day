<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;
use Carbon\Carbon;

class ScheduleTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_works()
    {
        Task::factory()->count(3)->create(['created_at' => Carbon::yesterday()]);

        $this->artisan('schedule:tasks')
            ->assertExitCode(0);
    }
}
