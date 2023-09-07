<?php

namespace App\Listeners;

use App\Events\CpuValueExceeded;
use App\Events\CpuValueExceededResolved;
use App\Events\RamValueExceeded;
use App\Events\RamValueExceededResolved;
use App\Events\ServerHistoryCreated;
use App\Events\ServiceDown;
use App\Events\ServiceUp;

class ServerParameterThresholdListener
{
    public function __construct()
    {
    }

    public function handle(ServerHistoryCreated $event): void
    {
        $model = $event->serverHistory;
        $config = null;

        foreach (config('services.servers') as $server) {
            if (isset($server->hostname) && $server->hostname == $model->hostname) {
                $config = $server;
            }
        }

        // check cpu
        $cpuLimit = $config->warning_notification_values->cpu;
        if ($model->CPU_usage > $cpuLimit) {
            event(new CpuValueExceeded($model->hostname, $config->warning_notification_values->cpu));
        } else {
            event(new CpuValueExceededResolved($model->hostname));
        }

        // check ram
        $ramLimitPercent = $config->warning_notification_values->ram;
        $currentRamPercent = ($model->RAM_usage / $model->RAM_max) * 100;
        if ($currentRamPercent > $ramLimitPercent) {
            event(new RamValueExceeded($model->hostname, $config->warning_notification_values->ram));
        } else {
            event(new RamValueExceededResolved($model->hostname));
        }

        // check services
        foreach ($config->services_to_check as $service) {
            if (isset($model->services_check[$service]) && $model->services_check[$service] == true) {
                event (new ServiceUp($model->hostname, $service));
            }
            else {
                event (new ServiceDown($model->hostname, $service));
            }
        }
    }
}
