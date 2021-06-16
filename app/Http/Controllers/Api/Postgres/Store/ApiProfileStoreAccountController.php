<?php

namespace App\Http\Controllers\Api\Postgres\Store;

use App\Http\Requests\Api\Postgres\Store\Account\DetailStoreAccountRequest;
use App\Http\Requests\Api\Postgres\Store\Account\UpdateStoreAccountRequest;
use App\Models\Postgres\Admin\Store;
use App\Models\Postgres\Store\Collection;
use App\Repositories\Postgres\Store\StoreAccountRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Elibs\eResponse;
use App\Services\Postgres\Store\FileUploadCollectionServiceInterface;
use  DB;

class ApiProfileStoreAccountController extends Controller
{
    protected $storeAccountRepository;
    protected $fileUploadCollectionService;

    public function __construct(StoreAccountRepositoryInterface $storeAccountRepository, FileUploadCollectionServiceInterface $fileUploadCollectionService)
    {
        $this->storeAccountRepository = $storeAccountRepository;
        $this->fileUploadCollectionService = $fileUploadCollectionService;
    }
    public function index(DetailStoreAccountRequest $request)
    {
        $storeAccountInfo = $request->get('storeAccountInfo');
        $id = $request->get('id');
        $storeAccount = $this->storeAccountRepository->getOneObjectStoreAccountByFilter(['id' => $id, 'store_id' => $storeAccountInfo['store_id'], 'project_id' => (int)$storeAccountInfo['project_id'], 'is_active' => Store::IS_ACTIVE, 'deleted_at' => true]);
        if (empty($storeAccount)){
            return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
        }

        return eResponse::response(STATUS_API_SUCCESS, __('notification.system.successful-data-retrieval'), $storeAccount->toAPIArray());
    }

    public function update(UpdateStoreAccountRequest $request)
    {

        $data = $request->only([
            'name',
            'email',
            'phone_number'
        ]);

        if ($request->has('password')) {
            $data['password'] = $request->get('password');
        }

        $id = $request->get('id');
        $storeAccountInfo = $request->get('storeAccountInfo');
        try {

            DB::beginTransaction();
            $storeAccount = $this->storeAccountRepository->getOneObjectStoreAccountByFilter(['id' => $id, 'store_id' => $storeAccountInfo['store_id'], 'project_id' => (int)$storeAccountInfo['project_id'], 'is_active' => Store::IS_ACTIVE, 'deleted_at' => true]);
            if (empty($storeAccount)){
                return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
            }

            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $image = $this->fileUploadCollectionService->upload('store_profile_image', $file, $storeAccountInfo, Collection::AVATAR);

                if (!empty($image)){
                    $data['profile_collection_id'] = $image['id'];
                }
            }

            $newStoreAccount = $this->storeAccountRepository->update($storeAccount, $data);

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-update-success'), $newStoreAccount->toAPIArray());
        } catch (\Exception $e) {
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'));
        }
    }
}
