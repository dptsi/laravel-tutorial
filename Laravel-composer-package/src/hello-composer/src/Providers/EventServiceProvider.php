<?php

namespace maximuse\HelloWorld\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use maximuse\HelloWorld\Events\ShowHelloWasCalled;
use maximuse\HelloWorld\Listeners\UpdateName;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ShowHelloWasCalled::class => [
            UpdateName::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}