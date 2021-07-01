<?php

namespace App\Modules\Post\Providers;

use Dptsi\Modular\Facade\Messaging;
use Illuminate\Support\ServiceProvider;

class MessagingServiceProvider extends ServiceProvider
{
    protected string $module_name = 'post';

    public function register()
    {
    }

    public function boot()
    {
        Messaging::setChannel('post');
//        Messaging::listenTo();
    }
}