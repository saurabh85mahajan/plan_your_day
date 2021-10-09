<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\View\View;

use App\Models\Task;
use App\Models\Project;

class Tasks extends Component
{
    use WithPagination;

    /**
     * @var array
     */
    protected $listeners = ['refresh' => '$refresh'];

    /**
     * @var array
     */
    public $filters = [];

    /**
     * @var string
     */
    public $sortBy = 'project.name';

    /**
     * @var array
     */
    public $selectedFilters = [];

    /**
     * @var string | int
     */
    public $pendingTasksOnly = 0;


    public function mount(): void
    {

        $this->filters = [
            'complexity' => [
                'label' => 'Complexity',
                'options' => [
                    ['key' => '', 'label' => 'Any'],
                    ['key' => '1', 'label' => '1'],
                    ['key' => '2', 'label' => '2'],
                    ['key' => '3', 'label' => '3'],
                ]
            ],
            'priority' => [
                'label' => 'Priority',
                'options' => [
                    ['key' => '', 'label' => 'Any'],
                    ['key' => '1', 'label' => '1'],
                    ['key' => '2', 'label' => '2'],
                    ['key' => '3', 'label' => '3'],
                ]
            ],
        ];

        $projects = auth()->user()->projects()->pluck('name', 'id')->map(function($i, $k) {
            return ['key' => $k, 'label' => $i];
        })->toArray();
        $this->filters['project_id']['label'] = 'Project';
        $this->filters['project_id']['options'] = ['0' => ['key' => '', 'label' => 'Any']] + $projects;
    }

    public function render(): View
    {
        $results = $this->query()
            ->with(['project'])
            ->get();

        if($this->sortBy == 'project.name') {
            $results = $results->sortBy($this->sortBy);
        } else {
            $results = $results->sortByDesc($this->sortBy);
        }

        return view('livewire.tasks', [
            'results' => $results
        ]);
    }

    public function updatingSelectedFilters(): void
    {
        $this->resetPage();
    }

    public function isFilterSet(string $column): bool
    {
        if( isset($this->selectedFilters[$column]) && $this->selectedFilters[$column] != '') {
            return true;
        }
        return false;
    }

    public function resetFilters(): void
    {
        $this->reset('selectedFilters');
    }

    public function query()
    {
        return auth()->user()->tasks()
            ->dueToday()
            ->when($this->pendingTasksOnly, function($query){
                return $query->pending();
            })
            ->when($this->isFilterSet('complexity'), function($query) {
                return $query->where('complexity', $this->selectedFilters['complexity']);
            })
            ->when($this->isFilterSet('priority'), function($query) {
                return $query->where('priority', $this->selectedFilters['priority']);
            })
            ->when($this->isFilterSet('project_id'), function($query) {
                return $query->where('project_id', $this->selectedFilters['project_id']);
            });
    }

    public function toggleComplete( Task $task)
    {
        if ($task->isCompleted()) {
            $task->markPending();
        } else {
            $task->markComplete();
        }
    }
}
