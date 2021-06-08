<?php

namespace App\Modules\Post\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    protected string $module_name = 'Post';
    protected string $view_path = '../Presentation/resources/views';

    public function boot()
    {
        View::addNamespace($this->module_name, __DIR__ . '/' . $this->view_path);
    }
}