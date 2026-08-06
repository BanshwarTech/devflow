<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $task->title }}</h2>
                <p class="text-sm text-gray-500">{{ $task->description ?: 'No description.' }}</p>
            </div>
            <div class="flex items-center gap-3">
                @php($priClass = [
                    'low' => 'bg-gray-100 text-gray-800',
                    'medium' => 'bg-amber-100 text-amber-800',
                    'high' => 'bg-orange-100 text-orange-800',
                    'urgent' => 'bg-rose-100 text-rose-800',
                ][$task->priority] ?? 'bg-gray-100 text-gray-800')
                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $priClass }}">{{ ucfirst($task->priority) }}</span>
                @can('update', $task)
                    <a href="{{ route('tasks.edit', $task) }}" wire:navigate class="px-3 py-1.5 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Edit</a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Sidebar --}}
                <div class="space-y-6">
                    <div class="bg-white shadow rounded-lg p-6 space-y-4 text-sm">
                        <div>
                            <p class="text-xs text-gray-500">Project</p>
                            <p class="text-gray-700 font-medium">{{ $task->project?->name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Status</p>
                            @php($taskStatus = [
                                'pending' => 'bg-gray-100 text-gray-800',
                                'in_progress' => 'bg-blue-100 text-blue-800',
                                'review' => 'bg-purple-100 text-purple-800',
                                'completed' => 'bg-emerald-100 text-emerald-800',
                                'blocked' => 'bg-rose-100 text-rose-800',
                            ][$task->status] ?? 'bg-gray-100 text-gray-800')
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $taskStatus }}">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Assignee</p>
                            <p class="text-gray-700">{{ $task->assignedTo?->name ?? 'Unassigned' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Created By</p>
                            <p class="text-gray-700">{{ $task->createdBy?->name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Due Date</p>
                            <p class="text-gray-700">{{ $task->due_date?->format('M d, Y') ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Created</p>
                            <p class="text-gray-700">{{ $task->created_at?->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Main: Comments --}}
                <div class="lg:col-span-2 space-y-6">
                    <h3 class="font-semibold text-gray-800">Comments</h3>
                    <livewire:comments.comment-list :model="$task" />
                    <livewire:comments.create :model="$task" />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
