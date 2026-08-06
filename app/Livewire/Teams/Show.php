<?php

namespace App\Livewire\Teams;

use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Show extends Component
{
    public Team $team;

    #[Validate('required|exists:users,id')]
    public ?int $user_id = null;

    #[Validate('required|in:owner,admin,member')]
    public string $role = 'member';

    public function mount(Team $team)
    {
        $this->authorize('view', $team);

        $this->team = $team;
    }

    protected $listeners = [
        'team-updated' => '$refresh',
        'member-added' => '$refresh',
        'member-removed' => '$refresh',
    ];

    public function addMember()
    {
        $this->authorize('manageMembers', $this->team);

        $this->validate();

        $exists = TeamMember::where('team_id', $this->team->id)
            ->where('user_id', $this->user_id)
            ->exists();

        if ($exists) {
            $this->addError('user_id', 'This user is already a member of this team.');
            return;
        }

        TeamMember::create([
            'team_id' => $this->team->id,
            'user_id' => $this->user_id,
            'role' => $this->role,
        ]);

        $this->reset(['user_id', 'role']);
        $this->dispatch('member-added');
    }

    public function removeMember(int $userId): void
    {
        $this->authorize('manageMembers', $this->team);

        TeamMember::where('team_id', $this->team->id)
            ->where('user_id', $userId)
            ->delete();

        $this->dispatch('member-removed');
    }

    public function delete()
    {
        $this->authorize('delete', $this->team);

        $this->team->delete();

        return $this->redirectRoute('teams.index', navigate: true);
    }

    #[Computed]
    public function users()
    {
        return User::pluck('name', 'id');
    }

    public function render()
    {
        $members = $this->team->members()->with('user')->get();

        return view('livewire.teams.show', [
            'members' => $members,
            'users' => $this->users,
        ]);
    }
}
