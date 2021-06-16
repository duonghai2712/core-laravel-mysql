<?php

namespace App\Http\Controllers\Api\Postgres\Store;

use App\Elibs\eFunction;
use App\Http\Controllers\Controller;
use App\Elibs\eResponse;
use App\Http\Requests\Api\PaginationRequest;
use App\Http\Requests\Api\Postgres\Store\StoreInfo\DetailBranchRequest;
use App\Http\Requests\Api\Postgres\Store\StoreInfo\UpdateBranchRequest;
use App\Http\Requests\Api\Postgres\Store\StoreInfo\UpdateStoreRequest;
use App\Http\Requests\BaseRequest;
use App\Models\Postgres\Admin\Store;
use App\Models\Postgres\Store\LogPoint;
use App\Repositories\Postgres\Admin\BranchRepositoryInterface;
use App\Repositories\Postgres\Admin\DeviceRepositoryInterface;
use App\Repositories\Postgres\Admin\StoreRepositoryInterface;
use App\Repositories\Postgres\Store\LogOperationRepositoryInterface;
use App\Repositories\Postgres\Store\LogPointRepositoryInterface;
use Illuminate\Support\Str;
use  DB;

class ApiInfoStoreController extends Controller
{
    protected $logOperationRepository;
    protected $logPointRepository;
    protected $storeRepository;
    protected $deviceRepository;
    protected $branchRepository;

    public function __construct(
        StoreRepositoryInterface $storeRepository,
        LogOperationRepositoryInterface $logOperationRepository,
        LogPointRepositoryInterface $logPointRepository,
        DeviceRepositoryInterface $deviceRepository,
        BranchRepositoryInterface $branchRepository
    )
    {
        $this->logOperationRepository = $logOperationRepository;
        $this->logPointRepository = $logPointRepository;
        $this->branchRepository = $branchRepository;
        $this->deviceRepository = $deviceRepository;
        $this->storeRepository = $storeRepository;
    }

    public function index(PaginationRequest $request)
    {
        try{
            $filter = [];
            $limit = $request->limit();
            $storeAccountInfo = $request->get('storeAccountInfo');
            eFunction::FillUpStore($storeAccountInfo, $filter);
            $this->makeFilter($request, $filter);
            $filter['deleted_at'] = true;

            $devices = $this->branchRepository->getListBranchByFilter($limit, $filter);

            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $devices);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function detailStore(BaseRequest $request)
    {
        $storeAccountInfo = $request->get('storeAccountInfo');
        $store = $this->storeRepository->getOneArrayStoreByFilter(['id' => $storeAccountInfo['store_id'], 'project_id' => $storeAccountInfo['project_id'], 'deleted_at' => true, 'is_active' => Store::IS_ACTIVE]);
        return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $store);
    }

    public function sidebarInfo(BaseRequest $request)
    {
        $storeAccountInfo = $request->get('storeAccountInfo');
        $isAccountBooking = eFunction::isAccountBooking($storeAccountInfo);
        $isAdminStore = eFunction::isAdminStore($storeAccountInfo);
        $filterDevice['store_id'] = (int)$storeAccountInfo['store_id'];
        $filterDevice['project_id'] = (int)$storeAccountInfo['project_id'];
        $filterDevice['deleted_at'] = true;
        if (!empty($isAccountBooking) && empty($isAdminStore)){
            $filterDevice['branch_id'] = (int)$storeAccountInfo['branch_id'];
        }

        $output['devices'] = $this->deviceRepository->countAllDeviceByFilter($filterDevice);
        $output['points'] = $storeAccountInfo['current_point'];
        $output['totalPoints'] = $storeAccountInfo['total_point'];

        return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $output);
    }

    public function detailBranch(DetailBranchRequest $request)
    {
        $id = $request->get('id');
        $storeAccountInfo = $request->get('storeAccountInfo');
        $branch = $this->branchRepository->getOneArrayBranchWithBrandByFilter(['id' => $id, 'project_id' => $storeAccountInfo['project_id'], 'deleted_at' => true]);
        if (!empty($branch)){
            $branch = eFunction::mergeSubBrandToBrandInBranch($branch);

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $branch);
        }

        return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
    }

    public function updateStore(UpdateStoreRequest $request)
    {
        $data = $request->only(
            [
                'name',
                'slug',
                'district_id',
                'province_id',
            ]
        );
        $id = $request->get('id');
        try{

            DB::beginTransaction();
            $storeAccountInfo = $request->get('storeAccountInfo');
            $store = $this->storeRepository->getOneObjectStoreByFilter(['id' => $id, 'project_id' => $storeAccountInfo['project_id'], 'deleted_at' => true]);
            if (!empty($store)){
                eFunction::FillUpStore($storeAccountInfo, $data);
                $this->storeRepository->update($store, $data);
            }
            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-update-success'), []);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function updateBranch(UpdateBranchRequest $request)
    {
        $data = $request->only(
            [
                'name',
                'slug',
                'contact',
                'phone_number',
            ]
        );

        $id = $request->get('id');

        try{

            DB::beginTransaction();

            $storeAccountInfo = $request->get('storeAccountInfo');

            $branch = $this->branchRepository->getOneObjectBranchByFilter(['id' => $id, 'project_id' => $storeAccountInfo['project_id'], 'deleted_at' => true]);
            if (!empty($branch)){
                eFunction::FillUpStore($storeAccountInfo, $data);
                $this->branchRepository->update($branch, $data);
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-update-success'), []);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function logOperation(PaginationRequest $request)
    {
        try{
            $filter = [];
            $limit = $request->limit();
            $storeAccountInfo = $request->get('storeAccountInfo');
            eFunction::FillUpStore($storeAccountInfo, $filter);

            $this->makeFilter($request, $filter);

            $logOperations = $this->logOperationRepository->getListLogOperationByFilter($limit, $filter);
            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $logOperations);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function logPoint(PaginationRequest $request)
    {
        try{
            $filter = [];
            $limit = $request->limit();
            $storeAccountInfo = $request->get('storeAccountInfo');
            eFunction::FillUpStore($storeAccountInfo, $filter);

            $this->makeFilter($request, $filter);
            if (empty($filter['type'])){
                $filter['type'] = LogPoint::TYPE_BONUS_POINT;
            }

            $logPoints = $this->logPointRepository->getListLogPointByFilter($limit, $filter);
            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $logPoints);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    private function makeFilter($request, &$filter)
    {
        if ($request->has('key_word')) {
            $filter['key_word'] = $request->get('key_word');
        }

        if ($request->has('type')) {
            $filter['type'] = $request->get('type');
        }
    }
}
