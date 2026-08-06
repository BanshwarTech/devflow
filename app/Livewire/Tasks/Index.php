<?php

namespace App\Livewire\Tasks;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $status = '';

    #[Url]
    public string $priority = '';

    #[Url]
    public string $project = '';

    public function boot()
    {
        $this->authorize('viewAny', Task::class);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedPriority(): void
    {
        $this->resetPage();
    }

    public function updatedProject(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'status', 'priority', 'project']);
        $this->resetPage();
    }

    #[Computed]
    public function statuses(): array
    {
        return ['pending', 'in_progress', 'review', 'completed', 'blocked'];
    }

    #[Computed]
    public function priorities(): array
    {
        return ['low', 'medium', 'high', 'urgent'];
    }

    #[Computed]
    public function projects()
    {
        return Project::query()
            ->when(! Gate::allows('manage projects'), fn ($q) => $q->where('user_id', auth()->id()))
            ->pluck('name', 'id');
    }

    #[Computed]
    public function tasks()
    {
        return Task::query()
            ->when($this->search, fn ($q) => $q->where('title', 'like', '%'.$this->search.'%'))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->priority, fn ($q) => $q->where('priority', $this->priority))
            ->when($this->project, fn ($q) => $q->where('project_id', $this->project))
            ->when(! Gate::allows('manage tasks'), function ($q) {
                $q->where(function ($sub) {
                    $sub->where('created_by', auth()->id())
                        ->orWhere('assigned_to', auth()->id());
                });
            })
            ->with(['project', 'assignedTo', 'createdBy'])
            ->latest()
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.tasks.index', [
            'statuses' => ['pending', 'in_progress', 'review', 'completed', 'blocked'],
            'priorities' => ['low', 'medium', 'high', 'urgent'],
            'projects' => $this->projects,
            'tasks' => $this->tasks,
        ]);
    }
}
