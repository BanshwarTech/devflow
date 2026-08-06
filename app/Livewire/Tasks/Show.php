<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use Livewire\Component;

class Show extends Component
{
    public Task $task;

    public function mount(Task $task)
    {
        $this->authorize('view', $task);

        $this->task = $task;
    }

    protected $listeners = [
        'task-updated' => '$refresh',
    ];

    public function render()
    {
        $this->task->load(['project', 'assignedTo', 'createdBy', 'comments.user', 'attachments']);

        return view('livewire.tasks.show');
    }
}
