<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
                <span class="text-sm text-gray-500" wire:ignore.self>
                    {{ now()->format('l, M d, Y') }}
                </span>
            </div>
            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" value="1" wire:model.live="mine"
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                    <span>Show only my projects</span>
                </label>
                <button type="button" wire:click="$refresh" wire:loading.attr="disabled"
                    class="px-3 py-1.5 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">
                    <span wire:loading.inline>Loading…</span>
                    <span wire:loading.remove>Refresh</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            {{-- Stats --}}
            <section>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <x-stat :label="__('Projects')" :value="$projectCount" color="blue" icon="📁" />
                    <x-stat :label="__('Tasks')" :value="$taskCount" color="amber" icon="✅" />
                    <x-stat :label="__('Open Tasks')" :value="$openTasks" color="rose" icon="🔔" />
                    <x-stat :label="__('Teams')" :value="$teamCount" color="emerald" color-fallback="green" icon="👥" />
                    <x-stat :label="__('Users')" :value="$userCount" color="purple" icon="👤" />
                </div>
            </section>

            {{-- Quick Actions --}}
            <section class="flex flex-wrap gap-3">
                @can('manage projects')
                    <a href="{{ route('projects.create') }}" wire:navigate class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium">Create Project</a>
                @endcan
                @can('manage tasks')
                    <a href="{{ route('tasks.create') }}" wire:navigate class="px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 text-sm font-medium">Create Task</a>
                @endcan
                @can('manage teams')
                    <a href="{{ route('teams.create') }}" wire:navigate class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 text-sm font-medium">Create Team</a>
                @endcan
            </section>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Upcoming Deadlines --}}
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Upcoming Deadlines</h3>
                    @forelse ($upcomingDeadlines as $task)
                        <div class="flex items-center justify-between py-3 border-b last:border-0">
                            <div>
                                <p class="font-medium text-gray-800">{{ Str::limit($task->title, 40) }}</p>
                                <p class="text-sm text-gray-500">{{ $task->project?->name ?? '—' }}</p>
                            </div>
                            <span class="text-sm text-red-600 font-medium">{{ $task->due_date?->format('M d') }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">No upcoming deadlines.</p>
                    @endforelse
                </div>

                {{-- Recent Activity --}}
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h3>
                    @forelse ($recentActivity as $activity)
                        <div class="py-3 border-b last:border-0">
                            <p class="text-sm text-gray-700">{!! \Illuminate\Support\Str::limit(nl2br(e($activity->description)), 80) !!}</p>
                            @if ($activity->causer)
                                <p class="text-xs text-gray-400">by {{ $activity->causer->name }} · {{ $activity->created_at->diffForHumans() }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">No recent activity.</p>
                    @endforelse
                </div>

                {{-- Recent Projects --}}
                <div class="bg-white rounded-xl shadow p-6 lg:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Projects</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="text-xs text-gray-500 uppercase">
                                    <th class="pb-2">Project</th>
                                    <th class="pb-2">Status</th>
                                    <th class="pb-2">Owner</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentProjects as $project)
                                    <tr class="border-t">
                                        <td class="py-2 font-medium">
                                            <a href="{{ route('projects.show', $project) }}" wire:navigate class="text-indigo-600 hover:underline">{{ Str::limit($project->name, 35) }}</a>
                                        </td>
                                        <td class="py-2">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</td>
                                        <td class="py-2 text-gray-500">{{ $project->user?->name ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="py-3 text-gray-400">No projects found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
