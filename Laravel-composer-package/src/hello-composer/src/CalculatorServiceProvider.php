<?php

namespace maximuse\HelloWorld;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use maximuse\HelloWorld\Console\InstallHelloWorld;
use maximuse\HelloWorld\Providers\EventServiceProvider;

class CalculatorServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('calculator', function ($app) {
            return new Calculator();
        });

        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'helloworld');
        $this->app->register(EventServiceProvider::class);
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallHelloWorld::class,
            ]);

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('helloworld.php')
            ], 'config');
        }

        if (!class_exists('CreateHistoriesTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/create_histories_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_histories_table.php')
            ], 'migrations');
        }

        Route::group($this->routeConfiguration(), function() {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'helloworld');
    }

    protected function routeConfiguration()
    {
        return [
            'prefix' => config('helloworld.prefix'),
            'middleware' => config('helloworld.middleware')
        ];
    }
}
