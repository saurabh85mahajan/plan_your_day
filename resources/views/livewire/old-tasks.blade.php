<div class="mt-8">
    <div class="mt-6 min-h-screen">
        <div class="flex justify-between">
            <div class="flex">
                <x-filters :filters=$filters />
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
                    <x-sortable-header label="Title" column="title">
                        <x:tall-crud-generator::sort-icon sortField="title" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                    </x-sortable-header>
                </x-td>
                <x-td>Project</x-td>
                <x-td>
                    <x-sortable-header label="Complexity" column="complexity">
                        <x:tall-crud-generator::sort-icon sortField="complexity" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                    </x-sortable-header>
                </x-td>
                <x-td>
                    <x-sortable-header label="Priority" column="priority">
                        <x:tall-crud-generator::sort-icon sortField="priority" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                    </x-sortable-header>
                </x-td>
                <x-td>
                    <x-sortable-header label="Created At" column="created_at">
                        <x:tall-crud-generator::sort-icon sortField="created_at" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                    </x-sortable-header>
                </x-td>
                <x-td>Actions</x-td>
            </x-slot>
            @foreach($results as $result)
                <tr class="hover:bg-blue-300 {{ ($loop->even ) ? "bg-blue-100" : ""}}">
                    <x-td>{{ $result->title}}</x-td>
                    <x-td>{{ $result->project?->name}}</x-td>
                    <x-td>{{ $result->complexity}}</x-td>
                    <x-td>{{ $result->priority}}</x-td>
                    <x-td>{{ date('F j, Y', strtotime($result->created_at))}}</x-td>
                    <x-td>
                        <x:tall-crud-generator::button mode="add" wire:loading.attr="disabled" wire:click="duplicate({{ $result->id}})">
                            Duplicate
                        </x:tall-crud-generator::button>
                    </x-td>
               </tr>
            @endforeach
        </x-table>
    </div>

    <div class="mt-4">
        {{ $results->links() }}
    </div>

    @livewire('livewire-toast')
</div>