<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Task</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form wire:submit.prevent="create" class="bg-white shadow rounded-lg p-8 space-y-6">
                <div>
                    <x-input-label for="title" value="Task Title" />
                    <x-text-input id="title" type="text" name="title" wire:model="title" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" value="Description" />
                    <textarea id="description" rows="4" name="description" wire:model="description"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="project_id" value="Project" />
                        <select id="project_id" name="project_id" wire:model="project_id"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Select a project</option>
                            @foreach ($projects as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('project_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="assigned_to" value="Assignee" />
                        <select id="assigned_to" name="assigned_to" wire:model="assigned_to"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Unassigned</option>
                            @foreach ($users as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('assigned_to')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="status" value="Status" />
                        <select id="status" name="status" wire:model="status"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            @foreach (['pending', 'in_progress', 'review', 'completed', 'blocked'] as $value)
                                <option value="{{ $value }}">{{ ucfirst(str_replace('_', ' ', $value)) }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="priority" value="Priority" />
                        <select id="priority" name="priority" wire:model="priority"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            @foreach (['low', 'medium', 'high', 'urgent'] as $value)
                                <option value="{{ $value }}">{{ ucfirst($value) }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="due_date" value="Due Date" />
                        <x-text-input id="due_date" type="date" name="due_date" wire:model="due_date" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <x-primary-button wire:loading.attr="disabled">
                        <span wire:loading inline>Creating…</span>
                        <span wire:loading.remove>Save Task</span>
                    </x-primary-button>
                    <a href="{{ route('tasks.index') }}" wire:navigate class="text-sm text-gray-600 hover:text-gray-800">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
