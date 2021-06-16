<?php

use App\Http\Controllers\Api\Postgres\Admin\ApiDeviceController;
use App\Http\Controllers\Api\Postgres\Admin\ApiProvisionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1', 'namespace' => 'Api', 'middleware' => ['api.allow_domain_all']], function () {

    Route::get('get-all-provinces',  [ApiProvisionController::class, 'allProvinces']);
    Route::get('get-all-districts',  [ApiProvisionController::class, 'allDistricts']);
    Route::get('detail-province',  [ApiProvisionController::class, 'detailProvince']);
    Route::get('add-provinces-and-district',  [ApiProvisionController::class, 'addProvincesAndDistricts']);

    Route::get('get-data',  [ApiProvisionController::class, 'GetQueueRabbit']);

    Route::get('send-data',  [ApiProvisionController::class, 'SendQueueRabbit']);

    Route::post('send', [ApiDeviceController::class, 'sendMessageQueue']);
    Route::get('get', [ApiDeviceController::class, 'getMessageQueue']);

    require (__DIR__.'/Api/Admin.php');

    require (__DIR__.'/Api/Store.php');

    require (__DIR__.'/Api/App.php');
});
