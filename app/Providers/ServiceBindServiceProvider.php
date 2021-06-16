<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ServiceBindServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     */
    public function boot() {
        //
    }

    /**
     * Register any application services.
     */
    public function register() {
        $this->app->singleton(
            \App\Services\AuthenticationServiceInterface::class,
            \App\Services\Production\AuthenticationService::class
        );

        $this->app->singleton(
            \App\Services\BaseServiceInterface::class,
            \App\Services\Production\BaseService::class
        );


        /* NEW BINDING */
    }
}
