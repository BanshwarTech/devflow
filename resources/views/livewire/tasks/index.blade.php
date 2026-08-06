<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tasks</h2>
            @can('manage tasks')
                <a href="{{ route('tasks.create') }}" wire:navigate class="px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 text-sm">New Task</a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Filters --}}
            <div class="flex flex-col sm:flex-row flex-wrap gap-4 mb-6">
                <div class="relative flex-1 min-w-56">
                    <input type="text" placeholder="Search tasks..."
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

                <select wire:model.live="priority" class="border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <option value="">All Priorities</option>
                    @foreach ($priorities as $value)
                        <option value="{{ $value }}">{{ ucfirst($value) }}</option>
                    @endforeach
                </select>

                @if (! empty($projects))
                    <select wire:model.live="project" class="border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">All Projects</option>
                        @foreach ($projects as $id => $name)
                            <option value="{{ $id }}">{{ Str::limit($name, 30) }}</option>
                        @endforeach
                    </select>
                @endif

                @if ($search || $status || $priority || $project)
                    <button type="button" wire:click="clearFilters"
                        class="px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-600 hover:bg-gray-50">Clear</button>
                @endif
            </div>

            {{-- List --}}
            <div class="bg-white shadow rounded-lg overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                        <tr>
                            <th class="px-6 py-3">Task</th>
                            <th class="px-6 py-3">Project</th>
                            <th class="px-6 py-3">Priority</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Due</th>
                            <th class="px-6 py-3">Assignee</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($tasks as $task)
                            @php($taskStatus = [
                                'pending' => 'bg-gray-100 text-gray-800',
                                'in_progress' => 'bg-blue-100 text-blue-800',
                                'review' => 'bg-purple-100 text-purple-800',
                                'completed' => 'bg-emerald-100 text-emerald-800',
                                'blocked' => 'bg-rose-100 text-rose-800',
                            ][$task->status] ?? 'bg-gray-100 text-gray-800')
                            <tr>
                                <td class="px-6 py-3">
                                    <a href="{{ route('tasks.show', $task) }}" wire:navigate class="font-medium text-indigo-600 hover:underline">{{ Str::limit($task->title, 40) }}</a>
                                </td>
                                <td class="px-6 py-3 text-gray-600">{{ $task->project?->name ?? '—' }}</td>
                                <td class="px-6 py-3">
                                    @php($priClass = [
                                        'low' => 'bg-gray-100 text-gray-800',
                                        'medium' => 'bg-amber-100 text-amber-800',
                                        'high' => 'bg-orange-100 text-orange-800',
                                        'urgent' => 'bg-rose-100 text-rose-800',
                                    ][$task->priority] ?? 'bg-gray-100 text-gray-800')
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $priClass }}">{{ ucfirst($task->priority) }}</span>
                                </td>
                                <td class="px-6 py-3"><span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $taskStatus }}">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span></td>
                                <td class="px-6 py-3 text-gray-600">{{ $task->due_date?->format('M d, Y') ?? '—' }}</td>
                                <td class="px-6 py-3 text-gray-600">{{ $task->assignedTo?->name ?? '—' }}</td>
                                <td class="px-6 py-3 text-right space-x-1">
                                    <a href="{{ route('tasks.show', $task) }}" wire:navigate class="text-indigo-600 hover:text-indigo-900" title="View">👁</a>
                                    @can('update', $task)
                                        <a href="{{ route('tasks.edit', $task) }}" wire:navigate class="text-amber-600 hover:text-amber-900" title="Edit">✏</a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400">No tasks found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $tasks->links() }}
        </div>
    </div>
</x-app-layout>
