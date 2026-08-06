<?php

namespace App\Livewire\Teams;

use App\Models\Team;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    public Team $team;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string')]
    public ?string $description = null;

    #[Validate('required|exists:users,id')]
    public ?int $owner_id = null;

    public function mount(Team $team)
    {
        $this->authorize('update', $team);

        $this->team = $team;
        $this->name = $team->name;
        $this->description = $team->description;
        $this->owner_id = $team->owner_id;
    }

    #[Computed]
    public function users()
    {
        return User::pluck('name', 'id');
    }

    public function update()
    {
        $this->authorize('update', $this->team);

        $this->validate();

        $this->team->update([
            'name' => $this->name,
            'description' => $this->description,
            'owner_id' => $this->owner_id,
        ]);

        $this->dispatch('team-updated', $this->team->id);

        return $this->redirectRoute('teams.show', $this->team, navigate: true);
    }

    public function render()
    {
        return view('livewire.teams.edit', [
            'users' => $this->users,
        ]);
    }
}
