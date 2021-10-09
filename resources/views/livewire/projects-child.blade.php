<div>

    <x:tall-crud-generator::confirmation-dialog wire:model="confirmingItemDeletion">
        <x-slot name="title">
            Delete Record
        </x-slot>

        <x-slot name="content">
            Are you sure you want to Delete Record?
        </x-slot>

        <x-slot name="footer">
            <x:tall-crud-generator::button wire:click="$set('confirmingItemDeletion', false)">Cancel</x:tall-crud-generator::button>
            <x:tall-crud-generator::button mode="delete" wire:loading.attr="disabled" wire:click="deleteItem()">Delete</x:tall-crud-generator::button>
        </x-slot>
    </x:tall-crud-generator::confirmation-dialog>

    <x:tall-crud-generator::dialog-modal wire:model="confirmingItemCreation">
        <x-slot name="title">
            Add Record
        </x-slot>

        <x-slot name="content">
            <div class="mt-4">
                <x:tall-crud-generator::label>Name</x:tall-crud-generator::label>
                <x:tall-crud-generator::input class="block mt-1 w-1/2" type="text" wire:model.defer="item.name" />
                @error('item.name') <x:tall-crud-generator::error-message>{{$message}}</x:tall-crud-generator::error-message> @enderror
            </div>
            <div class="mt-4">
                <x:tall-crud-generator::label>Notes</x:tall-crud-generator::label>
                <textarea class="block mt-1 w-1/2" type="text" wire:model.defer="item.notes"></textarea>
                @error('item.notes') <x:tall-crud-generator::error-message>{{$message}}</x:tall-crud-generator::error-message> @enderror
            </div>
        </x-slot>

        <x-slot name="footer">
            <x:tall-crud-generator::button wire:click="$set('confirmingItemCreation', false)">Cancel</x:tall-crud-generator::button>
            <x:tall-crud-generator::button mode="add" wire:loading.attr="disabled" wire:click="createItem()">Save</x:tall-crud-generator::button>
        </x-slot>
    </x:tall-crud-generator::dialog-modal>

    <x:tall-crud-generator::dialog-modal wire:model="confirmingItemEdit">
        <x-slot name="title">
            Edit Record
        </x-slot>

        <x-slot name="content">
            <div class="mt-4">
                <x:tall-crud-generator::label>Name</x:tall-crud-generator::label>
                <x:tall-crud-generator::input class="block mt-1 w-1/2" type="text" wire:model.defer="item.name" />
                @error('item.name') <x:tall-crud-generator::error-message>{{$message}}</x:tall-crud-generator::error-message> @enderror
            </div>
            <div class="mt-4">
                <x:tall-crud-generator::label>Notes</x:tall-crud-generator::label>
                <x:tall-crud-generator::input class="block mt-1 w-1/2" type="text" wire:model.defer="item.notes" />
                @error('item.notes') <x:tall-crud-generator::error-message>{{$message}}</x:tall-crud-generator::error-message> @enderror
            </div>
        </x-slot>

        <x-slot name="footer">
            <x:tall-crud-generator::button wire:click="$set('confirmingItemEdit', false)">Cancel</x:tall-crud-generator::button>
            <x:tall-crud-generator::button mode="add" wire:loading.attr="disabled" wire:click="editItem()">Save</x:tall-crud-generator::button>
        </x-slot>
    </x:tall-crud-generator::dialog-modal>
</div>
