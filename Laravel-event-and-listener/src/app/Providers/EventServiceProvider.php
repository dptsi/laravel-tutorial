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

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
       
        LoginHistory::class => [
            StoreUserLoginHistory::class,
        ]
        
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        /*
        Event::listen(
            LoginHistory::class,
            [StoreUserLoginHistory::class, 'handle']
        );
        */
        
        /*
        Event::listen(function (LoginHistory $event) {
            $current_timestamp = Carbon::now()->toDateTimeString();

            $userinfo = $event->user;

            $saveHistory = DB::table('login_history')->insert(
                [
                    'name' => $userinfo->name,
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
