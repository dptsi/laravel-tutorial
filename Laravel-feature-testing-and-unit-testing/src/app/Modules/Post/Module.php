<?php

namespace App\Modules\Post;

use Dptsi\Modular\Base\BaseModule;

class Module extends BaseModule
{
    public function getProviders(): array
    {
        return [
            \App\Modules\Post\Providers\RouteServiceProvider::class,
            \App\Modules\Post\Providers\DatabaseServiceProvider::class,
            \App\Modules\Post\Providers\ViewServiceProvider::class,
            \App\Modules\Post\Providers\LangServiceProvider::class,
            \App\Modules\Post\Providers\BladeComponentServiceProvider::class,
            \App\Modules\Post\Providers\DependencyServiceProvider::class,
            \App\Modules\Post\Providers\EventServiceProvider::class,
            \App\Modules\Post\Providers\MessagingServiceProvider::class,
        ];
    }
}