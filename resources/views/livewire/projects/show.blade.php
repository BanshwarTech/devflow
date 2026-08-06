<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $project->name }}</h2>
                <p class="text-sm text-gray-500">{{ Str::limit($project->description, 120) ?: 'No description provided.' }}</p>
            </div>
            <div class="flex items-center gap-3">
                @php($statusClass = [
                    'planning' => 'bg-blue-100 text-blue-800',
                    'active' => 'bg-emerald-100 text-emerald-800',
                    'on_hold' => 'bg-amber-100 text-amber-800',
                    'completed' => 'bg-gray-100 text-gray-800',
                    'cancelled' => 'bg-rose-100 text-rose-800',
                ][$project->status] ?? 'bg-gray-100 text-gray-800')
                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</span>
                @can('update', $project)
                    <a href="{{ route('projects.edit', $project) }}" wire:navigate class="px-3 py-1.5 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Edit</a>
                @endcan
                @can('manage projects')
                    <button type="button" wire:click="delete" wire:confirm="Delete this project?"
                        class="px-3 py-1.5 border border-red-300 rounded-md text-sm text-red-700 hover:bg-red-50">Delete</button>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Sidebar --}}
                <div class="space-y-6">
                    <div class="bg-white shadow rounded-lg p-6 space-y-4">
                        <div>
                            <p class="text-xs text-gray-500">Created</p>
                            <p class="text-sm text-gray-800">{{ $project->created_at?->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Start Date</p>
                            <p class="text-sm text-gray-800">{{ $project->start_date?->format('M d, Y') ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">End Date</p>
                            <p class="text-sm text-gray-800">{{ $project->end_date?->format('M d, Y') ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Owner</p>
                            <p class="text-sm text-gray-800">{{ $project->user?->name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Tasks</p>
                            <p class="text-sm text-gray-800">{{ $tasks->count() }} total ({{ $tasks->where('status', '!=', 'completed')->count() }} open)</p>
                        </div>
                        @can('manage tasks')
                            <a href="{{ route('tasks.create') }}" wire:navigate class="block text-center mt-2 px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 text-sm">New Task</a>
                        @endcan
                    </div>

                    {{-- Activity --}}
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Activity</h3>
                        <ul class="space-y-3 text-sm">
                            @forelse ($activities as $activity)
                                <li class="flex gap-3">
                                    <span class="text-gray-400">•</span>
                                    <p class="text-gray-600">{{ Str::limit($activity->description, 80) }}</p>
                                </li>
                            @empty
                                <li class="text-gray-400">No activity yet.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- Main: Tasks --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex justify-between items-center">
                        <h3 class="font-semibold text-gray-800">Tasks</h3>
                        @can('manage tasks')
                            <a href="{{ route('tasks.create') }}" wire:navigate class="text-sm text-indigo-600 hover:underline">Create a task →</a>
                        @endcan
                    </div>

                    <div class="space-y-3">
                        @forelse ($tasks as $task)
                            @php($taskStatus = [
                                'pending' => 'bg-gray-100 text-gray-800',
                                'in_progress' => 'bg-blue-100 text-blue-800',
                                'review' => 'bg-purple-100 text-purple-800',
                                'completed' => 'bg-emerald-100 text-emerald-800',
                                'blocked' => 'bg-rose-100 text-rose-800',
                            ][$task->status] ?? 'bg-gray-100 text-gray-800')
                            <div class="border border-gray-100 rounded-lg p-4 hover:bg-gray-50 transition">
                                <div class="flex justify-between">
                                    <a href="{{ route('tasks.show', $task) }}" wire:navigate class="font-medium text-indigo-600 hover:underline">{{ $task->title }}</a>
                                    <span class="px-2 py-0.5 text-xs rounded-full {{ $taskStatus }}">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                                </div>
                                @if ($task->description)
                                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit($task->description, 80) }}</p>
                                @endif
                                <div class="flex items-center gap-3 mt-2 text-xs text-gray-500">
                                    @if ($task->assignedTo)
                                        <span>Assignee: {{ $task->assignedTo->name }}</span>
                                    @endif
                                    @if ($task->due_date)
                                        <span>Due: {{ $task->due_date->format('M d') }}</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400">No tasks for this project.</p>
                        @endforelse
                    </div>

                    {{-- Comments --}}
                    <div class="pt-6 border-t">
                        <h3 class="font-semibold text-gray-800 mb-4">Comments</h3>
                        <livewire:comments.comment-list :model="$project" />
                        <livewire:comments.create :model="$project" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
