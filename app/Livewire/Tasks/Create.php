<?php

namespace App\Livewire\Tasks;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('nullable|string')]
    public ?string $description = null;

    #[Validate('required|in:pending,in_progress,review,completed,blocked')]
    public string $status = 'pending';

    #[Validate('required|in:low,medium,high,urgent')]
    public string $priority = 'medium';

    #[Validate('nullable|date|after_or_equal:today')]
    public ?string $due_date = null;

    #[Validate('required|exists:projects,id')]
    public ?int $project_id = null;

    #[Validate('nullable|exists:users,id')]
    public ?int $assigned_to = null;

    public function boot()
    {
        $this->authorize('create', Task::class);
    }

    #[Computed]
    public function projects()
    {
        return Project::pluck('name', 'id');
    }

    #[Computed]
    public function users()
    {
        return User::pluck('name', 'id');
    }

    public function create()
    {
        $this->validate();

        $task = Task::create([
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date' => $this->due_date,
            'project_id' => $this->project_id,
            'assigned_to' => $this->assigned_to,
            'created_by' => auth()->id(),
        ]);

        return $this->redirectRoute('tasks.show', $task, navigate: true);
    }

    public function render()
    {
        return view('livewire.tasks.create', [
            'projects' => $this->projects,
            'users' => $this->users,
        ]);
    }
}
