<?php

namespace App\Listeners;

use App\Events\DataOperation;
use App\Models\LogWork;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class WriteOperationEventToLog
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DataOperation  $event
     * @return void
     */
    public function handle(DataOperation $event)
    {
        //
         $log = new LogWork();
         $log->log($event->info);
    }
}
