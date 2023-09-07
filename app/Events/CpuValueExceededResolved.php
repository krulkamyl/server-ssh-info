<?php

namespace App\Events;

use App\Enums\NotificationTypeEnum;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CpuValueExceededResolved
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public NotificationTypeEnum $type = NotificationTypeEnum::CPU_VALUE_EXCEEDED;

    public function __construct(
        public string $hostname
    )
    {
    }
}
