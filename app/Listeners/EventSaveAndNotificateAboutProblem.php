<?php

namespace App\Listeners;

use App\Models\Notification;

class EventSaveAndNotificateAboutProblem
{
    public function __construct()
    {
    }

    public function handle(object $event): void
    {
        $check = Notification::where('hostname', $event->hostname)
            ->where('type', $event->type->name)->exists();

        if (!$check) {
            $notification = new Notification();
            $notification->hostname = $event->hostname;
            $notification->type = $event->type->name;
            $notification->value = $event->value ?? null;
            $notification->save();
        }
    }
}
