<div class="mt-8">
    <div class="flex justify-between">
        <div class="flex justify-start">
            <x-filters :filters=$filters />

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