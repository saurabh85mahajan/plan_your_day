<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Task;

class ScheduleTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Schedule Pedning Tasks from Yesterday';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tasks = Task::pending()
            ->whereDate('tasks.created_at', Carbon::yesterday())
            ->get();
        foreach($tasks as $task)
        {
            $task->duplicate();
        }

        $this->info( count($tasks) . ' Pending Tasks were Scheduled for Today');
        return 0;
    }
}
