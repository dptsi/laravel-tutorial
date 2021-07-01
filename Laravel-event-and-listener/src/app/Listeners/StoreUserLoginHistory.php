<?php

namespace App\Listeners;

use App\Events\LoginHistory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

//class StoreUserLoginHistory 
class StoreUserLoginHistory implements ShouldQueue
{
   
    //public $queue = 'listeners';
    //public $delay = 10;

   
    public function __construct()
    {
        //
    }

  
    public function handle(LoginHistory $event)
    {
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

    /*public function shouldQueue(LoginHistory $event)
    {
        return true;
    }*/

    /* public function viaQueue()
     {
         return 'listeners';
     }
    */
    /*
    public function failed(LoginHistory $event, $exception)
    {
        
    }
    */

    /*
    public function retryUntil()
    {
        return now()->addSeconds(5);
    }*/
}
