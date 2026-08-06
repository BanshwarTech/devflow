<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Teams</h2>
            @can('manage teams')
                <a href="{{ route('teams.create') }}" wire:navigate class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 text-sm">New Team</a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Search --}}
            <div class="flex flex-col sm:flex-row gap-4 mb-6">
                <div class="relative flex-1 min-w-56">
                    <input type="text" placeholder="Search teams..."
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                        wire:model.live="search" />
                    <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.356-4.356m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 017.5 7.5z" /></svg>
                </div>

                @if ($search)
                    <button type="button" wire:click="clearFilters"
                        class="px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-600 hover:bg-gray-50">Clear</button>
                @endif
            </div>

            {{-- List --}}
            <div class="bg-white shadow rounded-lg overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                        <tr>
                            <th class="px-6 py-3">Team</th>
                            <th class="px-6 py-3">Owner</th>
                            <th class="px-6 py-3">Members</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($teams as $team)
                            <tr>
                                <td class="px-6 py-3">
                                    <a href="{{ route('teams.show', $team) }}" wire:navigate class="font-medium text-indigo-600 hover:underline">{{ $team->name }}</a>
                                    @if ($team->description)
                                        <p class="text-sm text-gray-500 mt-1">{{ Str::limit($team->description, 60) }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-gray-600">{{ $team->owner?->name ?? '—' }}</td>
                                <td class="px-6 py-3 text-gray-600">{{ $team->members->count() }} member(s)</td>
                                <td class="px-6 py-3 text-right space-x-1">
                                    <a href="{{ route('teams.show', $team) }}" wire:navigate class="text-indigo-600 hover:text-indigo-900" title="View">👁</a>
                                    @can('update', $team)
                                        <a href="{{ route('teams.edit', $team) }}" wire:navigate class="text-amber-600 hover:text-amber-900" title="Edit">✏</a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">No teams found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $teams->links() }}
        </div>
    </div>
</x-app-layout>
