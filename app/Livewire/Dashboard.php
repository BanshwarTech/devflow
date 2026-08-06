<?php

namespace App\Livewire;

use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Dashboard extends Component
{
    public bool $mine = false;

    public function render()
    {
        return view('livewire.dashboard', [
            'projectCount' => $this->projectCount,
            'taskCount' => $this->taskCount,
            'openTasks' => $this->openTasks,
            'teamCount' => $this->teamCount,
            'userCount' => $this->userCount,
            'upcomingDeadlines' => $this->upcomingDeadlines,
            'recentActivity' => $this->recentActivity,
            'recentProjects' => $this->recentProjects,
        ]);
    }

    #[Computed]
    public function projectCount(): int
    {
        return Project::count();
    }

    #[Computed]
    public function taskCount(): int
    {
        return Task::count();
    }

    #[Computed]
    public function teamCount(): int
    {
        return Team::count();
    }

    #[Computed]
    public function userCount(): int
    {
        return User::count();
    }

    #[Computed]
    public function openTasks(): int
    {
        return Task::whereNotIn('status', ['completed', 'blocked', 'cancelled'])->count();
    }

    #[Computed]
    public function upcomingDeadlines(): mixed
    {
        return Task::where('due_date', '>=', now())
            ->whereNotIn('status', ['completed', 'blocked', 'cancelled'])
            ->with('project')
            ->orderBy('due_date')
            ->limit(6)
            ->get();
    }

    #[Computed]
    public function recentProjects(): mixed
    {
        return Project::query()
            ->when(Gate::allows('manage projects') && ! $this->mine, fn ($q) => $q, fn ($q) => $q->where('user_id', auth()->id()))
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();
    }

    #[Computed]
    public function recentActivity(): mixed
    {
        return Activity::query()
            ->with('causer')
            ->latest()
            ->limit(8)
            ->get();
    }
}
