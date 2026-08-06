<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('manage projects') || $user->can('view dashboard');
    }

    public function view(User $user, Project $project): bool
    {
        return $user->can('manage projects') || $project->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can('manage projects');
    }

    public function update(User $user, Project $project): bool
    {
        return $user->can('manage projects') || $project->user_id === $user->id;
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->can('manage projects') || $project->user_id === $user->id;
    }
}
