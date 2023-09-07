<?php

namespace App\Events;

use App\Enums\NotificationTypeEnum;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendNotification
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public string $hostname,
        public NotificationTypeEnum $type,
        public mixed $value = null
    )
    {
    }
}
