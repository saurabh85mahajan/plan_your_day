<div class="mt-8">
    <div class="flex justify-between">
        <div class="text-2xl">Tasks</div> 
    </div>

    <div class="mt-6 min-h-screen">
        <div class="flex justify-between">
            <div class="flex">

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
        <table class="w-full whitespace-no-wrap mt-4 shadow-2xl" wire:loading.class.delay="opacity-50">
            <thead>
                <tr class="text-left font-bold bg-blue-400">
                <td class="px-3 py-2" >
                    <div class="flex items-center">
                        <button wire:click="sortBy('title')">Title</button>
                        <x:tall-crud-generator::sort-icon sortField="title" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                    </div>
                </td>
                <td class="px-3 py-2" >Project</td>
                <td class="px-3 py-2" >
                    <div class="flex items-center">
                        <button wire:click="sortBy('complexity')">Complexity</button>
                        <x:tall-crud-generator::sort-icon sortField="complexity" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                    </div>
                </td>
                <td class="px-3 py-2" >
                    <div class="flex items-center">
                        <button wire:click="sortBy('priority')">Priority</button>
                        <x:tall-crud-generator::sort-icon sortField="priority" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                    </div>
                </td>
                <td class="px-3 py-2" >
                    <div class="flex items-center">
                        <button wire:click="sortBy('created_at')">Created At</button>
                        <x:tall-crud-generator::sort-icon sortField="created_at" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                    </div>
                </td>
                </tr>
            </thead>
            <tbody class="divide-y divide-blue-400">
            @foreach($results as $result)
                <tr class="hover:bg-blue-300 {{ ($loop->even ) ? "bg-blue-100" : ""}}">
                    <td class="px-3 py-2" >{{ $result->title}}</td>
                    <td class="px-3 py-2" >{{ $result->project?->name}}</td>
                    <td class="px-3 py-2" >{{ $result->complexity}}</td>
                    <td class="px-3 py-2" >{{ $result->priority}}</td>
                    <td class="px-3 py-2" >{{ $result->created_at}}</td>
               </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $results->links() }}
    </div>

    @livewire('livewire-toast')
</div>