<?php

namespace App\Livewire\Tasks;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    public Task $task;

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

    public function mount(Task $task)
    {
        $this->authorize('update', $task);

        $this->task = $task;
        $this->title = $task->title;
        $this->description = $task->description;
        $this->status = $task->status;
        $this->priority = $task->priority;
        $this->due_date = $task->due_date?->format('Y-m-d');
        $this->project_id = $task->project_id;
        $this->assigned_to = $task->assigned_to;
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

    public function update()
    {
        $this->authorize('update', $this->task);

        $this->validate();

        $this->task->update([
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date' => $this->due_date,
            'project_id' => $this->project_id,
            'assigned_to' => $this->assigned_to,
        ]);

        $this->dispatch('task-updated', $this->task->id);

        return $this->redirectRoute('tasks.show', $this->task, navigate: true);
    }

    public function render()
    {
        return view('livewire.tasks.edit', [
            'projects' => $this->projects,
            'users' => $this->users,
        ]);
    }
}
