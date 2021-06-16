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
            \App\Services\Postgres\Admin\AccountServiceInterface::class,
            \App\Services\Postgres\Admin\Production\AccountService::class
        );

        $this->app->singleton(
            \App\Services\AuthenticationServiceInterface::class,
            \App\Services\Production\AuthenticationService::class
        );

        $this->app->singleton(
            \App\Services\BaseServiceInterface::class,
            \App\Services\Production\BaseService::class
        );

        $this->app->singleton(
            \App\Services\Postgres\Admin\FileUploadServiceInterface::class,
            \App\Services\Postgres\Admin\Production\FileUploadService::class
        );

        $this->app->singleton(
            \App\Services\Postgres\Admin\ImageServiceInterface::class,
            \App\Services\Postgres\Admin\Production\ImageService::class
        );

        $this->app->singleton(
            \App\Services\Postgres\Store\StoreAccountServiceInterface::class,
            \App\Services\Postgres\Store\Production\StoreAccountService::class
        );

        $this->app->singleton(
            \App\Services\Postgres\Store\CollectionServiceInterface::class,
            \App\Services\Postgres\Store\Production\CollectionService::class
        );

        $this->app->singleton(
            \App\Services\Postgres\Store\FileUploadCollectionServiceInterface::class,
            \App\Services\Postgres\Store\Production\FileUploadCollectionService::class
        );

        $this->app->singleton(
            \App\Services\CommonServiceInterface::class,
            \App\Services\Production\CommonService::class
        );

        $this->app->singleton(
            \App\Services\ExcelServiceInterface::class,
            \App\Services\Production\ExcelService::class
        );

        /* NEW BINDING */
    }
}
