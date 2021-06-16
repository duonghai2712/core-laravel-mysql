<?php

namespace App\Http\Controllers\Api\Postgres\Admin;

use App\Elibs\eFunction;
use App\Events\SendEmailRegistrationAccountEvent;
use App\Http\Requests\Api\Postgres\Admin\Store\GetAllBrandRequest;
use App\Http\Requests\Api\PaginationRequest;
use App\Http\Requests\Api\Postgres\Admin\Store\CreateStoreRequest;
use App\Http\Requests\Api\Postgres\Admin\Store\DeleteStoreRequest;
use App\Http\Requests\Api\Postgres\Admin\Store\DetailStoreRequest;
use App\Http\Requests\Api\Postgres\Admin\Store\UpdateStoreRequest;
use App\Http\Requests\Api\Request;
use App\Models\Postgres\Admin\Account;
use App\Models\Postgres\Admin\Image;
use App\Models\Postgres\Admin\Store;
use App\Http\Controllers\Controller;
use App\Elibs\eResponse;
use App\Models\Postgres\Store\StoreAccount;
use App\Repositories\Postgres\Admin\AccountRepositoryInterface;
use App\Repositories\Postgres\Admin\BranchBrandRepositoryInterface;
use App\Repositories\Postgres\Admin\BranchRepositoryInterface;
use App\Repositories\Postgres\Admin\BranchSubBrandRepositoryInterface;
use App\Repositories\Postgres\Admin\BrandRepositoryInterface;
use App\Repositories\Postgres\Admin\DeviceRepositoryInterface;
use App\Repositories\Postgres\Admin\StoreBrandRepositoryInterface;
use App\Repositories\Postgres\Admin\StoreRepositoryInterface;
use App\Repositories\Postgres\Admin\StoreSubBrandRepositoryInterface;
use App\Repositories\Postgres\Admin\SubBrandRepositoryInterface;
use App\Repositories\Postgres\Store\StoreAccountRepositoryInterface;
use App\Services\Postgres\Store\FileUploadCollectionServiceInterface;
use App\Services\Postgres\Store\StoreAccountServiceInterface;
use  DB;
use phpDocumentor\Reflection\Types\Collection;

class ApiStoreController extends Controller
{
    protected $accountRepository;
    protected $storeRepository;
    protected $fileUploadCollectionService;
    protected $brandRepository;
    protected $storeAccountService;
    protected $storeAccountRepository;
    protected $subBrandRepository;
    protected $storeBrandRepository;
    protected $storeSubBrandRepository;
    protected $branchRepository;
    protected $deviceRepository;
    protected $branchBrandRepository;
    protected $branchSubBrandRepository;

    public function __construct(
        AccountRepositoryInterface $accountRepository,
        StoreRepositoryInterface $storeRepository,
        FileUploadCollectionServiceInterface $fileUploadCollectionService,
        BrandRepositoryInterface $brandRepository,
        SubBrandRepositoryInterface $subBrandRepository,
        StoreAccountServiceInterface $storeAccountService,
        StoreBrandRepositoryInterface $storeBrandRepository,
        StoreAccountRepositoryInterface $storeAccountRepository,
        BranchRepositoryInterface $branchRepository,
        DeviceRepositoryInterface $deviceRepository,
        BranchBrandRepositoryInterface $branchBrandRepository,
        BranchSubBrandRepositoryInterface $branchSubBrandRepository,
        StoreSubBrandRepositoryInterface $storeSubBrandRepository
    )
    {
        $this->accountRepository = $accountRepository;
        $this->storeRepository = $storeRepository;
        $this->fileUploadCollectionService = $fileUploadCollectionService;
        $this->brandRepository = $brandRepository;
        $this->subBrandRepository = $subBrandRepository;
        $this->storeBrandRepository = $storeBrandRepository;
        $this->storeAccountService = $storeAccountService;
        $this->deviceRepository = $deviceRepository;
        $this->storeSubBrandRepository = $storeSubBrandRepository;
        $this->storeAccountRepository = $storeAccountRepository;
        $this->branchBrandRepository = $branchBrandRepository;
        $this->branchSubBrandRepository = $branchSubBrandRepository;
        $this->branchRepository = $branchRepository;
    }

    public function index(PaginationRequest $request)
    {
        try{
            $filter = [];
            $limit = $request->limit();
            $accountInfo = $request->get('accountInfo');
            $this->makeFilter($request, $filter);
            eFunction::FillUp($accountInfo, $filter);
            $filter['deleted_at'] = true;

            $stores = $this->storeRepository->getListStoreByFilter($limit, $filter);
            $stores = eFunction::mergeSubBrandToBrand($stores);

            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $stores);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function create(CreateStoreRequest $request)
    {
        $data = $request->only([
            'name',
            'slug',
            'address',
            'district_id',
            'province_id',
        ]);

        $account = $request->only([
            'representative',
            'email',
            'phone_number',
            'is_active',
            'username',
            'password',
            'language',
        ]);

        $role = $request->get('role');

        try {
            DB::beginTransaction();

            $accountInfo = $request->get('accountInfo');
            eFunction::FillUp($accountInfo, $data);
            $store = $this->storeRepository->create($data);
            if (!empty($store)){
                if ($request->has('brands')){
                    $brands = $request->get('brands');
                    $this->insertSubBrandAndBrandToStore($brands, $accountInfo, $store->id, Store::CREATE);
                }

                $account['store_id'] = $store->id;
                $account['role'] = Store::ROLE[$role]['key'];
                $account['username'] = strtolower($account['username']);
                $account['email'] = strtolower($account['email']);
                $account['language'] = !empty($account['language']) ? $account['language'] : Account::LANGUAGE['vi']['key'];
                eFunction::FillUp($accountInfo, $account);

                $storeAccount = $this->storeAccountService->registerStoreAccount($account);

                if ($request->hasFile('profile_store_image')) {
                    $file = $request->file('profile_store_image');
                    $image = $this->fileUploadCollectionService->upload('store_profile_image', $file, [
                        'id' => $storeAccount->id,
                        'store_id' => $store->id,
                        'project_id' => $accountInfo['project_id']
                    ], Image::AVATAR);

                    if (!empty($image)){
                        $this->storeAccountRepository->update($storeAccount, ['profile_collection_id' => $image['id']]);
                    }
                }
            }

            DB::commit();

            event(new SendEmailRegistrationAccountEvent([
                'account' => $account,
                'type' => StoreAccount::STORE
            ]));

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-create-success'));
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'));
        }
    }

    public function detail(DetailStoreRequest $request)
    {
        $id = $request->get('id');
        $accountInfo = $request->get('accountInfo');

        $store = $this->storeRepository->getOneArrayStoreWithAccountByFilter(['id' => $id, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);

        if (!empty($store)){
            if (!empty($store['brands']) && !empty($store['sub_brands'])){
                foreach ($store['brands'] as $k => $brand){
                    $subBrands = [];
                    foreach ($store['sub_brands'] as $sub_brand){
                        if (!empty($sub_brand['brand_id']) && !empty($brand['id']) && (int)$sub_brand['brand_id'] === $brand['id']){
                            $subBrands[] = $sub_brand;
                        }
                    }
                    if (!empty($subBrands)){
                        $store['brands'][$k]['sub_brands'] = $subBrands;
                    }
                }

                unset($store['sub_brands']);
            }

            if (!empty($store['account']) && !empty($store['account']['source'])){
                $store['avatar'] = asset($store['account']['source']);
            }else{
                $store['avatar'] = null;
            }

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $store);
        }

        return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
    }

    public function update(UpdateStoreRequest $request)
    {
        $data = $request->only([
            'name',
            'slug',
            'address',
            'district_id',
            'province_id',
        ]);

        $account = $request->only([
            'representative',
            'email',
            'phone_number',
            'username',
            'language',
        ]);

        if ($request->has('password')) {
            $account['password'] = $request->get('password');
        }


        $id = $request->get('id');
        $role = $request->get('role');

        try {
            DB::beginTransaction();
            $accountInfo = $request->get('accountInfo');
            $store = $this->storeRepository->getOneObjectStoreByFilter(['id' => $id, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
            if (empty($store)){
                return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
            }

            $newStore = $this->storeRepository->update($store, $data);
            if (!empty($newStore)){
                if ($request->has('brands')){
                    $brands = $request->get('brands');
                    $this->insertSubBrandAndBrandToStore($brands, $accountInfo, $newStore->id, Store::UPDATE);
                }

                $account['role'] = Store::ROLE[$role]['key'];
                $account['language'] = !empty($account['language']) ? $account['language'] : Account::LANGUAGE['vi']['key'];
                eFunction::FillUp($accountInfo, $account);

                $storeAccount = $this->storeAccountRepository->getOneObjectStoreAccountByFilter(['store_id' => $id, 'role' => StoreAccount::ADMIN, 'group_store_account_id' => null, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
                if (empty($storeAccount)){
                    return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
                }

                if ($request->hasFile('profile_store_image')) {
                    $file = $request->file('profile_store_image');
                    $image = $this->fileUploadCollectionService->upload('store_profile_image', $file, [
                        'id' => $storeAccount->id,
                        'store_id' => $newStore->id,
                        'project_id' => $accountInfo['project_id']
                    ], Image::AVATAR);

                    if (!empty($image)){
                        $account['profile_collection_id'] = $image['id'];
                    }
                }

                $this->storeAccountRepository->update($storeAccount, $account);

            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-update-success'));
        } catch (\Exception $e) {
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'));
        }
    }

    public function delete(DeleteStoreRequest $request)
    {

        try{
            DB::beginTransaction();
            $accountInfo = $request->get('accountInfo');
            $ids = eFunction::arrayInteger($request->get('ids'));
            if (!empty($ids)){
                $devices = $this->deviceRepository->getAllDeviceByFilter(['store_id' => $ids, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
                if (!empty($devices)){
                    return eResponse::response(STATUS_API_FALSE, __('notification.system.exists-devices'));
                }

                $this->storeRepository->deleteAllStoreByFilter(['id' => $ids, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
                $this->storeAccountRepository->deleteAllStoreAccountByFilter(['store_id' => $ids, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
                $this->branchRepository->deleteAllBranchByFilter(['store_id' => $ids, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-delete-success'), []);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function changeStatusStore(GetAllBrandRequest $request)
    {
        $isActive = $request->get('is_active');
        $id = $request->get('id');

        try {

            DB::beginTransaction();
            $accountInfo = $request->get('accountInfo');
            $store = $this->storeRepository->getOneObjectStoreByFilter(['id' => $id, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
            if (empty($store) || !in_array($isActive, Store::ARR_IS_ACTIVE)){
                return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
            }

            $this->storeRepository->update($store, ['is_active' => (int)$isActive]);
            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-update-success'));
        } catch (\Exception $e) {
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'));
        }
    }

    public function getAllBrandAndSubBrandStoreSelected(Request $request)
    {
        try{
            $filter = [];
            $this->makeFilter($request, $filter);
            $accountInfo = $request->get('accountInfo');
            eFunction::FillUp($accountInfo, $filter);
            $filter['deleted_at'] = true;


            $store = $this->storeRepository->getOneArrayStoreWithBrandAndSubBrandByFilter($filter);
            if (!empty($store['brands']) && !empty($store['sub_brands'])){
                $brands = $store['brands'];
                $subBrands = $store['sub_brands'];
                foreach ($brands as $k => $brand){
                    foreach ($subBrands as $subBrand){
                        if (!empty($subBrand['brand_id']) && !empty($brand['id']) && (int)$subBrand['brand_id'] === $brand['id']){
                            $brands[$k]['sub_brands'][] = $subBrand;
                        }
                    }
                }

                return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $brands);
            }

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), []);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    private function insertSubBrandAndBrandToStore($brands, $accountInfo, $storeId, $type)
    {
        $brandIds = collect($brands)->pluck('idBrand')->filter()->unique()->values()->toArray();
        if (!empty($brandIds)){
            $arrInsertBrandToStore = $this->getArrayBrandAddToStore($brandIds, $accountInfo, $storeId);
            if (!empty($arrInsertBrandToStore)){
                if ($type === Store::UPDATE){
                    $arrIdsBrandDelete = $this->getArrayIdsBrandDelete($brandIds, $accountInfo, $storeId);
                    if (!empty($arrIdsBrandDelete)){
                        $this->branchBrandRepository->deleteAllBranchBrandByFilter(['brand_id' => $arrIdsBrandDelete]);
                    }
                }

                $this->storeBrandRepository->deleteAllStoreBrandByFilter(['store_id' => $storeId, 'project_id' => $accountInfo['project_id']]);
                $this->storeBrandRepository->createMultiRecord($arrInsertBrandToStore);
            }
        }

        $subBrandIds = collect($brands)->pluck('idsSubBrand')->collapse()->filter()->unique()->values()->toArray();
        if (!empty($subBrandIds) && !empty($brandIds)){
            $arrInsertSubBrandToStore = $this->getArraySubBrandAddToStore($subBrandIds, $accountInfo, $storeId);
            if (!empty($arrInsertSubBrandToStore)){
                if ($type === Store::UPDATE){
                    $arrIdsSubBrandDelete = $this->getArrayIdsSubBrandDelete($subBrandIds, $accountInfo, $storeId);
                    if (!empty($arrIdsSubBrandDelete)){
                        $this->branchSubBrandRepository->deleteAllBranchSubBrandByFilter(['sub_brand_id' => $arrIdsSubBrandDelete]);
                    }
                }

                $this->storeSubBrandRepository->deleteAllStoreSubBrandByFilter(['store_id' => $storeId, 'project_id' => $accountInfo['project_id']]);
                $this->storeSubBrandRepository->createMultiRecord($arrInsertSubBrandToStore);
            }
        }

        return true;
    }

    private function getArrayBrandAddToStore($brandIds, $accountInfo, $storeId)
    {
        $arrBrandBranches = [];
        //Check trong danh sách nhãn
        $brands = $this->brandRepository->countAllBrandByFilter(['id' => $brandIds, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
        if (empty($brands) || $brands !== count($brandIds)) {
            return $arrBrandBranches;
        }

        foreach ($brandIds as $id){
            $arrBrandBranches[] = [
                'brand_id' => (int)$id,
                'store_id' => $storeId,
                'account_id' => $accountInfo['id'],
                'project_id' => $accountInfo['project_id'],
                'created_at' => eFunction::getDateTimeNow(),
                'updated_at' => eFunction::getDateTimeNow(),
            ];
        }

        return $arrBrandBranches;
    }

    private function getArrayIdsBrandDelete($brandIds, $accountInfo, $storeId)
    {
        $deleteBrandIds = [];
        $storeBrand = $this->storeBrandRepository->getAllStoreBrandByFilter(['store_id' => $storeId, 'project_id' => $accountInfo['project_id']]);
        if (!empty($storeBrand)) {
            $AllBrandIds = collect($storeBrand)->pluck('brand_id')->filter()->unique()->values()->toArray();
            $deleteBrandIds = array_diff($AllBrandIds, $brandIds);
        }

        return $deleteBrandIds;
    }

    private function getArraySubBrandAddToStore($subBrandIds, $accountInfo, $storeId)
    {
        $arrSubBrandBranches = [];
        //Check trong nhãn con
        $subBrands = $this->subBrandRepository->countAllSubBrandByFilter(['id' => $subBrandIds, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
        if (empty($subBrands) || $subBrands !== count($subBrandIds)) {
            return $arrSubBrandBranches;
        }

        foreach ($subBrandIds as $id){
            $arrSubBrandBranches[] = [
                'sub_brand_id' => (int)$id,
                'store_id' => $storeId,
                'account_id' => $accountInfo['id'],
                'project_id' => $accountInfo['project_id'],
                'created_at' => eFunction::getDateTimeNow(),
                'updated_at' => eFunction::getDateTimeNow(),
            ];
        }

        return $arrSubBrandBranches;
    }

    private function getArrayIdsSubBrandDelete($subBrandIds, $accountInfo, $storeId)
    {
        $deleteSubBrandIds = [];
        $storeSubBrand = $this->storeSubBrandRepository->getAllStoreSubBrandByFilter(['store_id' => $storeId, 'project_id' => $accountInfo['project_id']]);
        if (!empty($storeSubBrand)) {
            $AllSubBrandIds = collect($storeSubBrand)->pluck('sub_brand_id')->filter()->unique()->values()->toArray();
            $deleteSubBrandIds = array_diff($AllSubBrandIds, $subBrandIds);
        }

        return $deleteSubBrandIds;
    }

    private function makeFilter($request, &$filter)
    {
        if ($request->has('key_word')) {
            $filter['key_word'] = $request->get('key_word');
        }

        if ($request->has('store_id')) {
            $filter['id'] = $request->get('store_id');
        }

        if ($request->has('province_id')) {
            $filter['province_id'] = $request->get('province_id');
        }

        if ($request->has('district_id')) {
            $filter['district_id'] = $request->get('district_id');
        }

        if ($request->has('brand_id')) {
            $filter['brand_id'] = $request->get('brand_id');
        }

        if ($request->has('sub_brand_id')) {
            $filter['sub_brand_id'] = $request->get('sub_brand_id');
        }
    }
}
