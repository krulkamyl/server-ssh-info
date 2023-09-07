<?php

namespace App\Observers;

use App\Events\ServerHistoryCreated;
use App\Models\ServerHistory;

class ServerHistoryObserver
{
    public function created(ServerHistory $serverHistory): void
    {
        event(new ServerHistoryCreated($serverHistory));
    }

}
