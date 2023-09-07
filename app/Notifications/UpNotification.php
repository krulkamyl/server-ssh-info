<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Notification as NotificationModel;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;

class UpNotification extends Notification
{
    use Queueable;

    public function __construct(
        public NotificationModel $notification
    )
    {
    }

    public function via(object $notifiable): array
    {
        return [DiscordChannel::class];
    }

    public function toDiscord($notifiable = null)
    {
        return DiscordMessage::create("🟢 [**{$this->notification->hostname}**] @here ". sprintf($this->notification->type->messageableResolve(), $this->notification->value ?? ''). sprintf( " (resolve time: %s)", $this->notification->created_at->diffForHumans()));
    }
}
