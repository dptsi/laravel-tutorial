<?php

namespace App\Listeners;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Events\LoginHistory;

class UserEventSubscriber
{
    /**
     * Handle user login events.
     */
    public function storeUserLogin($event) {
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
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen(
            LoginHistory::class,
            [UserEventSubscriber::class, 'storeUserLogin']
        );
    }
}