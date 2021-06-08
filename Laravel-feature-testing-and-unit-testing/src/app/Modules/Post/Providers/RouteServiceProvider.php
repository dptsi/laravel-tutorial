<?php

namespace App\Modules\Post\Providers;

use App\Modules\Post\Presentation\Controllers\BaseController;
use Dptsi\Modular\Facade\Module;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected string $prefix = 'post';
    protected string $module_name = 'post';
    protected string $route_path = '../Presentation/routes';

    public function boot()
    {
        $this->routes(function () {
            if (Module::get($this->module_name)->isDefault()) {
                Route::middleware('web')->get('/', [BaseController::class, 'index']);
            }

            Route::prefix('api/' . $this->prefix)
                ->middleware('api')
                ->group(__DIR__ . '/' . $this->route_path . '/api.php');

            Route::middleware('web')
                ->prefix("{$this->prefix}")
                ->group(__DIR__ . '/' . $this->route_path . '/web.php');
        });
    }
}
