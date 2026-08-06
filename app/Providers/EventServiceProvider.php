<?php

namespace App\Providers;

use App\Events\CommentAdded;
use App\Events\ProjectCreated;
use App\Events\TaskAssigned;
use App\Events\TaskUpdated;
use App\Listeners\LogActivity;
use App\Listeners\SendProjectNotification;
use App\Listeners\SendTaskAssignmentNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        ProjectCreated::class => [
            SendProjectNotification::class,
            LogActivity::class,
        ],

        TaskAssigned::class => [
            SendTaskAssignmentNotification::class,
            LogActivity::class,
        ],

        TaskUpdated::class => [
            LogActivity::class,
        ],

        CommentAdded::class => [
            LogActivity::class,
        ],
    ];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
