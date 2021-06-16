<?php
use App\Http\Controllers\Api\Postgres\Admin\ApiAccountController;
use App\Http\Controllers\Api\Postgres\Admin\ApiAuthAdminController;
use App\Http\Controllers\Api\Postgres\Admin\ApiBranchController;
use App\Http\Controllers\Api\Postgres\Admin\ApiBrandController;
use App\Http\Controllers\Api\Postgres\Admin\ApiCollectionController;
use App\Http\Controllers\Api\Postgres\Admin\ApiDeviceController;
use App\Http\Controllers\Api\Postgres\Admin\ApiOrderController;
use App\Http\Controllers\Api\Postgres\Admin\ApiRankController;
use App\Http\Controllers\Api\Postgres\Admin\ApiStoreController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'middleware' => ['api.allow_domain_admin']], function () {

    Route::post('sign-in', [ApiAuthAdminController::class, 'signIn']);
    Route::post('sign-up',  [ApiAuthAdminController::class, 'index']);
    Route::get('reset-password',  [ApiAuthAdminController::class, 'resetPassword']);
    Route::post('create-new-password',  [ApiAuthAdminController::class, 'createNewPassword']);
    Route::post('update-new-password',  [ApiAuthAdminController::class, 'updateNewPassword']);

    Route::group(['middleware' => ['api.account_admin_auth']], function () {

        Route::post('sign-out',  [ApiAuthAdminController::class, 'signOut']);

        //account route
        Route::group(['prefix' => 'account'],function (){
            Route::get('detail', [ApiAccountController::class, 'index']);
            Route::post('update', [ApiAccountController::class, 'update']);
        });

        //brands route
        Route::group(['prefix' => 'brand'],function (){
            Route::get('list', [ApiBrandController::class, 'index']);
            Route::post('create', [ApiBrandController::class, 'create']);
            Route::get('detail', [ApiBrandController::class, 'detail']);
            Route::post('update', [ApiBrandController::class, 'update']);
            Route::delete('delete', [ApiBrandController::class, 'delete']);
            Route::post('add-sub-brand', [ApiBrandController::class, 'addSubBrandToBrand']);
            Route::get('get-all-brand', [ApiBrandController::class, 'getAllBrand']);
        });

        //ranks route
        Route::group(['prefix' => 'rank'],function (){
            Route::get('list', [ApiRankController::class, 'index']);
            Route::post('create', [ApiRankController::class, 'create']);
            Route::get('detail', [ApiRankController::class, 'detail']);
            Route::post('update', [ApiRankController::class, 'update']);
            Route::delete('delete', [ApiRankController::class, 'delete']);
        });

        //stores route
        Route::group(['prefix' => 'store'],function (){
            Route::get('list', [ApiStoreController::class, 'index']);
            Route::post('create', [ApiStoreController::class, 'create']);
            Route::get('detail', [ApiStoreController::class, 'detail']);
            Route::post('update', [ApiStoreController::class, 'update']);
            Route::delete('delete', [ApiStoreController::class, 'delete']);
            Route::post('change-status-store', [ApiStoreController::class, 'changeStatusStore']);
            Route::get('get-all-brand-store-selected', [ApiStoreController::class, 'getAllBrandAndSubBrandStoreSelected']);
        });

        //branches route
        Route::group(['prefix' => 'branch'],function (){
            Route::get('list', [ApiBranchController::class, 'index']);
            Route::post('create', [ApiBranchController::class, 'create']);
            Route::get('detail', [ApiBranchController::class, 'detail']);
            Route::post('update', [ApiBranchController::class, 'update']);
            Route::delete('delete', [ApiBranchController::class, 'delete']);
        });

        //devices route
        Route::group(['prefix' => 'device'],function (){
            Route::get('list', [ApiDeviceController::class, 'index']);
            Route::post('create', [ApiDeviceController::class, 'create']);
            Route::get('detail', [ApiDeviceController::class, 'detail']);
            Route::post('update', [ApiDeviceController::class, 'update']);
            Route::delete('delete', [ApiDeviceController::class, 'delete']);
            Route::post('change-status-device', [ApiDeviceController::class, 'changeStatusDevice']);
            Route::post('add-collection', [ApiDeviceController::class, 'addCollection']);
            Route::get('detail-collection', [ApiDeviceController::class, 'detailCollection']);
            Route::post('update-collection', [ApiDeviceController::class, 'updateCollection']);
            Route::delete('delete-collection', [ApiDeviceController::class, 'deleteCollection']);
        });

        //Collection route
        Route::group(['prefix' => 'collection'],function (){
            Route::get('list-collection', [ApiCollectionController::class, 'index']);
            Route::get('list-media', [ApiCollectionController::class, 'listMedia']);
            Route::post('create-collection', [ApiCollectionController::class, 'createCollection']);
            Route::post('create-media', [ApiCollectionController::class, 'createMedia']);
            Route::delete('delete-collection', [ApiCollectionController::class, 'deleteCollection']);
            Route::delete('delete-media', [ApiCollectionController::class, 'deleteMedia']);
        });

        //Order route
        Route::group(['prefix' => 'order'],function (){
            Route::get('list', [ApiOrderController::class, 'index']);
            Route::get('list-store-detail-order', [ApiOrderController::class, 'listStoreInDetail']);
            Route::get('detail-order', [ApiOrderController::class, 'detail']);
            Route::get('get-store-and-branch-detail-order', [ApiOrderController::class, 'infoStoreAndBranchInOrder']);
            Route::get('list-branch-detail-order', [ApiOrderController::class, 'listBranchInDetail']);
            Route::get('list-collection-detail-order', [ApiOrderController::class, 'listCollectionInDetail']);
            Route::get('list-device-detail-order', [ApiOrderController::class, 'listDeviceInDetail']);
            Route::get('list-collection-detail-order-waiting', [ApiOrderController::class, 'listCollectionInDetailWaiting']);
            Route::post('accept-request', [ApiOrderController::class, 'acceptRequest']);
        });
    });
});
