<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\View\View;
use Carbon\Carbon;

use App\Models\Task;
use App\Models\Project;

class OldTasks extends Component
{
    use WithPagination;

    /**
     * @var array
     */
    protected $listeners = ['refresh' => '$refresh'];
    /**
     * @var string
     */
    public $sortBy = 'created_at';

    /**
     * @var bool
     */
    public $sortAsc = false;

    /**
     * @var int
     */
    public $per_page = 15;

    /**
     * @var array
     */
    public $filters = [];

    /**
     * @var array
     */
    public $selectedFilters = [];


    public function mount(): void
    {

        $this->filters = [
            'priority' => [
                'label' => 'Priority',
                'options' => [
                    ['key' => '', 'label' => 'Any'],
                    ['key' => '1', 'label' => '1'],
                    ['key' => '2', 'label' => '2'],
                    ['key' => '3', 'label' => '3'],
                ]
            ],
            'complexity' => [
                'label' => 'Complexity',
                'options' => [
                    ['key' => '', 'label' => 'Any'],
                    ['key' => '1', 'label' => '1'],
                    ['key' => '2', 'label' => '2'],
                    ['key' => '3', 'label' => '3'],
                ]
            ],
        ];

        $projects = Project::pluck('name', 'id')->map(function($i, $k) {
            return ['key' => $k, 'label' => $i];
        })->toArray();
        $this->filters['project_id']['label'] = 'Project';
        $this->filters['project_id']['options'] = ['0' => ['key' => '', 'label' => 'Any']] + $projects;
    }

    public function render(): View
    {
        $results = $this->query()
            ->with(['project'])
            ->orderBy($this->sortBy, $this->sortAsc ? 'ASC' : 'DESC')
            ->paginate($this->per_page);

        return view('livewire.old-tasks', [
            'results' => $results
        ]);
    }

    public function sortBy(string $field): void
    {
        if ($field == $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $field;
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
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

    public function query(): Builder
    {
        return Task::query()
            ->older()
            ->when($this->isFilterSet('priority'), function($query) {
                return $query->where('priority', $this->selectedFilters['priority']);
            })
            ->when($this->isFilterSet('complexity'), function($query) {
                return $query->where('complexity', $this->selectedFilters['complexity']);
            })
            ->when($this->isFilterSet('project_id'), function($query) {
                return $query->where('project_id', $this->selectedFilters['project_id']);
            });
    }

    public function duplicate(Task $task)
    {
        $task->duplicate();
        $this->emitTo('livewire-toast', 'show', 'New Task Added Successfully');
    }
}
