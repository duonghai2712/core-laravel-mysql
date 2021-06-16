<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryBindServiceProvider extends ServiceProvider {
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
            \App\Repositories\BaseRepositoryInterface::class,
            \App\Repositories\Eloquent\BaseRepository::class
        );

        $this->app->singleton(
            \App\Repositories\LogRepositoryInterface::class,
            \App\Repositories\Eloquent\LogRepository::class
        );

        $this->app->singleton(
            \App\Repositories\AuthenticationRepositoryInterface::class,
            \App\Repositories\Eloquent\AuthenticationRepository::class
        );


        $this->app->singleton(
            \App\Repositories\SingleKeyModelRepositoryInterface::class,
            \App\Repositories\Eloquent\SingleKeyModelRepository::class
        );

        /* NEW BINDING */
    }
}
