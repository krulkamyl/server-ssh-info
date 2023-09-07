<?php

namespace App\Listeners;

use App\Enums\NotificationTypeEnum;
use App\Models\Notification;

class EventRemoveAndNotificateAboutProblemResolved
{
    public function __construct()
    {
    }

    public function handle(object $event): void
    {
        $check = Notification::where('hostname', $event->hostname)
            ->where('type', $event->type->name);

        if (isset($event->value) && $event->type == NotificationTypeEnum::SERVICE_DOWN) {
            $check->where('value', $event->value);
        }

        $check = $check->first();

        if ($check) {
            $check->delete();
        }
    }
}
