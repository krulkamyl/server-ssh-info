<?php

namespace App\Models;

use App\Enums\NotificationTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Notification extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'hostname',
        'type',
        'value',
    ];

    protected $casts = [
        'type' => NotificationTypeEnum::class,
    ];

    public function routeNotificationForDiscord()
    {
        return config('service.discord.error_channel');
    }
}
