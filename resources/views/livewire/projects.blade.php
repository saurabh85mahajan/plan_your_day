<div class="mt-8">
    <div class="flex justify-between">
        <div class="text-2xl">Projects</div>
        <button type="submit" wire:click="$emitTo('projects-child', 'showCreateForm');" class="text-blue-500">
            <x:tall-crud-generator::icon-add />
        </button> 
    </div>

    <div class="mt-6">
        <div class="flex justify-between">
            <div class="flex">
                <input wire:model.debounce.500ms="q" type="search" placeholder="Search" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                <span class="ml-3 mt-2" wire:loading.delay wire:target="q">
                    <x:tall-crud-generator::loading-indicator />
                </span>
            </div>
            <div class="flex">
                <x:tall-crud-generator::select class="block w-1/10" wire:model="per_page">
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </x:tall-crud-generator::select>
            </div>
        </div>
        <x-table>
            <x-slot name="header">
                <x-td>
                    <x-sortable-header label="Id" column="id">
                        <x:tall-crud-generator::sort-icon sortField="id" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                    </x-sortable-header>
                </x-td>
                <x-td>
                    <x-sortable-header label="Name" column="name">
                        <x:tall-crud-generator::sort-icon sortField="name" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                    </x-sortable-header>
                </x-td>
                <x-td>Actions</x-td>
            </x-slot>
            @foreach($results as $result)
                <tr class="hover:bg-blue-300 {{ ($loop->even ) ? "bg-blue-100" : ""}}">
                    <x-td>{{ $result->id}}</x-td>
                    <x-td>{{ $result->name}}</x-td>
                    <x-td>
                        <button type="submit" wire:click="$emitTo('projects-child', 'showEditForm', {{ $result->id}});" class="text-green-500">
                            <x:tall-crud-generator::icon-edit />
                        </button>
                        <button type="submit" wire:click="$emitTo('projects-child', 'showDeleteForm', {{ $result->id}});" class="text-red-500">
                            <x:tall-crud-generator::icon-delete />
                        </button>
                    </x-td>
                </tr>
            @endforeach
        </x-table>
    </div>

    <div class="mt-4">
        {{ $results->links() }}
    </div>
    @livewire('projects-child')
    @livewire('livewire-toast')
</div>