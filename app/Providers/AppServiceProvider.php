<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        // Настройка супер админа согласно документации Spatie
        // https://spatie.be/docs/laravel-permission/v6/basic-usage/super-admin
        Gate::before(function ($user, $ability) {
            // Супер админ имеет все разрешения
            return $user->hasRole('super-admin') ? true : null;
        });
    }
}
