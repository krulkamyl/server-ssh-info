<?php

namespace App\Events;

use App\Enums\NotificationTypeEnum;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServerDown
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public NotificationTypeEnum $type = NotificationTypeEnum::SERVER_DOWN;

    public function __construct(
        public string $hostname
    )
    {
    }

}
