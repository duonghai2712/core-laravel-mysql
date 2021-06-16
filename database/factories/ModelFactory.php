<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\Postgres\Admin\Image::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Admin\Account::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Log::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Admin\OauthAccessToken::class, function (Faker\Generator $faker) {
    return [
        'id' => '',
        'user_id' => '',
        'client_id' => '',
        'name' => '',
        'scopes' => '',
        'revoked' => '',
        'expires_at' => '',
    ];
});

$factory->define(App\Models\Postgres\Admin\OauthClient::class, function (Faker\Generator $faker) {
    return [
        'id' => '',
        'user_id' => '',
        'name' => '',
        'secret' => '',
        'provider' => '',
        'redirect' => '',
        'personal_access_client' => '',
        'password_client' => '',
        'revoked' => '',
    ];
});

$factory->define(App\Models\Postgres\Admin\OauthRefreshToken::class, function (Faker\Generator $faker) {
    return [
        'id' => '',
        'access_token_id' => '',
        'revoked' => '',
        'expires_at' => '',
    ];
});

$factory->define(App\Models\Postgres\Admin\Device::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Admin\Project::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\File::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Admin\Store::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Admin\Brand::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Admin\Branch::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Admin\Rank::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Admin\SubBrand::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Admin\BranchBrand::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Province::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\District::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Industry::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Admin\StoreBrand::class, function (Faker\Generator $faker) {
    return [
        'id' => '',
        'project_id' => '',
        'account_id' => '',
        'brand_id' => '',
        'store_id' => '',
        'sub_brand_id' => '',
    ];
});

$factory->define(App\Models\Postgres\Admin\StoreSubBrand::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Admin\BranchSubBrand::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Store\GroupStoreAccount::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Store\Permission::class, function (Faker\Generator $faker) {
    return [
    ];
});


$factory->define(App\Models\Postgres\Store\GroupStoreAccountPermission::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Store\StoreAccount::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Store\Collection::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Store\StoreDeviceCollection::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Admin\AdminDeviceImage::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Admin\ResetPasswordAccount::class, function (Faker\Generator $faker) {
    return [
        'id' => '',
        'email' => '',
        'token' => '',
    ];
});

$factory->define(App\Models\Postgres\Store\ResetPasswordAccountStore::class, function (Faker\Generator $faker) {
    return [
        'id' => '',
        'email' => '',
        'token' => '',
    ];
});

$factory->define(App\Models\Postgres\Admin\Owner::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Customer\CustomerAccount::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Store\Order::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Store\OrderDevice::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Store\StoreCrossDeviceCollection::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Store\TimeFrame::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Store\TimeFrameDetail::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Admin\AdminDeviceStatistic::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Store\StoreDeviceStatistic::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Store\StoreCrossDeviceStatistic::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Admin\DeviceStatistic::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Store\LogOperation::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Store\LogPoint::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Store\OrderStore::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Store\OrderBranch::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Store\TimeFrameLogPoint::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Store\CollectionLoadingStatus::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Admin\DeviceLoadingStatus::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Admin\ImageLoadingStatus::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Models\Postgres\Store\CollectionCrossLoadingStatus::class, function (Faker\Generator $faker) {
    return [
    ];
});

/* NEW MODEL FACTORY */
