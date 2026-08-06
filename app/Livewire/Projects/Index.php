<?php

namespace App\Livewire\Projects;

use App\Models\Project;
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

    public function boot()
    {
        $this->authorize('viewAny', Project::class);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'status']);
        $this->resetPage();
    }

    #[Computed]
    public function statuses(): array
    {
        return ['planning', 'active', 'on_hold', 'completed', 'cancelled'];
    }

    #[Computed]
    public function projects()
    {
        return Project::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when(! Gate::allows('manage projects'), fn ($q) => $q->where('user_id', auth()->id()))
            ->latest()
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.projects.index', [
            'statuses' => ['planning', 'active', 'on_hold', 'completed', 'cancelled'],
            'projects' => $this->projects,
        ]);
    }
}
