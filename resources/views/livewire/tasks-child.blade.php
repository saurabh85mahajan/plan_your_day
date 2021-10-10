<div>

    <x:tall-crud-generator::confirmation-dialog wire:model="confirmingItemDeletion">
        <x-slot name="title">
            Delete Task
        </x-slot>

        <x-slot name="content">
            Are you sure you want to Delete Task?
        </x-slot>

        <x-slot name="footer">
            <x:tall-crud-generator::button wire:click="$set('confirmingItemDeletion', false)">Cancel</x:tall-crud-generator::button>
            <x:tall-crud-generator::button mode="delete" wire:loading.attr="disabled" wire:click="deleteItem()">Delete</x:tall-crud-generator::button>
        </x-slot>
    </x:tall-crud-generator::confirmation-dialog>

    <x:tall-crud-generator::dialog-modal wire:model="confirmingItemCreation">
        <x-slot name="title">
            Add Task
        </x-slot>

        <x-slot name="content">
            <div class="mt-4">
                <x:tall-crud-generator::label>Task</x:tall-crud-generator::label>
                <x:tall-crud-generator::input class="block mt-1 w-1/2" type="text" wire:model.defer="item.title" />
                @error('item.title') <x:tall-crud-generator::error-message>{{$message}}</x:tall-crud-generator::error-message> @enderror
            </div>

            <div class="grid grid-cols-3">
                <div class="mt-4">
                    <x:tall-crud-generator::label>Project</x:tall-crud-generator::label>
                    <x:tall-crud-generator::select class="block mt-1 w-full" wire:model.defer="item.project_id">
                        @foreach($projects as $c)
                        <option value="{{$c->id}}">{{$c->name}}</option>
                        @endforeach
                    </x:tall-crud-generator::select>
                    @error('item.project_id') <x:tall-crud-generator::error-message>{{$message}}</x:tall-crud-generator::error-message> @enderror
                </div>
            </div>
            <div class="mt-4">
                <x:tall-crud-generator::label>Complexity</x:tall-crud-generator::label>
                <x:tall-crud-generator::select class="block mt-1 w-1/4" wire:model.defer="item.complexity">
                    @for($i = 3; $i >= 1; $i--)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </x:tall-crud-generator::select>
                @error('item.complexity') <x:tall-crud-generator::error-message>{{$message}}</x:tall-crud-generator::error-message> @enderror
            </div>
            <div class="mt-4">
                <x:tall-crud-generator::label>Priority</x:tall-crud-generator::label>
                <x:tall-crud-generator::select class="block mt-1 w-1/4" wire:model.defer="item.priority">
                    @for($i = 3; $i >= 1; $i--)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </x:tall-crud-generator::select>
                @error('item.priority') <x:tall-crud-generator::error-message>{{$message}}</x:tall-crud-generator::error-message> @enderror
            </div>
        </x-slot>

        <x-slot name="footer">
            <x:tall-crud-generator::button wire:click="$set('confirmingItemCreation', false)">Cancel</x:tall-crud-generator::button>
            <x:tall-crud-generator::button mode="add" wire:loading.attr="disabled" wire:click="createItem()">Save</x:tall-crud-generator::button>
        </x-slot>
    </x:tall-crud-generator::dialog-modal>

    <x:tall-crud-generator::dialog-modal wire:model="confirmingItemEdit">
        <x-slot name="title">
            Edit Task
        </x-slot>

        <x-slot name="content">
            <div class="mt-4">
                <x:tall-crud-generator::label>Task</x:tall-crud-generator::label>
                <x:tall-crud-generator::input class="block mt-1 w-1/2" type="text" wire:model.defer="item.title" />
                @error('item.title') <x:tall-crud-generator::error-message>{{$message}}</x:tall-crud-generator::error-message> @enderror
            </div>

            <div class="grid grid-cols-3">
                <div class="mt-4">
                    <x:tall-crud-generator::label>Project</x:tall-crud-generator::label>
                    <x:tall-crud-generator::select class="block mt-1 w-full" wire:model.defer="item.project_id">
                        @foreach($projects as $c)
                        <option value="{{$c->id}}">{{$c->name}}</option>
                        @endforeach
                    </x:tall-crud-generator::select>
                </div>
            </div>
            <div class="mt-4">
                <x:tall-crud-generator::label>Complexity</x:tall-crud-generator::label>
                <x:tall-crud-generator::select class="block mt-1 w-1/4" wire:model.defer="item.complexity">
                    @for($i = 3; $i >= 1; $i--)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </x:tall-crud-generator::select>
            </div>
            <div class="mt-4">
                <x:tall-crud-generator::label>Priority</x:tall-crud-generator::label>
                <x:tall-crud-generator::select class="block mt-1 w-1/4" wire:model.defer="item.priority">
                    @for($i = 3; $i >= 1; $i--)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </x:tall-crud-generator::select>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x:tall-crud-generator::button wire:click="$set('confirmingItemEdit', false)">Cancel</x:tall-crud-generator::button>
            <x:tall-crud-generator::button mode="edit" wire:loading.attr="disabled" wire:click="editItem()">Save</x:tall-crud-generator::button>
        </x-slot>
    </x:tall-crud-generator::dialog-modal>
</div>
