<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('manage tasks') || $user->can('view dashboard');
    }

    public function view(User $user, Task $task): bool
    {
        return $user->can('manage tasks') || $task->created_by === $user->id || $task->assigned_to === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can('manage tasks');
    }

    public function update(User $user, Task $task): bool
    {
        return $user->can('manage tasks') || $task->created_by === $user->id || $task->assigned_to === $user->id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->can('manage tasks') || $task->created_by === $user->id;
    }
}
