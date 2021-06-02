<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Post' => 'App\Policies\PostPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Gate::guessPolicyNamesUsing(function ($modelClass) {
        //     return 'App\\Policies\\' . class_basename($modelClass) . 'Policy';
        // });

        // before
        // Gate::before(function ($user, $ability) {
        //     if ($user->isAdmin) {
        //         return true;
        //     }
        // });

        Gate::define('go-to-private', function ($user) {
            return($user->isAdmin);
        });

        Gate::define('update-private', function ($user) {
           return true;
        });

        // Gate responses
        Gate::define('go-to-response', function (User $user) {
            return $user->isAdmin
                        ? Response::allow()
                        : Response::deny('You must be an administrator.');
        });

        // after, akan dijalankan terakhir
        // Gate::after(function ($user, $ability) {
        //     if ($user->isAdmin) {
        //         return true;
        //     }
        // });
    }
}
