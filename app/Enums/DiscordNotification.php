<?php

namespace App\Enums;

use Illuminate\Notifications\Notifiable;

class DiscordNotification
{
    use Notifiable;

    public function __construct(public string $channel)
    {
    }

    public function routeNotificationForDiscord()
    {
        return $this->channel;
    }
}
