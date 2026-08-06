<?php

namespace App\Livewire\Teams;

use App\Models\Team;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public function boot()
    {
        $this->authorize('viewAny', Team::class);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search']);
        $this->resetPage();
    }

    public function render()
    {
        $teams = Team::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->when(Gate::allows('manage teams'), fn ($q) => $q, fn ($q) => $q->where('owner_id', auth()->id()))
            ->with('owner')
            ->latest()
            ->paginate(10);

        return view('livewire.teams.index', [
            'teams' => $teams,
        ]);
    }
}
