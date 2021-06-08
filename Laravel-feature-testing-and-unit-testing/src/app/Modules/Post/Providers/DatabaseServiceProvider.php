<?php

namespace App\Modules\Post\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    protected string $module_name = 'post';

    public function register()
    {
    }

    public function boot()
    {
        Config::set(
            'database.connections.' . $this->module_name,
            config($this->module_name . '.database')
        );
    }
}