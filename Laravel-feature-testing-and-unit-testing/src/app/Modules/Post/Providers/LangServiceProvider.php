<?php

namespace App\Modules\Post\Providers;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;

class LangServiceProvider extends ServiceProvider
{
    protected string $module_name = 'Post';
    protected string $lang_path = '../Presentation/resources/lang';

    public function boot()
    {
        Lang::addNamespace($this->module_name, __DIR__ . '/' . $this->lang_path);
    }
}