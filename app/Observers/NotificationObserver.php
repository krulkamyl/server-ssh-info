<?php

namespace App\Observers;

use App\Enums\DiscordNotification;
use App\Enums\MerkandiDiscordChannels;
use App\Models\Notification;
use App\Notifications\DownNotification;
use App\Notifications\LongQueueWaitNotification;
use App\Notifications\UpNotification;
use GuzzleHttp\Client;
use NotificationChannels\Discord\Discord;
use NotificationChannels\Discord\DiscordChannel;

class NotificationObserver
{
    public function created(Notification $notification): void
    {
        $discord = new Discord(new Client(), config('services.discord.token'));
        $channel = new DiscordChannel($discord);
        $channel->send(
            new DiscordNotification(config('services.discord.error_channel')),
            new DownNotification($notification)
        );
    }

    public function deleted(Notification $notification): void
    {
        $discord = new Discord(new Client(), config('services.discord.token'));
        $channel = new DiscordChannel($discord);
        $channel->send(
            new DiscordNotification(config('services.discord.error_channel')),
            new UpNotification($notification)
        );
    }

}
