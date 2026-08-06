<?php

namespace App\Listeners;

use App\Events\CommentAdded;
use App\Events\ProjectCreated;
use App\Events\TaskUpdated;
use App\Models\Activity;

class LogActivity
{
    public function handle($event): void
    {
        match (true) {
            $event instanceof ProjectCreated => Activity::create([
                'description' => 'created project',
                'subject_id' => $event->project->id,
                'subject_type' => $event->project::class,
                'causer_id' => $event->project->user_id,
            ]),

            $event instanceof TaskUpdated => Activity::create([
                'description' => 'updated task',
                'subject_id' => $event->task->id,
                'subject_type' => $event->task::class,
                'causer_id' => $event->task->created_by,
            ]),

            $event instanceof TaskAssigned => Activity::create([
                'description' => 'assigned task',
                'subject_id' => $event->task->id,
                'subject_type' => $event->task::class,
                'causer_id' => $event->task->created_by,
                'properties' => ['assigned_to' => $event->task->assigned_to],
            ]),

            $event instanceof CommentAdded => Activity::create([
                'description' => 'added comment',
                'subject_id' => $event->comment->commentable_id,
                'subject_type' => $event->comment->commentable_type,
                'causer_id' => $event->comment->user_id,
            ]),
        };
    }
}
