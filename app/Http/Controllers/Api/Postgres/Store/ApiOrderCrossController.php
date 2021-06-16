<?php

namespace App\Http\Controllers\Api\Postgres\Store;

use App\Elibs\eFunction;
use App\Http\Controllers\Controller;
use App\Elibs\eResponse;
use App\Http\Requests\Api\PaginationRequest;
use App\Http\Requests\Api\Postgres\Store\CrossOrder\InfoInCrossOrderRequest;
use App\Http\Requests\Api\Postgres\Store\CrossOrder\ListBranchPaginationRequest;
use App\Http\Requests\Api\Postgres\Store\CrossOrder\ListCollectionPaginationRequest;
use App\Http\Requests\Api\Postgres\Store\CrossOrder\ListDevicePaginationRequest;
use App\Models\Postgres\Store\OrderBranch;
use App\Models\Postgres\Store\OrderDevice;
use App\Models\Postgres\Store\OrderStore;
use App\Models\Postgres\Store\StoreCrossDeviceCollection;
use App\Repositories\Postgres\Admin\BranchRepositoryInterface;
use App\Repositories\Postgres\Admin\BranchSubBrandRepositoryInterface;
use App\Repositories\Postgres\Admin\BrandRepositoryInterface;
use App\Repositories\Postgres\Admin\DeviceRepositoryInterface;
use App\Repositories\Postgres\Admin\RankRepositoryInterface;
use App\Repositories\Postgres\Admin\StoreBrandRepositoryInterface;
use App\Repositories\Postgres\Admin\StoreRepositoryInterface;
use App\Repositories\Postgres\Store\CollectionRepositoryInterface;
use App\Repositories\Postgres\Store\OrderBranchRepositoryInterface;
use App\Repositories\Postgres\Store\OrderDeviceRepositoryInterface;
use App\Repositories\Postgres\Store\OrderRepositoryInterface;
use App\Repositories\Postgres\Store\OrderStoreRepositoryInterface;
use App\Repositories\Postgres\Store\StoreCrossDeviceCollectionRepositoryInterface;
use App\Repositories\Postgres\Store\TimeFrameRepositoryInterface;
use DateInterval;
use DatePeriod;
use DateTime;
use  DB;

class ApiOrderCrossController extends Controller
{

    protected $rankRepository;
    protected $storeRepository;
    protected $orderRepository;
    protected $orderStoreRepository;
    protected $orderBranchRepository;
    protected $orderDeviceRepository;
    protected $timeFrameRepository;
    protected $deviceRepository;
    protected $branchRepository;
    protected $brandRepository;
    protected $branchSubBrandRepository;
    protected $storeBrandRepository;
    protected $collectionRepository;
    protected $storeCrossDeviceCollectionRepository;

    public function __construct(
        RankRepositoryInterface $rankRepository,
        StoreCrossDeviceCollectionRepositoryInterface $storeCrossDeviceCollectionRepository,
        StoreRepositoryInterface $storeRepository,
        OrderRepositoryInterface $orderRepository,
        OrderStoreRepositoryInterface $orderStoreRepository,
        OrderBranchRepositoryInterface $orderBranchRepository,
        OrderDeviceRepositoryInterface $orderDeviceRepository,
        TimeFrameRepositoryInterface $timeFrameRepository,
        DeviceRepositoryInterface $deviceRepository,
        BranchSubBrandRepositoryInterface $branchSubBrandRepository,
        BranchRepositoryInterface $branchRepository,
        StoreBrandRepositoryInterface $storeBrandRepository,
        CollectionRepositoryInterface $collectionRepository,
        BrandRepositoryInterface $brandRepository
    )
    {
        $this->branchSubBrandRepository = $branchSubBrandRepository;
        $this->storeBrandRepository = $storeBrandRepository;
        $this->deviceRepository = $deviceRepository;
        $this->branchRepository = $branchRepository;
        $this->brandRepository = $brandRepository;
        $this->rankRepository = $rankRepository;
        $this->storeRepository = $storeRepository;
        $this->orderRepository = $orderRepository;
        $this->orderStoreRepository = $orderStoreRepository;
        $this->orderBranchRepository = $orderBranchRepository;
        $this->collectionRepository = $collectionRepository;
        $this->orderDeviceRepository = $orderDeviceRepository;
        $this->timeFrameRepository = $timeFrameRepository;
        $this->storeCrossDeviceCollectionRepository = $storeCrossDeviceCollectionRepository;
    }

    public function index(PaginationRequest $request)
    {
        try{
            $filter = [];
            $limit = $request->limit();
            $storeAccountInfo = $request->get('storeAccountInfo');
            $this->makeFilter($request, $filter);
            $filter['store_cross_id'] = $storeAccountInfo['store_id'];
            $filter['project_id'] = $storeAccountInfo['project_id'];
            $filter['deleted_at'] = true;

            $orders = $this->orderRepository->getListOrderCrossByFilter($limit, $filter);
            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $orders);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function infoStoreAndBranchInCrossOrder(InfoInCrossOrderRequest $request)
    {
        $storeAccountInfo = $request->get('storeAccountInfo');
        $this->makeFilterOrder($request, $filter);
        if (empty($filter['order_id'])){
            return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
        }

        $params = [];
        if (!empty($filter['order_branch_id'])){
            $orderBranch = $this->orderBranchRepository->getOneArrayOrderBranchByFilter(['id' => (int)$filter['order_branch_id'], 'order_id' => (int)$filter['order_id'], 'project_id' => $storeAccountInfo['project_id'], 'status' => OrderBranch::STATUS_USING]);
            $params['totalMedias'] = $this->storeCrossDeviceCollectionRepository->countAllStoreCrossDeviceCollectionByFilter(['order_id' => (int)$filter['order_id'], 'project_id' => $storeAccountInfo['project_id']]);
            $params['totalPoints'] = @$orderBranch['point'];
            $params['branch'] = @$orderBranch['branch'];
            $params['deviceCount'] = @$orderBranch['devices_count'];

            $orderStore = $this->orderStoreRepository->getOneArrayOrderStoreByFilter(['id' => (int)$orderBranch['order_store_id'], 'order_id' => (int)$filter['order_id'], 'project_id' => $storeAccountInfo['project_id'], 'status' => OrderStore::STATUS_USING]);
            $params['store'] = @$orderStore['store'];
            $params['branchCount'] = @$orderStore['branches_count'];
        }

        return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $params);
    }

    public function listBranchInDetailCross(ListBranchPaginationRequest $request)
    {
        try{
            $filter = [];
            $limit = $request->limit();
            $this->makeFilterOrder($request, $filter);
            $storeAccountInfo = $request->get('storeAccountInfo');
            $filter['project_id'] = $storeAccountInfo['project_id'];
            $filter['store_cross_id'] = $storeAccountInfo['store_id'];
            $filter['status'] = OrderBranch::STATUS_USING;

            if (!empty($filter['key_word'])){
                $branches = $this->branchRepository->getAllBranchForStoreByFilter(['key_word' => $filter['key_word'], 'project_id' => $storeAccountInfo['project_id']]);
                if (empty($branches)){
                    return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), []);
                }

                $filter['branch_id'] = collect($branches)->pluck('id')->values()->toArray();
            }


            $orderBranches = $this->orderBranchRepository->getListOrderBranchByFilter($limit, $filter);
            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $orderBranches);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function listCollectionInDetailCross(ListCollectionPaginationRequest $request)
    {
        try{
            $filter = [];
            $limit = $request->limit();
            $this->makeFilterOrder($request, $filter);
            $storeAccountInfo = $request->get('storeAccountInfo');
            $filter['project_id'] = $storeAccountInfo['project_id'];

            $filterOrderStore['order_id'] = (int)$filter['order_id'];
            $filterOrderStore['store_id'] = $storeAccountInfo['store_id'];
            $filterOrderStore['project_id'] = $storeAccountInfo['project_id'];
            $filterOrderStore['status'] = OrderStore::STATUS_USING;

            $orderStore = $this->orderStoreRepository->getOneArrayOrderStoreByFilter($filterOrderStore);
            if(empty($orderStore)){
                return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), []);
            }

            $filter['order_store_id'] = $orderStore['id'];

            if (!empty($filter['key_word'])){
                $collections = $this->collectionRepository->getAllCollectionByFilter(['key_word' => $filter['key_word'], 'project_id' => $storeAccountInfo['project_id']]);
                if (empty($collections)){
                    return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), []);
                }

                $filterCollection['collection_id'] = collect($collections)->pluck('id')->values()->toArray();
            }

            $orderBranch = $this->orderBranchRepository->getOneArrayOrderBranchByFilter(['order_id' => (int)$filter['order_id'], 'id' => (int)$filter['order_branch_id'], 'order_store_id' => (int)$filter['order_store_id'], 'project_id' => $storeAccountInfo['project_id'], 'status' => OrderBranch::STATUS_USING]);
            $order = $this->orderRepository->getOneArrayOrderByFilter(['id' => $filter['order_id'], 'project_id' => $storeAccountInfo['project_id'], 'deleted_at' => true]);
            if (empty($orderBranch) || empty($order) || empty($order['time_frames'])){
                return eResponse::responsePagination(STATUS_API_FALSE, __('notification.system.data-not-found'));
            }
            $totalTimes = eFunction::getTotalTimeInOrder($order['time_frames']);

            $filterCollection['status'] = [StoreCrossDeviceCollection::COLLECTION_STATUS_WAIT, StoreCrossDeviceCollection::COLLECTION_STATUS_CONFIRMED];
            $filterCollection['order_id'] = $filter['order_id'];
            $filterCollection['project_id'] = $storeAccountInfo['project_id'];

            $storeCrossDeviceCollections = $this->storeCrossDeviceCollectionRepository->getListStoreCrossDeviceCollectionByFilter($limit, $filterCollection);
            if (!empty($storeCrossDeviceCollections['data'])){
                foreach ($storeCrossDeviceCollections['data'] as $k => $collection){
                    $storeCrossDeviceCollections['data'][$k]['duration'] = $totalTimes*$orderBranch['devices_count'];
                    $storeCrossDeviceCollections['data'][$k]['totalTimes'] = $totalTimes*$orderBranch['devices_count']*(int)$collection['second'];
                    $point = eFunction::getPointCollection((int)$collection['type'], (int)$collection['second'], $totalTimes, $orderBranch['rank']['coefficient'], $orderBranch['devices_count']);
                    $storeCrossDeviceCollections['data'][$k]['point'] = round($point, 1);

                    if (!empty($collection['collection']['source'])){
                        $storeCrossDeviceCollections['data'][$k]['source'] = asset($collection['collection']['source']);
                    }

                    if (!empty($collection['collection']['mimes'])){
                        $storeCrossDeviceCollections['data'][$k]['mimes'] = $collection['collection']['mimes'];
                    }

                    if (!empty($collection['collection']['source_thumb'])){
                        $storeCrossDeviceCollections['data'][$k]['source_thumb'] = asset($collection['collection']['source_thumb']);
                    }

                    unset($collection['collection']);
                }
            }
            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $storeCrossDeviceCollections);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function listDeviceInDetailCross(ListDevicePaginationRequest $request)
    {
        try{
            $filter = [];
            $limit = $request->limit();
            $this->makeFilterOrder($request, $filter);
            $storeAccountInfo = $request->get('storeAccountInfo');
            $filter['project_id'] = $storeAccountInfo['project_id'];
            $filter['status'] = OrderDevice::STATUS_USING;

            $filterOrderStore['order_id'] = (int)$filter['order_id'];
            $filterOrderStore['store_id'] = $storeAccountInfo['store_id'];
            $filterOrderStore['project_id'] = $storeAccountInfo['project_id'];
            $filterOrderStore['status'] = OrderStore::STATUS_USING;

            $orderStore = $this->orderStoreRepository->getOneArrayOrderStoreByFilter($filterOrderStore);
            if(empty($orderStore)){
                return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), []);
            }

            $filter['order_store_id'] = $orderStore['id'];

            if (!empty($filter['key_word'])){
                $devices = $this->deviceRepository->getAllDeviceByFilter(['key_word' => $filter['key_word'], 'project_id' => $storeAccountInfo['project_id']]);
                if (empty($devices)){
                    return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), []);
                }

                $filter['device_id'] = collect($devices)->pluck('id')->values()->toArray();
            }

            $detailOrders = $this->orderDeviceRepository->getListOrderDeviceWithDeviceByFilter($limit, $filter);
            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $detailOrders);

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

        if ($request->has('status')) {
            $filter['status'] = $request->get('status');
        }

        if ($request->has('sub_brand_id')) {
            $filter['sub_brand_id'] = $request->get('sub_brand_id');
        }

        if ($request->has('order_id')) {
            $filter['order_id'] = $request->get('order_id');
        }

        if ($request->has('id')) {
            $filter['id'] = $request->get('id');
        }

        if ($request->has('store_id')) {
            $filter['store_id'] = $request->get('store_id');
        }

        if ($request->has('branch_id')) {
            $filter['branch_id'] = $request->get('branch_id');
        }

        if ($request->has('province_id')) {
            $filter['province_id'] = $request->get('province_id');
        }

        if ($request->has('district_id')) {
            $filter['district_id'] = $request->get('district_id');
        }

        if ($request->has('rank_id')) {
            $filter['rank_id'] = $request->get('rank_id');
        }

        if ($request->has('time_book')) {
            $filter['time_book'] = $request->get('time_book');
        }
    }

    private function makeFilterOrder($request, &$filter)
    {
        if ($request->has('key_word')) {
            $filter['key_word'] = $request->get('key_word');
        }

        if ($request->has('id')) {
            $filter['id'] = $request->get('id');
        }

        if ($request->has('order_id')) {
            $filter['order_id'] = $request->get('order_id');
        }

        if ($request->has('order_store_id')) {
            $filter['order_store_id'] = $request->get('order_store_id');
        }

        if ($request->has('order_branch_id')) {
            $filter['order_branch_id'] = $request->get('order_branch_id');
        }

    }
}
