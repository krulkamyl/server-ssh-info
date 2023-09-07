<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'hostname',
        'RAM_usage',
        'RAM_max',
        'CPU_usage',
        'load_avg',
        'disk_capacity',
        'services_check',
        'connections_check',
    ];

    protected $casts = [
        'load_avg' => 'array',
        'disk_capacity' => 'array',
        'services_check' => 'array',
        'connections_check' => 'array',
    ];
}
