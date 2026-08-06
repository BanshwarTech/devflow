<?php

namespace App\Listeners;

use App\Events\TaskAssigned;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SendTaskAssignmentNotification implements ShouldQueue
{
    public function handle(TaskAssigned $event): void
    {
        $task = $event->task;

        if ($task->assignedTo) {
            $task->assignedTo->notify(new class($task) extends Notification {
                public function __construct(public $task) {}

                public function via($notifiable): array
                {
                    return ['database'];
                }

                public function toArray($notifiable): array
                {
                    return [
                        'title' => 'Task Assigned',
                        'body' => "You have been assigned to task '{$this->task->title}'.",
                        'url' => route('tasks.show', $this->task),
                    ];
                }
            });
        }
    }
}
