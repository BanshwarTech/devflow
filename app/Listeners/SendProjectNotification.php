<?php

namespace App\Listeners;

use App\Events\ProjectCreated;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SendProjectNotification implements ShouldQueue
{
    public function handle(ProjectCreated $event): void
    {
        $project = $event->project;
        $users = User::role('Admin')->get();

        foreach ($users as $user) {
            $user->notify(new class($project) extends Notification {
                public function __construct(public $project) {}

                public function via($notifiable): array
                {
                    return ['database'];
                }

                public function toArray($notifiable): array
                {
                    return [
                        'title' => 'New Project Created',
                        'body' => "Project '{$this->project->name}' has been created by {$this->project->user->name}.",
                        'url' => route('projects.show', $this->project),
                    ];
                }
            });
        }
    }
}
