<?php

namespace App\Events;

use App\Enums\NotificationTypeEnum;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServiceUp
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;


    public NotificationTypeEnum $type = NotificationTypeEnum::SERVICE_DOWN;

    public function __construct(
        public string $hostname,
        public string $value
    )
    {
    }
}
