<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Projects</h2>
            @can('manage projects')
                <a href="{{ route('projects.create') }}" wire:navigate class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">New Project</a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Filters --}}
            <div class="flex flex-col sm:flex-row gap-4 mb-6">
                <div class="relative flex-1">
                    <input type="text" placeholder="Search projects..."
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                        wire:model.live="search" />
                    <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.356-4.356m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 017.5 7.5z" /></svg>
                </div>

                <select wire:model.live="status" class="border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <option value="">All Statuses</option>
                    @foreach ($statuses as $value)
                        <option value="{{ $value }}">{{ ucfirst(str_replace('_', ' ', $value)) }}</option>
                    @endforeach
                </select>

                @if ($search || $status)
                    <button type="button" wire:click="clearFilters"
                        class="px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-600 hover:bg-gray-50">Clear</button>
                @endif
            </div>

            {{-- List --}}
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                        <tr>
                            <th class="px-6 py-3">Project</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Tasks</th>
                            <th class="px-6 py-3">Owner</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($projects as $project)
                            <tr>
                                <td class="px-6 py-3">
                                    <a href="{{ route('projects.show', $project) }}" wire:navigate class="font-medium text-indigo-600 hover:underline">{{ Str::limit($project->name, 40) }}</a>
                                    @if ($project->description)
                                        <p class="text-sm text-gray-500 mt-1">{{ Str::limit($project->description, 60) }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    @php
                                        $statusClass = [
                                            'planning' => 'bg-blue-100 text-blue-800',
                                            'active' => 'bg-emerald-100 text-emerald-800',
                                            'on_hold' => 'bg-amber-100 text-amber-800',
                                            'completed' => 'bg-gray-100 text-gray-800',
                                            'cancelled' => 'bg-rose-100 text-rose-800',
                                        ][$project->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</span>
                                </td>
                                <td class="px-6 py-3 text-gray-600">{{ $project->tasks_count ?? $project->tasks->count() }}</td>
                                <td class="px-6 py-3 text-gray-500">{{ $project->user?->name ?? '—' }}</td>
                                <td class="px-6 py-3 text-right space-x-1">
                                    <a href="{{ route('projects.show', $project) }}" wire:navigate class="text-indigo-600 hover:text-indigo-900" title="View">👁</a>
                                    @can('update', $project)
                                        <a href="{{ route('projects.edit', $project) }}" wire:navigate class="text-amber-600 hover:text-amber-900" title="Edit">✏</a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">No projects found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $projects->links() }}
        </div>
    </div>
</x-app-layout>
