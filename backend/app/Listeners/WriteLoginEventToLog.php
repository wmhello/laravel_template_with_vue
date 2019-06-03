<?php

namespace App\Listeners;

use App\Events\UserLogin;
use App\Models\LogLogin;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class WriteLoginEventToLog
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
     * @param  UserLogin  $event
     * @return void
     */
    public function handle(UserLogin $event)
    {
        //
        $log = new LogLogin();
        $log->saveLoginLog();
    }
}
