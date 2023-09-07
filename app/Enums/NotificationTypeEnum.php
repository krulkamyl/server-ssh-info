<?php

namespace App\Enums;

enum NotificationTypeEnum
{
    case SERVER_DOWN;
    case CPU_VALUE_EXCEEDED;
    case RAM_VALUE_EXCEEDED;
    case SERVICE_DOWN;

    public function messageable(): string
    {
        return match ($this) {
            self::SERVER_DOWN => 'The server is down.',
            self::CPU_VALUE_EXCEEDED => 'CPU value exceeded the permissible level (%s %).',
            self::RAM_VALUE_EXCEEDED => 'RAM value exceeded the permissible level (%s %).',
            self::SERVICE_DOWN => 'The service **%s** is down.',
        };
    }

    public function messageableResolve(): string
    {
        return match ($this) {
            self::SERVER_DOWN => 'The server issue has been resolved.',
            self::CPU_VALUE_EXCEEDED => 'The CPU usage problem has been resolved.',
            self::RAM_VALUE_EXCEEDED => 'The RAM usage problem has been resolved.',
            self::SERVICE_DOWN => 'The service **%s** has been resolved.',
        };
    }

}
