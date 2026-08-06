<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Team</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form wire:submit.prevent="update" class="bg-white shadow rounded-lg p-8 space-y-6">
                <div>
                    <x-input-label for="name" value="Team Name" />
                    <x-text-input id="name" type="text" name="name" wire:model="name" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" value="Description" />
                    <textarea id="description" rows="4" name="description" wire:model="description"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="owner_id" value="Team Owner" />
                    <select id="owner_id" name="owner_id" wire:model="owner_id"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        @foreach ($users as $id => $name)
                            <option value="{{ $id }}" {{ $id == $owner_id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('owner_id')" class="mt-2" />
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <x-primary-button wire:loading.attr="disabled">
                        <span wire:loading inline>Saving…</span>
                        <span wire:loading.remove>Save Changes</span>
                    </x-primary-button>
                    <a href="{{ route('teams.show', $team) }}" wire:navigate class="text-sm text-gray-600 hover:text-gray-800">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
