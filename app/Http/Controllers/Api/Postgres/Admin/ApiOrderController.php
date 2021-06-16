<?php

namespace App\Http\Controllers\Api\Postgres\Admin;

use App\Elibs\eFunction;
use App\Http\Controllers\Controller;
use App\Elibs\eResponse;
use App\Http\Requests\Api\PaginationRequest;
use App\Http\Requests\Api\Postgres\Admin\Order\AcceptRequestRequest;
use App\Http\Requests\Api\Postgres\Admin\Order\DetailOrderRequest;
use App\Http\Requests\Api\Postgres\Admin\Order\InfoInOrderRequest;
use App\Http\Requests\Api\Postgres\Admin\Order\ListBranchPaginationRequest;
use App\Http\Requests\Api\Postgres\Admin\Order\ListCollectionPaginationRequest;
use App\Http\Requests\Api\Postgres\Admin\Order\ListDevicePaginationRequest;
use App\Http\Requests\Api\Postgres\Admin\Order\ListStorePaginationRequest;
use App\Models\Postgres\Admin\Branch;
use App\Models\Postgres\Admin\Device;
use App\Models\Postgres\Admin\Store;
use App\Models\Postgres\Store\LogPoint;
use App\Models\Postgres\Store\Order;
use App\Models\Postgres\Store\OrderBranch;
use App\Models\Postgres\Store\OrderDevice;
use App\Models\Postgres\Store\OrderStore;
use App\Models\Postgres\Store\StoreAccount;
use App\Models\Postgres\Store\StoreCrossDeviceCollection;
use App\Repositories\Postgres\Admin\BranchRepositoryInterface;
use App\Repositories\Postgres\Admin\BranchSubBrandRepositoryInterface;
use App\Repositories\Postgres\Admin\BrandRepositoryInterface;
use App\Repositories\Postgres\Admin\DeviceRepositoryInterface;
use App\Repositories\Postgres\Admin\RankRepositoryInterface;
use App\Repositories\Postgres\Admin\StoreBrandRepositoryInterface;
use App\Repositories\Postgres\Admin\StoreRepositoryInterface;
use App\Repositories\Postgres\Store\CollectionRepositoryInterface;
use App\Repositories\Postgres\Store\LogPointRepositoryInterface;
use App\Repositories\Postgres\Store\OrderBranchRepositoryInterface;
use App\Repositories\Postgres\Store\OrderDeviceRepositoryInterface;
use App\Repositories\Postgres\Store\OrderRepositoryInterface;
use App\Repositories\Postgres\Store\OrderStoreRepositoryInterface;
use App\Repositories\Postgres\Store\StoreCrossDeviceCollectionRepositoryInterface;
use App\Repositories\Postgres\Store\TimeFrameLogPointRepositoryInterface;
use App\Repositories\Postgres\Store\TimeFrameRepositoryInterface;
use App\Services\CommonServiceInterface;
use DateInterval;
use DatePeriod;
use DateTime;
use  DB;
use Illuminate\Database\Eloquent\Model;
use function Composer\Autoload\includeFile;

class ApiOrderController extends Controller
{

    protected $rankRepository;
    protected $logPointRepository;
    protected $timeFrameLogPointRepository;
    protected $commonService;
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
        LogPointRepositoryInterface $logPointRepository,
        TimeFrameLogPointRepositoryInterface $timeFrameLogPointRepository,
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
        CommonServiceInterface $commonService,
        BrandRepositoryInterface $brandRepository
    )
    {
        $this->branchSubBrandRepository = $branchSubBrandRepository;
        $this->logPointRepository = $logPointRepository;
        $this->timeFrameLogPointRepository = $timeFrameLogPointRepository;
        $this->storeBrandRepository = $storeBrandRepository;
        $this->deviceRepository = $deviceRepository;
        $this->orderStoreRepository = $orderStoreRepository;
        $this->orderBranchRepository = $orderBranchRepository;
        $this->branchRepository = $branchRepository;
        $this->brandRepository = $brandRepository;
        $this->rankRepository = $rankRepository;
        $this->storeRepository = $storeRepository;
        $this->orderRepository = $orderRepository;
        $this->collectionRepository = $collectionRepository;
        $this->orderDeviceRepository = $orderDeviceRepository;
        $this->timeFrameRepository = $timeFrameRepository;
        $this->commonService = $commonService;
        $this->storeCrossDeviceCollectionRepository = $storeCrossDeviceCollectionRepository;
    }

    public function index(PaginationRequest $request)
    {
        try{
            $filter = [];
            $limit = $request->limit();
            $accountInfo = $request->get('accountInfo');
            $this->makeFilter($request, $filter);
            $filter['project_id'] = $accountInfo['project_id'];
            $filter['deleted_at'] = true;

            $orders = $this->orderRepository->getListOrderByFilter($limit, $filter);
            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $orders);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function listStoreInDetail(ListStorePaginationRequest $request)
    {
        try{
            $filter = [];
            $limit = $request->limit();
            $accountInfo = $request->get('accountInfo');
            $this->makeFilterOrder($request, $filter);
            $filter['project_id'] = $accountInfo['project_id'];

            if (!empty($filter['key_word'])){
                $stores = $this->storeRepository->getAllStoreByFilter(['key_word' => $filter['key_word'], 'project_id' => $accountInfo['project_id']]);
                if (empty($stores)){
                    return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), []);
                }

                $filter['store_id'] = collect($stores)->pluck('id')->values()->toArray();
            }
            $orderStores = $this->orderStoreRepository->getListOrderStoreByFilter($limit, $filter);
            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $orderStores);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function detail(DetailOrderRequest $request)
    {
        try{
            $accountInfo = $request->get('accountInfo');

            $this->makeFilter($request, $filter);
            $filter['project_id'] = $accountInfo['project_id'];
            $filter['deleted_at'] = true;
            $detailOrders = $this->orderRepository->getOneArrayOrderByFilter($filter);
            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $detailOrders);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function infoStoreAndBranchInOrder(InfoInOrderRequest $request)
    {
        $accountInfo = $request->get('accountInfo');
        $this->makeFilterOrder($request, $filter);
        if (empty($filter['order_id']) || empty($filter['order_store_id'])){
            return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
        }

        $orderStore = $this->orderStoreRepository->getOneArrayOrderStoreByFilter(['order_id' => (int)$filter['order_id'], 'id' => (int)$filter['order_store_id'], 'project_id' => $accountInfo['project_id']]);
        $params['totalPoints'] = @$orderStore['point'];
        $params['store'] = @$orderStore['store'];
        $params['branchCount'] = @$orderStore['branches_count'];

        if (!empty($filter['order_branch_id'])){
            $orderBranch = $this->orderBranchRepository->getOneArrayOrderBranchByFilter(['order_id' => (int)$filter['order_id'], 'id' => (int)$filter['order_branch_id'], 'order_store_id' => (int)$filter['order_store_id'], 'project_id' => $accountInfo['project_id']]);
            $params['totalMedias'] = $this->storeCrossDeviceCollectionRepository->countAllStoreCrossDeviceCollectionByFilter(['order_id' => (int)$filter['order_id'], 'project_id' => $accountInfo['project_id']]);
            $params['totalPoints'] = @$orderBranch['point'];
            $params['branch'] = @$orderBranch['branch'];
            $params['deviceCount'] = @$orderBranch['devices_count'];
        }

        return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $params);
    }

    public function listBranchInDetail(ListBranchPaginationRequest $request)
    {
        try{
            $filter = [];
            $limit = $request->limit();
            $this->makeFilterOrder($request, $filter);
            $accountInfo = $request->get('accountInfo');
            $filter['project_id'] = $accountInfo['project_id'];

            if (!empty($filter['key_word'])){
                $branches = $this->branchRepository->getAllBranchForStoreByFilter(['key_word' => $filter['key_word'], 'project_id' => $accountInfo['project_id']]);
                if (empty($branches)){
                    return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), []);
                }

                $filter['branch_id'] = collect($branches)->pluck('id')->values()->toArray();
            }

            $orderBranches = $this->orderBranchRepository->getListOrderBranchByFilter($limit, $filter);
            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $orderBranches);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function listCollectionInDetail(ListCollectionPaginationRequest $request)
    {
        try{
            $filter = [];
            $limit = $request->limit();
            $this->makeFilterOrder($request, $filter);
            $accountInfo = $request->get('accountInfo');
            $filter['project_id'] = $accountInfo['project_id'];

            if (!empty($filter['key_word'])){
                $collections = $this->collectionRepository->getAllCollectionByFilter(['key_word' => $filter['key_word'], 'project_id' => $accountInfo['project_id']]);
                if (empty($collections)){
                    return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), []);
                }

                $filterCollection['collection_id'] = collect($collections)->pluck('id')->values()->toArray();
            }

            $orderBranch = $this->orderBranchRepository->getOneArrayOrderBranchByFilter(['order_id' => (int)$filter['order_id'], 'id' => (int)$filter['order_branch_id'], 'order_store_id' => (int)$filter['order_store_id'], 'project_id' => $accountInfo['project_id']]);
            $order = $this->orderRepository->getOneArrayOrderByFilter(['id' => $filter['order_id'], 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
            if (empty($orderBranch)|| empty($order) || empty($order['time_frames'])){
                return eResponse::responsePagination(STATUS_API_FALSE, __('notification.system.data-not-found'));
            }

            $totalTimes = eFunction::getTotalTimeInOrder($order['time_frames']);
            $filterCollection['order_id'] = $filter['order_id'];
            $filterCollection['project_id'] = $accountInfo['project_id'];

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

    public function listDeviceInDetail(ListDevicePaginationRequest $request)
    {
        try{
            $filter = [];
            $limit = $request->limit();
            $this->makeFilterOrder($request, $filter);
            $accountInfo = $request->get('accountInfo');
            $filter['project_id'] = $accountInfo['project_id'];

            if (!empty($filter['key_word'])){
                $devices = $this->deviceRepository->getAllDeviceByFilter(['key_word' => $filter['key_word'], 'project_id' => $accountInfo['project_id']]);
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

    public function listCollectionInDetailWaiting(PaginationRequest $request)
    {
        try{
            $filter = [];
            $limit = $request->limit();
            $this->makeFilterOrder($request, $filter);
            $accountInfo = $request->get('accountInfo');
            $filter['project_id'] = $accountInfo['project_id'];

            if (empty($filter['order_id'])){
                return eResponse::responsePagination(STATUS_API_FALSE, __('notification.system.data-not-found'));
            }

            if (!empty($filter['key_word'])){
                $collections = $this->collectionRepository->getAllCollectionByFilter(['key_word' => $filter['key_word'], 'project_id' => $accountInfo['project_id']]);
                if (empty($collections)){
                    return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), []);
                }

                $filterCollection['collection_id'] = collect($collections)->pluck('id')->values()->toArray();
            }

            $orderDevices = $this->orderDeviceRepository->getAllOrderDeviceByFilter(['order_id' => (int)$filter['order_id'], 'status' => [OrderDevice::STATUS_USING, OrderDevice::STATUS_DELETE], 'project_id' => $accountInfo['project_id']]);
            $order = $this->orderRepository->getOneArrayOrderByFilter(['id' => $filter['order_id'], 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
            if (empty($orderDevices) || empty($order) || empty($order['time_frames'])){
                return eResponse::responsePagination(STATUS_API_FALSE, __('notification.system.data-not-found'));
            }

            $filterDevices['id'] = collect($orderDevices)->pluck('device_id')->filter()->unique()->values()->toArray();
            $filterDevices['project_id'] = $accountInfo['project_id'];
            $filterDevices['deleted_at'] = true;
            $devices = $this->deviceRepository->getAllDeviceWithRankByFilter($filterDevices);
            if (empty($devices)){
                return eResponse::responsePagination(STATUS_API_FALSE, __('notification.system.data-not-found'));
            }

            $totalDevices = count($devices);
            $totalTimes = eFunction::getTotalTimeInOrder($order['time_frames']);
            $filterCollection['order_id'] = $filter['order_id'];
            $filterCollection['project_id'] = $accountInfo['project_id'];

            $storeCrossDeviceCollections = $this->storeCrossDeviceCollectionRepository->getListStoreCrossDeviceCollectionByFilter($limit, $filterCollection);
            if (!empty($storeCrossDeviceCollections['data'])){
                foreach ($storeCrossDeviceCollections['data'] as $k => $collection){
                    $storeCrossDeviceCollections['data'][$k]['duration'] = $totalTimes*$totalDevices;
                    $storeCrossDeviceCollections['data'][$k]['totalTimes'] = $totalTimes*$totalDevices*(int)$collection['second'];
                    $point = eFunction::getPointCollectionAdmin((int)$collection['type'], (int)$collection['second'], $totalTimes, $devices);
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

            $storeCrossDeviceCollections['status_order'] = $order['status'];

            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $storeCrossDeviceCollections);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function acceptRequest(AcceptRequestRequest $request)
    {
        $orderId = $request->get('order_id');
        $type = $request->get('type');
        $reason = $request->get('reason');
        $accountInfo = $request->get('accountInfo');
        if (!in_array($type, [Order::RESOLVE, Order::REJECT])){
            return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
        }

        try {

            DB::beginTransaction();
            $order = $this->orderRepository->getOneObjectOrderByFilter(['id' => (int)$orderId, 'deleted_at' => true, 'project_id' => $accountInfo['project_id']]);
            $storeCrossWaitingDeviceCollections = $this->storeCrossDeviceCollectionRepository->getAllStoreCrossDeviceCollectionByFilter(['order_id' => (int)$orderId, 'status' => StoreCrossDeviceCollection::COLLECTION_STATUS_WAIT, 'project_id' => $accountInfo['project_id']]);
            $storeCrossConfirmedDeviceCollections = $this->storeCrossDeviceCollectionRepository->getAllStoreCrossDeviceCollectionByFilter(['order_id' => (int)$orderId, 'status' => StoreCrossDeviceCollection::COLLECTION_STATUS_CONFIRMED, 'project_id' => $accountInfo['project_id']]);
            if (empty($order)) {
                return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
            }

            //Cộng điểm cho cửa hàng đặt chéo hoặc hoàn điển lại khi bị reject
            $this->pointForStores((int)$orderId, $accountInfo, (int)$type);

            $params = [];
            $status = (int)$type === Order::RESOLVE ? StoreCrossDeviceCollection::COLLECTION_STATUS_CONFIRMED : StoreCrossDeviceCollection::COLLECTION_STATUS_DECLINED;
            if (!empty($storeCrossWaitingDeviceCollections)){
                $arrCrossCollections = [];
                $userInstance = new StoreCrossDeviceCollection();
                $index = 'id';

                foreach ($storeCrossWaitingDeviceCollections as $val){
                    $arrCrossCollections[] = [
                        'id' => $val['id'],
                        'status' => $status
                    ];
                }

                if (!empty($arrCrossCollections)){
                    \Batch::update($userInstance, $arrCrossCollections, $index);
                }
            }

            if ((int)$type === Order::RESOLVE){
                $params['status'] = Order::CONFIRMED;
            }else{
                $params['status'] = !empty($storeCrossConfirmedDeviceCollections) ? Order::CONFIRMED : Order::DECLINED;
                $params['reason'] = $reason;

                $this->orderStoreRepository->deleteAllOrderStoreByFilter(['order_id' => (int)$orderId, 'status' => OrderStore::STATUS_USING]);
                $this->orderBranchRepository->deleteAllOrderBranchByFilter(['order_id' => (int)$orderId, 'status' => OrderBranch::STATUS_USING]);
                $this->orderDeviceRepository->deleteAllOrderDeviceByFilter(['order_id' => (int)$orderId, 'status' => OrderDevice::STATUS_USING]);
            }

            $params['approval_time'] = eFunction::getDateTimeNow();
            $this->orderRepository->update($order, $params);

            if ((int)$type === Order::RESOLVE){
                $arrActivities  = [];
                $storeCrossDeviceCollectionForLogPoints = $this->storeCrossDeviceCollectionRepository->getAllStoreCrossDeviceCollectionByFilter(['order_id' => (int)$orderId, 'status' => StoreCrossDeviceCollection::COLLECTION_STATUS_CONFIRMED, 'project_id' => $accountInfo['project_id']]);
                //Lấy tất cả các thiết bị với đơn hàng đang duyệt
                $orderDevices = $this->orderDeviceRepository->getAllOrderDeviceForQueueByFilter(['order_id' => (int)$orderId, 'status' => OrderDevice::STATUS_USING]);
                if (!empty($orderDevices)){
                    $idsDevices = collect($orderDevices)->pluck('device_id')->filter()->unique()->values()->toArray();
                    foreach ($orderDevices as $orderDevice){
                        //Bắn even cho rabbit MQ
                        if (!empty($orderDevice['device']['device_code']) && isset($orderDevice['device']['is_active']) && (int)$orderDevice['device']['is_active'] === Device::IS_ACTIVE && isset($orderDevice['device']['status']) && (int)$orderDevice['device']['status'] === Device::CONNECT){
                            $params = [
                                'event' => Device::EVENT_CHANGE_MEDIA,
                                'message' => 'Duyệt đơn hàng (ADMIN)',
                                'data' => $this->commonService->getDataFromDeviceId((int)$orderDevice['device_id'])
                            ];

                            eFunction::sendMessageQueue($params, $orderDevice['device']['device_code']);
                        }

                        //Thêm log lịch sử hoạt động cộng tiền cho cửa hàng
                        $arrActivities[] = [
                            'type' => LogPoint::TYPE_BONUS_POINT,
                            'point' => @$orderDevice['point'],
                            'time' => !empty($storeCrossDeviceCollectionForLogPoints) ? collect($storeCrossDeviceCollectionForLogPoints)->sum('second') : 0,
                            'code' => @$orderDevice['order']['code'],
                            'device_id' => @$orderDevice['device_id'],
                            'transaction' => LogPoint::TRANSACTION['plus'],
                            'order_id' => (int)@$orderDevice['order']['id'],
                            'store_id' => @$orderDevice['device']['store_id'],
                            'branch_id' => @$orderDevice['device']['branch_id'],
                            'project_id' => $accountInfo['project_id'],
                            'created_at' => eFunction::getDateTimeNow(),
                            'updated_at' => eFunction::getDateTimeNow()
                        ];
                    }

                    if (!empty($arrActivities)){
                        $this->logPointRepository->insertMulti($arrActivities);
                    }

                    //Lấy lại các log vừa công tiền của cửa hàng ra để thêm khoảng thời gian vào thêm vào ra để thêm thời gian
                    $logPoints = $this->logPointRepository->getAllLogPointByFilter(['order_id' => (int)$order->id, 'device_id' => $idsDevices, 'type' => LogPoint::TYPE_BONUS_POINT]);
                    if (!empty($logPoints)){
                        //Lấy lại các khung thời gian của đơn hàng ra để mapping vào log cộng điểm của cửa hàng
                        $timeFrames = $this->timeFrameRepository->getAllTimeFramesByFilter(['order_id' => (int)$order->id]);
                        if (!empty($timeFrames)){
                            $arrTimeFrameLogPoints = [];
                            foreach ($logPoints as $log){
                                foreach ($timeFrames as $time){
                                    $arrTimeFrameLogPoints[] = [
                                        'log_point_id' => (int)$log['id'],
                                        'time_frame_id' => (int)$time['id'],
                                        'order_id' => (int)$order->id,
                                        'project_id' => $accountInfo['project_id'],
                                        'created_at' => eFunction::getDateTimeNow(),
                                        'updated_at' => eFunction::getDateTimeNow()
                                    ];
                                }
                            }

                            if (!empty($arrTimeFrameLogPoints)){
                                $this->timeFrameLogPointRepository->insertMulti($arrTimeFrameLogPoints);
                            }
                        }
                    }

                }
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-update-success'));
        } catch (\Exception $e) {
            DB::rollback();
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'));
        }
    }

    private function pointForStores($orderId, $accountInfo, $type)
    {
        $arrStore = $arrBranch = [];
        //Lấy tất cả các thiết bị đã được đặt với đơn hàng này
        $orderDevices = $this->orderDeviceRepository->getAllOrderDeviceForQueueByFilter(['order_id' => (int)$orderId, 'status' => OrderDevice::STATUS_USING, 'project_id' => $accountInfo['project_id']]);
        $order = $this->orderRepository->getOneArrayOrderByFilter(['id' => (int)$orderId, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
        if (!empty($order) && !empty($orderDevices)){
            if ((int)$type === Order::RESOLVE){
                $arrBranchAdsIds = [];
                $orderBranchIds = collect($orderDevices)->pluck('order_branch_id')->filter()->unique()->values()->toArray();
                $orderBranches = $this->orderBranchRepository->getAllOrderBranchWithBranchByFilter(['id' => $orderBranchIds, 'status' => OrderBranch::STATUS_USING]);
                if (!empty($orderBranches)){
                    foreach ($orderBranches as $orderBranch){
                        if (!empty($orderBranch['branch']['make_ads']) && (int)$orderBranch['branch']['make_ads'] === StoreAccount::MAKE_ADS_TRUE){
                            $arrBranchAdsIds[] = (int)$orderBranch['branch_id'];
                        }
                    }
                }

                foreach ($orderDevices as $orderDevice){
                    if (!empty($orderDevice['device']) && !empty($orderDevice['deivce']['store_id'])){
                        //Thêm hoạt động cộng tiền với từng thiết bị
                        $activities[] = [
                            'type' => LogPoint::TYPE_BONUS_POINT,
                            'time' => $order['time_booked'],
                            'point' => $orderDevice['real_point'],
                            'transaction' => LogPoint::TRANSACTION['plus'],
                            'store_id' => (int)$orderDevice['device']['store_id'],
                            'device_id' => (int)$orderDevice['device']['branch_id'],
                            'branch_id' => (int)$orderDevice['device_id'],
                            'project_id' => (int)$orderDevice['project_id'],
                            'created_at' => eFunction::getDateTimeNow(),
                            'updated_at' => eFunction::getDateTimeNow()
                        ];

                        //Công tiền cho chi nhánh có quảng cáo
                        if (!empty($arrBranchAdsIds)){
                            if (!empty($arrBranch[$orderDevice['deivce']['branch_id']])){
                                $arrBranch[$orderDevice['deivce']['branch_id']] = $arrBranch[$orderDevice['deivce']['branch_id']] + $orderDevice['real_point'];
                            }else{
                                $arrBranch[$orderDevice['deivce']['branch_id']] = $orderDevice['real_point'];
                            }
                        }

                        //Cộng tiền cho cửa hàng
                        if (!in_array($orderDevice['deivce']['branch_id'], $arrBranchAdsIds)){
                            if (!empty($arrStore[$orderDevice['deivce']['store_id']])){
                                $arrStore[$orderDevice['deivce']['store_id']] = $arrStore[$orderDevice['deivce']['store_id']] + $orderDevice['real_point'];
                            }else{
                                $arrStore[$orderDevice['deivce']['store_id']] = $orderDevice['real_point'];
                            }
                        }
                    }
                }

                if (!empty($activities)){
                    $this->logPointRepository->insertMulti($activities);
                }

                if (!empty($arrBranch)){
                    $branchIds = array_keys($arrBranch);
                    $branches = $this->branchRepository->getAllBranchByFilter(['id' => $branchIds, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
                    if (!empty($branches)){
                        $BranchInstance = new Branch();
                        $indexBranch = 'id';
                        $arrUpdateBranch = [];
                        foreach ($branches as $branch){
                            $arrUpdateBranch[] = [
                                'id' => (int)$branch['id'],
                                'current_point' => (int)$branch['current_point'] + $arrBranch[$branch['id']],
                                'total_point' => (int)$branch['total_point'] + $arrBranch[$branch['id']],
                            ];
                        }

                        if (!empty($arrUpdateBranch)){
                            \Batch::update($BranchInstance, $arrUpdateBranch, $indexBranch);
                        }
                    }
                }

                if (!empty($arrStore)){
                    $storeIds = array_keys($arrStore);
                    $stores = $this->storeRepository->getAllStoreByFilter(['id' => $storeIds, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
                    if (!empty($stores)){
                        $StoreInstance = new Store();
                        $indexStore = 'id';
                        $arrUpdateStore = [];
                        foreach ($stores as $store){
                            $arrUpdateStore[] = [
                                'id' => (int)$store['id'],
                                'current_point' => (int)$store['current_point'] + $arrStore[$store['id']],
                                'total_point' => (int)$store['total_point'] + $arrStore[$store['id']],
                            ];
                        }

                        if (!empty($arrUpdateStore)){
                            \Batch::update($StoreInstance, $arrUpdateStore, $indexStore);
                        }
                    }
                }
            }else{
                //Thêm hoạt đông cộng tiền tiền lại cho cửa hàng đã đặt đơn hàng này
                $activity = [
                    'type' => LogPoint::TYPE_BONUS_POINT,
                    'time' => $order['time_booked'],
                    'point' => $order['payment'],
                    'transaction' => LogPoint::TRANSACTION['refund_order'],
                    'store_id' => (int)$order['store_id'],
                    'device_id' => null,
                    'branch_id' => (int)$order['store_id'],
                    'project_id' => (int)$order['project_id'],
                ];

                if (!empty($order['branch_id'])){//Khi mà có id của chi nhánh thì là chi nhánh này được quyền đặt hàng và cộng tiền phải vào chi nhánha
                    $branch = $this->branchRepository->getOneObjectBranchByFilter(['id' => $order['branch_id'], 'deleted_at' => true]);
                    if (!empty($branch)){
                        //Thêm lại tiền cho chi nhánh dó
                        $params = [
                            'current_point' => $branch->current_point + $order['payment'],
                            'total_point' => $branch->total_point + $order['payment'],
                        ];

                        $this->branchRepository->update($branch, $params);
                    }
                }else{
                    //Lấy cửa hàng đặt đơn này
                    $store = $this->storeRepository->getOneObjectStoreByFilter(['id' => $order['store_id'], 'deleted_at' => true]);
                    if (!empty($store)){
                        //Thêm lại tiền cho cửa hàng dó
                        $params = [
                            'current_point' => $store->current_point + $order['payment'],
                            'total_point' => $store->total_point + $order['payment'],
                        ];

                        $this->storeRepository->update($store, $params);
                    }
                }

                //Tạo log cộng lại tiền cho cửa hàng đó
                $this->logPointRepository->create($activity);
                //Cập nhật lại tiền
            }
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
