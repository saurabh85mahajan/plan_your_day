@php
$priorityClass = [
    '1' => 'from-red-400 to-green-500',
    '2' => 'from-red-500 to-green-500',
    '3' => 'from-red-800 to-green-500',
][$task->priority ?? '1'];
@endphp

<div class="lg:flex items-center justify-center mb-2 text-white bg-gray-100 rounded border px-3 py-2">
    <div class="flex lg:w-4/6 bg-gradient-to-r {{$priorityClass}} rounded-lg border px-3 py-2 ">
        <div @class(['flex-initial', 'w-2/3', 'line-through' => $task->is_completed])>{{ $task->title}}</div>
        <div class="flex flex-initial w-1/6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
            </svg>
            {{ $task->project->name}}
        </div>
        <div class="flex-initial w-1/6 mr-2 text-sm">
            <x-tag>Complexity:{{ $task->complexity}}</x-tag>
        </div>
    </div>

    <div class="flex-grow mb-6 mt-2 lg:mb-0 lg:mt-0 lg:ml-2 lg:flex lg:justify-around">
        <x:tall-crud-generator::button mode="add" wire:loading.attr="disabled" wire:click="toggleComplete({{ $task->id}})">
            Mark {{$task->isCompleted() ? 'Pending' : 'Complete'}}
        </x:tall-crud-generator::button>
        <x:tall-crud-generator::button mode="edit" wire:loading.attr="disabled" wire:click="$emitTo('tasks-child', 'showEditForm', {{ $task->id}});">Edit</x:tall-crud-generator::button>
        <x:tall-crud-generator::button mode="delete" wire:loading.attr="disabled" wire:click="$emitTo('tasks-child', 'showDeleteForm', {{ $task->id}});">Delete</x:tall-crud-generator::button>
    </div>
</div>