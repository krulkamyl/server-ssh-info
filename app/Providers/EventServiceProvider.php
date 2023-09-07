<?php

namespace App\Providers;

use App\Events\CpuValueExceeded;
use App\Events\CpuValueExceededResolved;
use App\Events\RamValueExceeded;
use App\Events\RamValueExceededResolved;
use App\Events\ServerDown;
use App\Events\ServerHistoryCreated;
use App\Events\ServerUp;
use App\Events\ServiceDown;
use App\Events\ServiceUp;
use App\Listeners\EventRemoveAndNotificateAboutProblemResolved;
use App\Listeners\EventSaveAndNotificateAboutProblem;
use App\Listeners\ServerParameterThresholdListener;
use App\Models\Notification;
use App\Models\ServerHistory;
use App\Observers\NotificationObserver;
use App\Observers\ServerHistoryObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ServerHistoryCreated::class  => [
            ServerParameterThresholdListener::class
        ],

        ServiceDown::class => [
            EventSaveAndNotificateAboutProblem::class,
        ],

        ServiceUp::class => [
            EventRemoveAndNotificateAboutProblemResolved::class,
        ],

        ServerDown::class => [
            EventSaveAndNotificateAboutProblem::class,
        ],

        ServerUp::class => [
            EventRemoveAndNotificateAboutProblemResolved::class,
        ],

        CpuValueExceeded::class => [
            EventSaveAndNotificateAboutProblem::class,
        ],

        CpuValueExceededResolved::class => [
            EventRemoveAndNotificateAboutProblemResolved::class,
        ],

        RamValueExceeded::class => [
            EventSaveAndNotificateAboutProblem::class,
        ],

        RamValueExceededResolved::class => [
            EventRemoveAndNotificateAboutProblemResolved::class
        ],
    ];

    public function boot(): void
    {
        ServerHistory::observe(ServerHistoryObserver::class);
        Notification::observe(NotificationObserver::class);
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
