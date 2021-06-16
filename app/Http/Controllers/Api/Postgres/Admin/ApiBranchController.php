<?php

namespace App\Http\Controllers\Api\Postgres\Admin;

use App\Elibs\eFunction;
use App\Http\Requests\Api\Postgres\Admin\Branch\AllBranchRequest;
use App\Http\Requests\Api\Postgres\Admin\Branch\CreateBranchRequest;
use App\Http\Requests\Api\Postgres\Admin\Branch\DeleteBranchRequest;
use App\Http\Requests\Api\Postgres\Admin\Branch\DetailBranchRequest;
use App\Http\Requests\Api\Postgres\Admin\Branch\UpdateBranchRequest;
use App\Http\Requests\Api\PaginationRequest;
use App\Models\Postgres\Admin\Branch;
use App\Repositories\Postgres\Admin\BranchBrandRepositoryInterface;
use App\Repositories\Postgres\Admin\BranchRepositoryInterface;
use App\Repositories\Postgres\Admin\BranchSubBrandRepositoryInterface;
use App\Repositories\Postgres\Admin\BrandRepositoryInterface;
use App\Repositories\Postgres\Admin\DeviceRepositoryInterface;
use App\Repositories\Postgres\Admin\StoreBrandRepositoryInterface;
use App\Repositories\Postgres\Admin\StoreSubBrandRepositoryInterface;
use App\Repositories\Postgres\Admin\SubBrandRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Elibs\eResponse;
use App\Repositories\Postgres\Store\StoreAccountRepositoryInterface;
use Illuminate\Support\Str;
use  DB;

class ApiBranchController extends Controller
{
    protected $branchRepository;
    protected $brandRepository;
    protected $deviceRepository;
    protected $subBrandRepository;
    protected $branchBrandRepository;
    protected $branchSubBrandRepository;
    protected $storeBrandRepository;
    protected $storeSubBrandRepository;
    protected $storeAccountRepository;

    public function __construct(
        BranchRepositoryInterface $branchRepository,
        BrandRepositoryInterface $brandRepository,
        SubBrandRepositoryInterface $subBrandRepository,
        BranchBrandRepositoryInterface $branchBrandRepository,
        BranchSubBrandRepositoryInterface $branchSubBrandRepository,
        StoreBrandRepositoryInterface $storeBrandRepository,
        StoreSubBrandRepositoryInterface $storeSubBrandRepository,
        DeviceRepositoryInterface $deviceRepository,
        StoreAccountRepositoryInterface $storeAccountRepository
    )
    {
        $this->branchRepository = $branchRepository;
        $this->brandRepository = $brandRepository;
        $this->subBrandRepository = $subBrandRepository;
        $this->branchBrandRepository = $branchBrandRepository;
        $this->branchSubBrandRepository = $branchSubBrandRepository;
        $this->storeBrandRepository = $storeBrandRepository;
        $this->storeSubBrandRepository = $storeSubBrandRepository;
        $this->deviceRepository = $deviceRepository;
        $this->storeAccountRepository = $storeAccountRepository;
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

            $branches = $this->branchRepository->getListBranchByFilter($limit, $filter);
            $branches = eFunction::mergeSubBrandToBrand($branches);

            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $branches);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function create(CreateBranchRequest $request)
    {
        $data = $request->only(
            [
                'name',
                'slug',
                'contact',
                'phone_number',
                'district_id',
                'province_id',
                'address',
                'store_id',
                'rank_id',
            ]
        );

        try{
            DB::beginTransaction();

            $accountInfo = $request->get('accountInfo');
            eFunction::FillUp($accountInfo, $data);

            $branch = $this->branchRepository->create($data);
            if (!empty($branch) && $request->has('brands')){
                $brands = $request->get('brands');
                $this->insertSubBrandAndBrandToBranch($brands, $accountInfo, $branch->id, (int)$data['store_id']);
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-create-success'), []);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function detail(DetailBranchRequest $request)
    {
        $id = $request->get('id');
        $accountInfo = $request->get('accountInfo');

        $branch = $this->branchRepository->getOneArrayBranchWithBrandByFilter(['id' => $id, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
        if (!empty($branch)){
            $branch = eFunction::mergeSubBrandToBrandInBranch($branch);

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $branch);
        }

        return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
    }

    public function update(UpdateBranchRequest $request)
    {
        $data = $request->only(
            [
                'name',
                'slug',
                'contact',
                'phone_number',
                'district_id',
                'province_id',
                'address',
                'store_id',
                'rank_id',
            ]
        );

        $id = $request->get('id');
        try{
            DB::beginTransaction();

            $accountInfo = $request->get('accountInfo');
            $branch = $this->branchRepository->getOneObjectBranchByFilter(['id' => $id, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
            if (!empty($branch)){
                eFunction::FillUp($accountInfo, $data);
                $newBranch = $this->branchRepository->update($branch, $data);
                if (!empty($newBranch) && $request->has('brands')){
                    $brands = $request->get('brands');
                    $this->insertSubBrandAndBrandToBranch($brands, $accountInfo, $branch->id, (int)$data['store_id']);
                }
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-update-success'), []);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function delete(DeleteBranchRequest $request)
    {
        try{

            DB::beginTransaction();
            $ids = eFunction::arrayInteger($request->get('ids'));
            $accountInfo = $request->get('accountInfo');
            if (!empty($ids)){
                $devices = $this->deviceRepository->getAllDeviceByFilter(['branch_id' => $ids, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
                if (!empty($devices)){
                    return eResponse::response(STATUS_API_FALSE, __('notification.system.exists-devices'));
                }

                $this->branchRepository->deleteAllBranchByFilter(['id' => $ids, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
            }
            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-delete-success'), []);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    //Function của bên store
    public function allBranchesOfStore(AllBranchRequest $request)
    {
        $filter = [];
        $filterStoreAccount = [];
        $this->makeFilter($request, $filterStoreAccount);
        $filterStoreAccount['deleted_at'] = true;

        $allStoreAccounts = $this->storeAccountRepository->getAllStoreAccountWithOnlyBranchIdByFilter($filterStoreAccount);
        if (!empty($allStoreAccounts)){
            $filter['id_not_in'] = collect($allStoreAccounts)->pluck('branch_id')->filter()->unique()->values()->toArray();
        }

        $filter['deleted_at'] = true;
        $this->makeFilter($request, $filter);
        $branches = $this->branchRepository->getAllBranchForStoreByFilter($filter);
        return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $branches);

    }

    private function insertSubBrandAndBrandToBranch($brands, $accountInfo, $branchId, $storeId)
    {
        $brandIds = collect($brands)->pluck('idBrand')->filter()->unique()->values()->toArray();
        if (!empty($brandIds)){
            $arrInsertBrandToBranch = $this->getArrayBrandAddToBranch($brandIds, $branchId, $accountInfo, $storeId);
            if (!empty($arrInsertBrandToBranch)){
                $this->branchBrandRepository->deleteAllBranchBrandByFilter(['branch_id' => $branchId, 'project_id' => $accountInfo['project_id']]);
                $this->branchBrandRepository->createMultiRecord($arrInsertBrandToBranch);
            }
        }

        $subBrandIds = collect($brands)->pluck('idsSubBrand')->collapse()->filter()->unique()->values()->toArray();
        if (!empty($subBrandIds) && !empty($brandIds)){
            $arrInsertSubBrandToBranch = $this->getArraySubBrandAddToBranch($subBrandIds, $branchId, $accountInfo, $storeId);
            if (!empty($arrInsertSubBrandToBranch)){
                $this->branchSubBrandRepository->deleteAllBranchSubBrandByFilter(['branch_id' => $branchId, 'project_id' => $accountInfo['project_id']]);
                $this->branchSubBrandRepository->createMultiRecord($arrInsertSubBrandToBranch);
            }
        }

        return true;
    }

    private function getArrayBrandAddToBranch($brandIds, $branchId, $accountInfo, $storeId)
    {
        $arrBrandBranches = [];
        //Check trong danh sách nhãn
        $brands = $this->brandRepository->countAllBrandByFilter(['id' => $brandIds, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);

        //Check trong danh sách nhãn đã chọn trong cửa hàng
        $storeBrands = $this->storeBrandRepository->countAllStoreBrandByFilter(['store_id' => $storeId, 'brand_id' => $brandIds, 'project_id' => $accountInfo['project_id']]);
        if (empty($storeBrands) || empty($brands) || $storeBrands !== count($brandIds) || $brands !== count($brandIds)) {
            return $arrBrandBranches;
        }

        foreach ($brandIds as $id){
            $arrBrandBranches[] = [
                'brand_id' => (int)$id,
                'branch_id' => $branchId,
                'account_id' => $accountInfo['id'],
                'project_id' => $accountInfo['project_id'],
                'created_at' => eFunction::getDateTimeNow(),
                'updated_at' => eFunction::getDateTimeNow(),
            ];
        }

        return $arrBrandBranches;
    }

    private function getArraySubBrandAddToBranch($subBrandIds, $branchId, $accountInfo, $storeId)
    {
        $arrSubBrandBranches = [];
        //Check trong nhãn con
        $subBrands = $this->subBrandRepository->countAllSubBrandByFilter(['id' => $subBrandIds, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);

        //Check trong nhãn con của cửa hàng
        $storeSubBrands = $this->storeSubBrandRepository->countAllStoreSubBrandByFilter(['store_id' => $storeId, 'sub_brand_id' => $subBrandIds, 'project_id' => $accountInfo['project_id']]);
        if (empty($storeSubBrands) || empty($subBrands) || $subBrands !== count($subBrandIds) || $storeSubBrands !== count($subBrandIds)) {
            return $arrSubBrandBranches;
        }

        foreach ($subBrandIds as $id){
            $arrSubBrandBranches[] = [
                'sub_brand_id' => (int)$id,
                'branch_id' => $branchId,
                'account_id' => $accountInfo['id'],
                'project_id' => $accountInfo['project_id'],
                'created_at' => eFunction::getDateTimeNow(),
                'updated_at' => eFunction::getDateTimeNow(),
            ];
        }

        return $arrSubBrandBranches;
    }

    private function makeFilter($request, &$filter)
    {
        if ($request->has('key_word')) {
            $filter['key_word'] = $request->get('key_word');
        }

        if ($request->has('store_id')) {
            $filter['store_id'] = $request->get('store_id');
        }

        if ($request->has('brand_id')) {
            $filter['brand_id'] = $request->get('brand_id');
        }

        if ($request->has('sub_brand_id')) {
            $filter['sub_brand_id'] = $request->get('sub_brand_id');
        }

        if ($request->has('province_id')) {
            $filter['province_id'] = $request->get('province_id');
        }

        if ($request->has('district_id')) {
            $filter['district_id'] = $request->get('district_id');
        }

        if ($request->has('store_account_id')) {
            $filter['id_not_in'] = $request->get('store_account_id');
        }
    }
}
