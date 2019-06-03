<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Login' => [
            'App\Listeners\LoginListener',
        ],
        'App\Events\OrderShipped' => [
            'App\Listeners\SendShipmentNotification',
        ],
        'App\Events\UserLogin' => [
            'App\Listeners\WriteLoginEventToLog',
        ],
        'App\Events\UserLogout' => [
            'App\Listeners\WriteLogoutEventToLog',
        ],
        'App\Events\DataOperation' => [
            'App\Listeners\WriteOperationEventToLog',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
