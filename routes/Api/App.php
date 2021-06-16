<?php

use App\Http\Controllers\Api\Postgres\App\ApiDeviceController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app', 'middleware' => ['api.allow_domain_store']], function () {

    Route::post('sign-in', [ApiDeviceController::class, 'index']);
    Route::group(['middleware' => ['api.app_auth']], function () {
        Route::get('download', [ApiDeviceController::class, 'download']);
        Route::get('test', [ApiDeviceController::class, 'demo']);
        Route::post('statistics', [ApiDeviceController::class, 'statistics']);
        Route::post('check-status-collection-download', [ApiDeviceController::class, 'checkStatusCollectionDownload']);
    });
});
