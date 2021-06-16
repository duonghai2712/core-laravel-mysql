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

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\ImageRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\ImageRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\AccountRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\AccountRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\PasswordResettableRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\PasswordResettableRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\AccountPasswordResetRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\AccountPasswordResetRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\OauthClientRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\OauthClientRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\OauthAccessTokenRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\OauthAccessTokenRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\OauthRefreshTokenRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\OauthRefreshTokenRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\DeviceRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\DeviceRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\ProjectRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\ProjectRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\FileRepositoryInterface::class,
            \App\Repositories\Postgres\Eloquent\FileRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\StoreRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\StoreRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\BrandRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\BrandRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\BranchRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\BranchRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\RankRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\RankRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\SubBrandRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\SubBrandRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\BranchBrandRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\BranchBrandRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\ProvinceRepositoryInterface::class,
            \App\Repositories\Postgres\Eloquent\ProvinceRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\DistrictRepositoryInterface::class,
            \App\Repositories\Postgres\Eloquent\DistrictRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\IndustryRepositoryInterface::class,
            \App\Repositories\Postgres\Eloquent\IndustryRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\StoreBrandRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\StoreBrandRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\BranchBrandRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\BranchBrandRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\StoreSubBrandRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\StoreSubBrandRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\BranchSubBrandRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\BranchSubBrandRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Store\GroupStoreAccountRepositoryInterface::class,
            \App\Repositories\Postgres\Store\Eloquent\GroupStoreAccountRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Store\PermissionRepositoryInterface::class,
            \App\Repositories\Postgres\Store\Eloquent\PermissionRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Store\GroupStoreAccountPermissionRepositoryInterface::class,
            \App\Repositories\Postgres\Store\Eloquent\GroupStoreAccountPermissionRepository::class
        );
        $this->app->singleton(
            \App\Repositories\Postgres\Store\StoreAccountRepositoryInterface::class,
            \App\Repositories\Postgres\Store\Eloquent\StoreAccountRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Store\CollectionRepositoryInterface::class,
            \App\Repositories\Postgres\Store\Eloquent\CollectionRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\AdminDeviceImageRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\AdminDeviceImageRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Store\StoreDeviceCollectionRepositoryInterface::class,
            \App\Repositories\Postgres\Store\Eloquent\StoreDeviceCollectionRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\ResetPasswordAccountRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\ResetPasswordAccountRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Store\ResetPasswordAccountStoreRepositoryInterface::class,
            \App\Repositories\Postgres\Store\Eloquent\ResetPasswordAccountStoreRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\OwnerRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\OwnerRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Customer\CustomerAccountRepositoryInterface::class,
            \App\Repositories\Postgres\Customer\Eloquent\CustomerAccountRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Store\OrderRepositoryInterface::class,
            \App\Repositories\Postgres\Store\Eloquent\OrderRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Store\OrderDeviceRepositoryInterface::class,
            \App\Repositories\Postgres\Store\Eloquent\OrderDeviceRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Store\StoreCrossDeviceCollectionRepositoryInterface::class,
            \App\Repositories\Postgres\Store\Eloquent\StoreCrossDeviceCollectionRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Store\TimeFrameRepositoryInterface::class,
            \App\Repositories\Postgres\Store\Eloquent\TimeFrameRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\AdminDeviceStatisticRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\AdminDeviceStatisticRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Store\StoreDeviceStatisticRepositoryInterface::class,
            \App\Repositories\Postgres\Store\Eloquent\StoreDeviceStatisticRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Store\StoreCrossDeviceStatisticRepositoryInterface::class,
            \App\Repositories\Postgres\Store\Eloquent\StoreCrossDeviceStatisticRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\DeviceStatisticRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\DeviceStatisticRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Store\LogOperationRepositoryInterface::class,
            \App\Repositories\Postgres\Store\Eloquent\LogOperationRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Store\LogPointRepositoryInterface::class,
            \App\Repositories\Postgres\Store\Eloquent\LogPointRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Store\OrderStoreRepositoryInterface::class,
            \App\Repositories\Postgres\Store\Eloquent\OrderStoreRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Store\OrderBranchRepositoryInterface::class,
            \App\Repositories\Postgres\Store\Eloquent\OrderBranchRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Store\TimeFrameLogPointRepositoryInterface::class,
            \App\Repositories\Postgres\Store\Eloquent\TimeFrameLogPointRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Store\CollectionLoadingStatusRepositoryInterface::class,
            \App\Repositories\Postgres\Store\Eloquent\CollectionLoadingStatusRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\DeviceLoadingStatusRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\DeviceLoadingStatusRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Admin\ImageLoadingStatusRepositoryInterface::class,
            \App\Repositories\Postgres\Admin\Eloquent\ImageLoadingStatusRepository::class
        );

        $this->app->singleton(
            \App\Repositories\Postgres\Store\CollectionCrossLoadingStatusRepositoryInterface::class,
            \App\Repositories\Postgres\Store\Eloquent\CollectionCrossLoadingStatusRepository::class
        );

        /* NEW BINDING */
    }
}
