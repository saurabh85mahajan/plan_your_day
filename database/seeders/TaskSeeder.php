<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $projects = Project::where('is_default', null)->get();
        foreach ($projects as $project) {
            Task::factory()
                ->count(4)
                ->for($project)
                ->create();

            $startDate = Carbon::now();
            for($i = 1; $i <= 6; $i++) {
                $date = $startDate->subDay()->format('Y-m-d');
                Task::factory()
                    ->count(2)
                    ->for($project)
                    ->create(['created_at' => $date]);
            }
        }
    }
}
