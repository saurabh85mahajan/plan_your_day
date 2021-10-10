<div class="mt-8">
    <div class="flex justify-between">
        <div class="flex justify-start">
            <x:tall-crud-generator::dropdown class="flex justify-items items-center border border-rounded ml-4 px-4 cursor-pointer" width="w-72">
                <x-slot name="trigger">
                    <span class="flex">
                    Filters <x:tall-crud-generator::icon-filter />
                    </span>
                </x-slot>
            
                <x-slot name="content">
                    @foreach($filters as $f => $filter)
                    <div class="mt-4">
                        <x:tall-crud-generator::label class="font-sm font-bold">
                            {{ $filter['label'] }}
                        </x:tall-crud-generator::label>
                        <x:tall-crud-generator::select class="w-3/4" wire:model="selectedFilters.{{$f}}">
                            @foreach($filter['options'] as $o)
                            <option value="{{$o['key']}}">{{$o['label']}}</option>
                            @endforeach
                        </x:tall-crud-generator::select>
                    </div>
                    @endforeach
                    <div class="my-4">
                        <x:tall-crud-generator::button wire:click="resetFilters()">Reset</x:tall-crud-generator::button>
                    </div>
                </x-slot>
            </x:tall-crud-generator::dropdown>

            <div class="ml-2 flex justify-items items-center ">
                <x-label>Group By</x-label>
                <x:tall-crud-generator::select wire:model="sortBy" class="ml-2">
                    <option value="project.name">Project</option>
                    <option value="complexity">Complexity</option>
                    <option value="priority">Priority</option>
                </x:tall-crud-generator::select>
            </div>

            <div class="ml-2 flex justify-items items-center ">
                <x:tall-crud-generator::checkbox-wrapper>
                    <x-label>Pending Tasks Only:</x-label>
                    <x:tall-crud-generator::checkbox class="ml-2" wire:model="pendingTasksOnly" />
                </x:tall-crud-generator::checkbox-wrapper>
            </div>
        </div>

        <button type="submit" wire:click="$emitTo('tasks-child', 'showCreateForm');" class="text-blue-500">
            <x:tall-crud-generator::icon-add />
        </button> 
    </div>

    <div class="mt-6 min-h-screen" wire:loading.class="opacity-50">
        @foreach($results as $task)
            <x-task-card :task=$task />
        @endforeach
    </div>
    @livewire('tasks-child')
    @livewire('livewire-toast')
</div>