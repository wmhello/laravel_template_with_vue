<?php

namespace App\Listeners;

use App\Events\UserLogout;
use App\Models\LogLogin;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class WriteLogoutEventToLog
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
     * @param  UserLogout  $event
     * @return void
     */
    public function handle(UserLogout $event)
    {
        //
        $log = new LogLogin();
        $log->saveLogoutLog($event->user);
    }
}
