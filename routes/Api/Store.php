<?php

use App\Http\Controllers\Api\Postgres\Admin\ApiBranchController;
use App\Http\Controllers\Api\Postgres\Store\ApiCollectionController;
use App\Http\Controllers\Api\Postgres\Store\ApiDashboardStoreController;
use App\Http\Controllers\Api\Postgres\Store\ApiDeviceController;
use App\Http\Controllers\Api\Postgres\Store\ApiGroupStoreAccountController;
use App\Http\Controllers\Api\Postgres\Store\ApiInfoStoreController;
use App\Http\Controllers\Api\Postgres\Store\ApiOrderController;
use App\Http\Controllers\Api\Postgres\Store\ApiOrderCrossController;
use App\Http\Controllers\Api\Postgres\Store\ApiProfileStoreAccountController;
use App\Http\Controllers\Api\Postgres\Store\ApiAuthStoreController;
use App\Http\Controllers\Api\Postgres\Store\ApiStoreAccountController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'store', 'middleware' => ['api.allow_domain_store']], function () {

    Route::post('sign-in', [ApiAuthStoreController::class, 'index']); //->middleware("throttle:5,5")
    Route::get('reset-password',  [ApiAuthStoreController::class, 'resetPassword']);
    Route::post('create-new-password',  [ApiAuthStoreController::class, 'createNewPassword']);
    Route::post('update-new-password',  [ApiAuthStoreController::class, 'updateNewPassword']);

    Route::group(['middleware' => ['api.store_admin_auth']], function () {

        Route::post('sign-out', [ApiAuthStoreController::class, 'signOut']);

        //account route
        Route::group(['prefix' => 'dashboard'],function (){
            Route::get('/homepage', [ApiDashboardStoreController::class, 'index']);
            Route::get('media-playback-statistics', [ApiDashboardStoreController::class, 'mediaPlaybackStatistics']);
            Route::get('get-list-store-ads', [ApiDashboardStoreController::class, 'getListStorePlayAds']);
            Route::get('get-list-branch-ads', [ApiDashboardStoreController::class, 'getListBranchPlayAds']);
        });

        //account route
        Route::group(['prefix' => 'branch'],function (){
            Route::get('all-branches-store', [ApiBranchController::class, 'allBranchesOfStore']);
        });

        //account route
        Route::group(['prefix' => 'account'],function (){
            Route::get('detail', [ApiProfileStoreAccountController::class, 'index']);
            Route::post('update', [ApiProfileStoreAccountController::class, 'update']);
        });

        //Group store accounts route
        Route::group(['prefix' => 'group-store-account'],function (){
            Route::get('list', [ApiGroupStoreAccountController::class, 'index']);
            Route::post('create', [ApiGroupStoreAccountController::class, 'create']);
            Route::get('detail', [ApiGroupStoreAccountController::class, 'detail']);
            Route::post('update', [ApiGroupStoreAccountController::class, 'update']);
            Route::delete('delete', [ApiGroupStoreAccountController::class, 'delete']);

            Route::get('all-group-store-accounts', [ApiGroupStoreAccountController::class, 'allGroupStoreAccounts']);
        });

        //Store accounts route
        Route::group(['prefix' => 'store-account'],function (){
            Route::get('list', [ApiStoreAccountController::class, 'index']);
            Route::post('create', [ApiStoreAccountController::class, 'create']);
            Route::get('detail', [ApiStoreAccountController::class, 'detail']);
            Route::post('update', [ApiStoreAccountController::class, 'update']);
            Route::post('change-make-ads', [ApiStoreAccountController::class, 'changeMakeAds']);
            Route::delete('delete', [ApiStoreAccountController::class, 'delete']);
            Route::get('all-permission', [ApiStoreAccountController::class, 'allPermissions']);
        });

        //Collection route
        Route::group(['prefix' => 'collection'],function (){
            Route::get('list', [ApiCollectionController::class, 'index']);
            Route::post('create', [ApiCollectionController::class, 'create']);
            Route::delete('delete', [ApiCollectionController::class, 'delete']);
        });

        //Device route
        Route::group(['prefix' => 'device'],function (){
            Route::get('list', [ApiDeviceController::class, 'index']);
            Route::post('add-collection', [ApiDeviceController::class, 'addCollection']);
            Route::post('block-ads', [ApiDeviceController::class, 'blockAds']);
            Route::get('detail', [ApiDeviceController::class, 'detail']);
            Route::post('update-collection', [ApiDeviceController::class, 'updateCollection']);
            Route::get('statistic', [ApiDeviceController::class, 'statistic']);
            Route::delete('delete-collection', [ApiDeviceController::class, 'deleteCollection']);
        });

        //Device route
        Route::group(['prefix' => 'store-info'],function (){
            Route::get('list', [ApiInfoStoreController::class, 'index']);
            Route::get('detail-store', [ApiInfoStoreController::class, 'detailStore']);
            Route::post('update-store', [ApiInfoStoreController::class, 'updateStore']);
            Route::get('sidebar-info', [ApiInfoStoreController::class, 'sidebarInfo']);
            Route::get('detail-branch', [ApiInfoStoreController::class, 'detailBranch']);
            Route::post('update-branch', [ApiInfoStoreController::class, 'updateBranch']);
            Route::get('log-operation', [ApiInfoStoreController::class, 'logOperation']);
            Route::get('log-point', [ApiInfoStoreController::class, 'logPoint']);

        });

        //Order route
        Route::group(['prefix' => 'order'],function (){
            Route::get('list', [ApiOrderController::class, 'index']);
            Route::get('list-rank', [ApiOrderController::class, 'listRank']);
            Route::get('list-brand', [ApiOrderController::class, 'listBrand']);
            Route::get('list-store', [ApiOrderController::class, 'listStore']);
            Route::get('list-device', [ApiOrderController::class, 'listDevice']);
            Route::post('save-order', [ApiOrderController::class, 'saveOrder']);
            Route::get('list-store-detail-order', [ApiOrderController::class, 'listStoreInDetail']);
            Route::get('detail-order', [ApiOrderController::class, 'detail']);
            Route::get('info-branch-or-store', [ApiOrderController::class, 'infoBranchOrStore']);
            Route::get('get-store-and-branch-detail-order', [ApiOrderController::class, 'infoStoreAndBranchInOrder']);
            Route::get('list-branch-detail-order', [ApiOrderController::class, 'listBranchInDetail']);
            Route::get('list-collection-detail-order', [ApiOrderController::class, 'listCollectionInDetail']);
            Route::get('list-device-detail-order', [ApiOrderController::class, 'listDeviceInDetail']);
            Route::get('list-collection-order', [ApiOrderController::class, 'listCollectionOrder']);
            Route::post('update-collection-order', [ApiOrderController::class, 'updateCollectionOrder']);
        });

        //Cross Order route
        Route::group(['prefix' => 'cross-order'],function (){
            Route::get('list', [ApiOrderCrossController::class, 'index']);
            Route::get('get-store-and-branch-detail-cross-order', [ApiOrderCrossController::class, 'infoStoreAndBranchInCrossOrder']);
            Route::get('list-branch-detail-cross-order', [ApiOrderCrossController::class, 'listBranchInDetailCross']);
            Route::get('list-collection-detail-cross-order', [ApiOrderCrossController::class, 'listCollectionInDetailCross']);
            Route::get('list-device-detail-cross-order', [ApiOrderCrossController::class, 'listDeviceInDetailCross']);
        });

    });
});
