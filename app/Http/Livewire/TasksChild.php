<?php

namespace App\Http\Livewire;

use Livewire\Component;
use \Illuminate\View\View;
use App\Models\Task;
use App\Models\Project;

class TasksChild extends Component
{

    public $item;

    /**
     * @var array
     */
    protected $listeners = [
        'showDeleteForm',
        'showCreateForm',
        'showEditForm',
    ];

    /**
     * @var array
     */
    public $projects = [];

    /**
     * @var array
     */
    protected $rules = [
        'item.title' => 'required',
        'item.priority' => 'required',
        'item.complexity' => 'required',
        'item.project_id' => 'required',
    ];

    /**
     * @var array
     */
    protected $validationAttributes = [
        'item.title' => 'Task',
    ];

    /**
     * @var bool
     */
    public $confirmingItemDeletion = false;

    /**
     * @var string | int
     */
    public $primaryKey;

    /**
     * @var bool
     */
    public $confirmingItemCreation = false;

    /**
     * @var bool
     */
    public $confirmingItemEdit = false;

    public function render(): View
    {
        return view('livewire.tasks-child');
    }

    public function showDeleteForm(int $id): void
    {
        $this->confirmingItemDeletion = true;
        $this->primaryKey = $id;
    }

    public function deleteItem(): void
    {
        Task::destroy($this->primaryKey);
        $this->confirmingItemDeletion = false;
        $this->primaryKey = '';
        $this->reset(['item']);
        $this->emitTo('tasks', 'refresh');
        $this->emitTo('livewire-toast', 'show', 'Task Deleted Successfully');
    }
 
    public function showCreateForm(): void
    {
        $this->confirmingItemCreation = true;
        $this->resetErrorBag();
        $this->reset(['item']);
        $this->item['complexity'] = $this->item['priority'] = 1;

        $this->projects = auth()->user()->projects()->orderBy('is_default', 'DESC')->orderBy('name')->get();
    }

    public function createItem(): void
    {
        $this->validate();
        auth()->user()->projects()
            ->firstWhere('id', $this->item['project_id'])
            ->tasks()
            ->create([
                'title' => $this->item['title'] ?? '',
                'complexity' => $this->item['complexity'] ?? '1',
                'priority' => $this->item['priority'] ?? '1',
            ]);
        $this->confirmingItemCreation = false;
        $this->emitTo('tasks', 'refresh');
        $this->emitTo('livewire-toast', 'show', 'Task Added Successfully');
    }
 
    public function showEditForm(Task $task): void
    {
        $this->resetErrorBag();
        $this->item = $task;
        $this->confirmingItemEdit = true;

        $this->projects = auth()->user()->projects()->orderBy('is_default', 'DESC')->orderBy('name')->get();
    }

    public function editItem(): void
    {
        $this->validate();
        $this->item->save();
        $this->confirmingItemEdit = false;
        $this->primaryKey = '';
        $this->emitTo('tasks', 'refresh');
        $this->emitTo('livewire-toast', 'show', 'Task Updated Successfully');
    }

}
