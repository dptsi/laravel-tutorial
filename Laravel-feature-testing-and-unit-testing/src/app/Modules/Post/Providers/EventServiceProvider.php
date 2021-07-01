<?php

namespace App\Modules\Post\Providers;

use Dptsi\Modular\Facade\Module;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [

    ];

    public function shouldDiscoverEvents()
    {
        return true;
    }

    protected function discoverEventsWithin()
    {
        return [
            Module::path('Post', 'Core/Application/EventListeners'),
        ];
    }
}