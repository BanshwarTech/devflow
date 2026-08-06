<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('manage teams') || $user->can('view dashboard');
    }

    public function view(User $user, Team $team): bool
    {
        return $user->can('manage teams') || $team->owner_id === $user->id || $team->members()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->can('manage teams');
    }

    public function update(User $user, Team $team): bool
    {
        return $user->can('manage teams') || $team->owner_id === $user->id;
    }

    public function delete(User $user, Team $team): bool
    {
        return $user->can('manage teams') || $team->owner_id === $user->id;
    }

    public function manageMembers(User $user, Team $team): bool
    {
        return $user->can('manage teams') || $team->owner_id === $user->id;
    }
}
