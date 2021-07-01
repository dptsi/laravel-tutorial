<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\LoginHistory;
use App\Listeners\StoreUserLoginHistory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use function Illuminate\Events\queueable;
use Illuminate\Support\Facades\Redis;
use App\Listeners\UserEventSubscriber;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        //normal
        
        LoginHistory::class => [
            StoreUserLoginHistory::class,
        ]
        
        
    ];
    //subscriber
    /*
    protected $subscribe = [
        UserEventSubscriber::class,
    ];
    */

    public function boot()
    {
        
        /*
        Event::listen(
            LoginHistory::class,
            [StoreUserLoginHistory::class, 'handle']
        );
       */
        //manual
        /*
        Event::listen(function (LoginHistory $event) {
            $current_timestamp = Carbon::now()->toDateTimeString();

            $userinfo = $event->user;

            $saveHistory = DB::table('login_history')->insert(
                [
                    'name' => 'manual',
                    'email' => $userinfo->email,
                    'created_at' => $current_timestamp,
                    'updated_at' => $current_timestamp
                ]
            );
            return $saveHistory;
        });
       */

    }
}
