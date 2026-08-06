<?php

namespace App\Livewire\Teams;

use App\Models\Team;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string')]
    public ?string $description = null;

    #[Validate('required|exists:users,id')]
    public ?int $owner_id = null;

    public function boot()
    {
        $this->authorize('create', Team::class);
    }

    #[Computed]
    public function users()
    {
        return User::pluck('name', 'id');
    }

    public function create()
    {
        $this->validate();

        $team = Team::create([
            'name' => $this->name,
            'description' => $this->description,
            'owner_id' => $this->owner_id ?: auth()->id(),
        ]);

        return $this->redirectRoute('teams.show', $team, navigate: true);
    }

    public function render()
    {
        return view('livewire.teams.create', [
            'users' => $this->users,
        ]);
    }
}
