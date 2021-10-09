<?php

namespace App\Http\Livewire;

use Livewire\Component;
use \Illuminate\View\View;
use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectsChild extends Component
{

    use AuthorizesRequests;

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
    protected $rules = [
        'item.name' => 'required|min:3|max:50',
        'item.notes' => '',
    ];

    /**
     * @var array
     */
    protected $validationAttributes = [
        'item.name' => 'Name',
        'item.notes' => 'Notes',
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
        return view('livewire.projects-child');
    }

    public function showDeleteForm(int $id): void
    {
        $this->confirmingItemDeletion = true;
        $this->primaryKey = $id;
    }

    public function deleteItem(): void
    {
        $project = Project::findOrFail($this->primaryKey);
        $this->authorize('delete', $project);
        Project::destroy($this->primaryKey);
        $this->confirmingItemDeletion = false;
        $this->primaryKey = '';
        $this->reset(['item']);
        $this->emitTo('projects', 'refresh');
        $this->emitTo('livewire-toast', 'show', 'Project Deleted Successfully');
    }
 
    public function showCreateForm(): void
    {
        $this->confirmingItemCreation = true;
        $this->resetErrorBag();
        $this->reset(['item']);
    }

    public function createItem(): void
    {
        $this->validate();
        $item = auth()->user()->projects()->create([
            'name' => $this->item['name'] ?? '', 
            'notes' => $this->item['notes'] ?? '', 
        ]);
        $this->confirmingItemCreation = false;
        $this->emitTo('projects', 'refresh');
        $this->emitTo('livewire-toast', 'show', 'Project Added Successfully');
    }
 
    public function showEditForm(Project $project): void
    {
        $this->authorize('update', $project);
        $this->resetErrorBag();
        $this->item = $project;
        $this->confirmingItemEdit = true;
    }

    public function editItem(): void
    {
        $this->authorize('update', $this->item);
        $this->validate();
        $this->item->save();
        $this->confirmingItemEdit = false;
        $this->primaryKey = '';
        $this->emitTo('projects', 'refresh');
        $this->emitTo('livewire-toast', 'show', 'Project Updated Successfully');
    }

}
