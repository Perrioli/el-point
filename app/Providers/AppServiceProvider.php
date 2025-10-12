<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('is-admin', function (User $user) {
            return $user->rol === 'Administrador';
        });

        Gate::define('is-cajero', function (User $user) {
            return $user->rol === 'Cajero';
        });

        Gate::define('is-empleado', function (User $user) {
            return $user->rol === 'Empleado';
        });

        Gate::define('open-register', function (User $user) {
            return in_array($user->rol, ['Administrador', 'Cajero']);
        });
    }
}
