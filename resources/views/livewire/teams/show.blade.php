<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $team->name }}</h2>
                <p class="text-sm text-gray-500">{{ $team->description ?: 'No description provided.' }}</p>
            </div>
            <div class="flex items-center gap-3">
                @can('update', $team)
                    <a href="{{ route('teams.edit', $team) }}" wire:navigate class="px-3 py-1.5 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Edit</a>
                @endcan
                @can('delete', $team)
                    <button type="button" wire:click="delete" wire:confirm="Delete this team?"
                        class="px-3 py-1.5 border border-red-300 rounded-md text-sm text-red-700 hover:bg-red-50">Delete</button>
                @endcan
            </div>
        </div>
    </x-slot

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Sidebar --}}
                <div class="space-y-6">
                    <div class="bg-white shadow rounded-lg p-6 space-y-4 text-sm">
                        <div>
                            <p class="text-xs text-gray-500">Owner</p>
                            <p class="text-gray-700 font-medium">{{ $team->owner?->name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Members</p>
                            <p class="text-gray-700">{{ $members->count() }} member(s)</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Created</p>
                            <p class="text-gray-700">{{ $team->created_at?->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Main: Members + Add Member --}}
                <div class="lg:col-span-2 space-y-6">
                    @can('manageMembers', $team)
                        <div class="bg-white shadow rounded-lg p-6">
                            <h3 class="font-semibold text-gray-800 mb-4">Add Member</h3>
                            <form wire:submit.prevent="addMember" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                <div class="md:col-span-2">
                                    <x-input-label for="user_id" value="User" />
                                    <select id="user_id" name="user_id" wire:model="user_id"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Select a user</option>
                                        @foreach ($users as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="role" value="Role" />
                                    <select id="role" name="role" wire:model="role"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="member">Member</option>
                                        <option value="admin">Admin</option>
                                        <option value="owner">Owner</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                                </div>

                                <div>
                                    <x-primary-button wire:loading.attr="disabled">
                                        <span wire:loading inline>Adding…</span>
                                        <span wire:loading.remove>Add</span>
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    @endcan

                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="font-semibold text-gray-800 mb-4">Members</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                                    <tr>
                                        <th class="px-4 py-2">User</th>
                                        <th class="px-4 py-2">Role</th>
                                        <th class="px-4 py-2 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse ($members as $member)
                                        <tr>
                                            <td class="px-4 py-2">{{ $member->user?->name ?? '—' }}</td>
                                            <td class="px-4 py-2">{{ ucfirst($member->role) }}</td>
                                            <td class="px-4 py-2 text-right">
                                                @can('manageMembers', $team)
                                                    <button type="button" wire:click="removeMember({{ $member->user_id }})"
                                                        wire:confirm="Remove this member?"
                                                        class="text-rose-600 hover:text-rose-800">Remove</button>
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="px-4 py-4 text-gray-400">No members yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
