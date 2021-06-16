<?php

namespace App\Http\Controllers\Api\Postgres\Store;

use App\Elibs\eFunction;
use App\Events\SendEmailOrderEvent;
use App\Http\Controllers\Controller;
use App\Elibs\eResponse;
use App\Http\Requests\Api\PaginationRequest;
use App\Http\Requests\Api\Postgres\Store\Order\DetailOrderRequest;
use App\Http\Requests\Api\Postgres\Store\Order\InfoInOrderRequest;
use App\Http\Requests\Api\Postgres\Store\Order\ListBranchPaginationRequest;
use App\Http\Requests\Api\Postgres\Store\Order\ListCollectionOrderRequest;
use App\Http\Requests\Api\Postgres\Store\Order\ListCollectionPaginationRequest;
use App\Http\Requests\Api\Postgres\Store\Order\ListDevicePaginationRequest;
use App\Http\Requests\Api\Postgres\Store\Order\ListStorePaginationRequest;
use App\Http\Requests\Api\Postgres\Store\Order\SaveOrderRequest;
use App\Http\Requests\Api\Postgres\Store\Order\UpdateCollectionOrderRequest;
use App\Http\Requests\BaseRequest;
use App\Models\Postgres\Admin\Device;
use App\Models\Postgres\Admin\Store;
use App\Models\Postgres\Store\Collection;
use App\Models\Postgres\Store\LogPoint;
use App\Models\Postgres\Store\Order;
use App\Models\Postgres\Store\OrderBranch;
use App\Models\Postgres\Store\OrderDevice;
use App\Models\Postgres\Store\OrderStore;
use App\Models\Postgres\Store\StoreAccount;
use App\Models\Postgres\Store\StoreCrossDeviceCollection;
use App\Models\Postgres\Store\StoreDeviceCollection;
use App\Repositories\Postgres\Admin\AccountRepositoryInterface;
use App\Repositories\Postgres\Admin\BranchRepositoryInterface;
use App\Repositories\Postgres\Admin\BranchSubBrandRepositoryInterface;
use App\Repositories\Postgres\Admin\BrandRepositoryInterface;
use App\Repositories\Postgres\Admin\DeviceRepositoryInterface;
use App\Repositories\Postgres\Admin\RankRepositoryInterface;
use App\Repositories\Postgres\Admin\StoreBrandRepositoryInterface;
use App\Repositories\Postgres\Admin\StoreRepositoryInterface;
use App\Repositories\Postgres\Admin\StoreSubBrandRepositoryInterface;
use App\Repositories\Postgres\Admin\SubBrandRepositoryInterface;
use App\Repositories\Postgres\Store\CollectionRepositoryInterface;
use App\Repositories\Postgres\Store\LogOperationRepositoryInterface;
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
use function PHPUnit\Framework\returnArgument;

class ApiOrderController extends Controller
{

    protected $logPointRepository;
    protected $accountRepository;
    protected $rankRepository;
    protected $logOperationRepository;
    protected $storeRepository;
    protected $orderRepository;
    protected $timeFrameRepository;
    protected $deviceRepository;
    protected $branchRepository;
    protected $orderStoreRepository;
    protected $orderBranchRepository;
    protected $orderDeviceRepository;
    protected $brandRepository;
    protected $commonService;
    protected $branchSubBrandRepository;
    protected $storeBrandRepository;
    protected $collectionRepository;
    protected $storeSubBrandRepository;
    protected $subBrandRepository;
    protected $timeFrameLogPointRepository;
    protected $storeCrossDeviceCollectionRepository;

    public function __construct(
        RankRepositoryInterface $rankRepository,
        LogPointRepositoryInterface $logPointRepository,
        TimeFrameLogPointRepositoryInterface $timeFrameLogPointRepository,
        StoreCrossDeviceCollectionRepositoryInterface $storeCrossDeviceCollectionRepository,
        StoreRepositoryInterface $storeRepository,
        AccountRepositoryInterface $accountRepository,
        CommonServiceInterface $commonService,
        OrderRepositoryInterface $orderRepository,
        LogOperationRepositoryInterface $logOperationRepository,
        OrderDeviceRepositoryInterface $orderDeviceRepository,
        OrderStoreRepositoryInterface $orderStoreRepository,
        OrderBranchRepositoryInterface $orderBranchRepository,
        TimeFrameRepositoryInterface $timeFrameRepository,
        DeviceRepositoryInterface $deviceRepository,
        StoreSubBrandRepositoryInterface $storeSubBrandRepository,
        BranchSubBrandRepositoryInterface $branchSubBrandRepository,
        BranchRepositoryInterface $branchRepository,
        StoreBrandRepositoryInterface $storeBrandRepository,
        SubBrandRepositoryInterface $subBrandRepository,
        CollectionRepositoryInterface $collectionRepository,
        BrandRepositoryInterface $brandRepository
    )
    {
        $this->branchSubBrandRepository = $branchSubBrandRepository;
        $this->storeBrandRepository = $storeBrandRepository;
        $this->deviceRepository = $deviceRepository;
        $this->accountRepository = $accountRepository;
        $this->branchRepository = $branchRepository;
        $this->timeFrameLogPointRepository = $timeFrameLogPointRepository;
        $this->logPointRepository = $logPointRepository;
        $this->logOperationRepository = $logOperationRepository;
        $this->brandRepository = $brandRepository;
        $this->rankRepository = $rankRepository;
        $this->storeRepository = $storeRepository;
        $this->commonService = $commonService;
        $this->orderRepository = $orderRepository;
        $this->collectionRepository = $collectionRepository;
        $this->orderDeviceRepository = $orderDeviceRepository;
        $this->orderStoreRepository = $orderStoreRepository;
        $this->orderBranchRepository = $orderBranchRepository;
        $this->storeSubBrandRepository = $storeSubBrandRepository;
        $this->timeFrameRepository = $timeFrameRepository;
        $this->subBrandRepository = $subBrandRepository;
        $this->storeCrossDeviceCollectionRepository = $storeCrossDeviceCollectionRepository;
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

            $orders = $this->orderRepository->getListOrderByFilter($limit, $filter);
            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $orders);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function listRank(BaseRequest $request)
    {
        $storeAccountInfo = $request->get('storeAccountInfo');
        $ranks = $this->rankRepository->getAllRankByFilter(['project_id' => $storeAccountInfo['project_id'], 'deleted_at' => true]);
        return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $ranks);

    }

    public function listBrand(BaseRequest $request)
    {
        $storeAccountInfo = $request->get('storeAccountInfo');
        //Lấy tất cả các sub brand của cửa hàng hiện tại
        $storeSubBrands = $this->storeSubBrandRepository->getAllStoreSubBrandByFilter(['store_id' => $storeAccountInfo['store_id'], 'project_id' => $storeAccountInfo['project_id']]);
        if (!empty($storeSubBrands)){
            $filterSubBrand['id_not_in'] = collect($storeSubBrands)->pluck('sub_brand_id')->filter()->unique()->values()->toArray();
        }

        $filterSubBrand['project_id'] = $storeAccountInfo['project_id'];
        $filterSubBrand['deleted_at'] = true;
        //Lấy tất cả các nhãn con mà không có trong cửa hàng hiện tại
        $subBrands = $this->subBrandRepository->getAllSubBrandByFilter($filterSubBrand);
        if (empty($subBrands)){
            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), []);
        }

        $filter['id'] = collect($subBrands)->pluck('brand_id')->filter()->unique()->values()->toArray();
        $filter['project_id'] = $storeAccountInfo['project_id'];
        $filter['deleted_at'] = true;
        //Lấy tất cả nhãn hàng không có trong cửa hàng hiện tại
        $brands = $this->brandRepository->getAllBrandByFilter($filter);
        if (empty($brands)){
            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), []);
        }

        $brands = collect($brands)->keyBy('id')->toArray();
        foreach ($subBrands as $sub){
            if (!empty($brands[$sub['brand_id']])){
                $brands[$sub['brand_id']]['sub_brands'][] = $sub;
            }
        }

        return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), array_values($brands));

    }

    public function listStore(PaginationRequest $request)
    {
        try{
            $filterStore = [];
            $limit = $request->limit();
            $storeAccountInfo = $request->get('storeAccountInfo');
            $filter = [];
            $this->makeFilter($request, $filter);

            if (empty($filter['start_date']) || empty($filter['end_date']) || empty($filter['start_time']) || empty($filter['end_time'])){
                return eResponse::responsePagination(STATUS_API_ERROR, __('notification.system.lack-data'), []);
            }

            $idsDevices = $this->filterByArguments($filter, $storeAccountInfo);
            $idsDeviceFromTime = $this->filterByTime($filter, $storeAccountInfo);
            if (empty($idsDevices)){
                return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), ['data' => []]);
            }

            //Lấy ra những id của device còn lại khi bỏ đi những thiết bị không thể book nữa
            $idsDevices = array_diff($idsDevices, $idsDeviceFromTime);
            if (empty($idsDevices)){
                return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), ['data' => []]);
            }

            $devices = $this->deviceRepository->getAllDeviceByFilter(['id' => $idsDevices, 'deleted_at' => true, 'project_id' => $storeAccountInfo['project_id']]);
            if (empty($devices)){
                return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), ['data' => []]);
            }

            $branchStore = [];
            foreach ($devices as $device){
                if (!empty($branchStore[$device['store_id']])){
                    $branchStore['store_id'] = $device['store_id'] + 1;
                }else{
                    $branchStore['store_id'] = 1;
                }
            }

            $filterStore['id'] = collect($devices)->pluck('store_id')->filter()->unique()->values()->toArray();
            $filterStore['deleted_at'] = true;
            $filterStore['is_active'] = Store::IS_ACTIVE;
            $filterStore['project_id'] = $storeAccountInfo['project_id'];
            $filterStore['id_not_in'] = $storeAccountInfo['store_id'];
            if (!empty($filter['key_word'])){
                $filterStore['key_word'] = $filter['key_word'];
            }

            $stores = $this->storeRepository->getListStoreByFilter($limit, $filterStore);
            if (!empty($stores) && !empty($stores['data'])){
                foreach ($stores['data'] as $key => $value){
                    $stores['data'][$key]['totalBranch'] = @$branchStore[$value['id']];
                    if (!empty($value['account']) && !empty($value['account']['source'])){
                        $stores['data'][$key]['avatar'] = asset($value['account']['source']);
                    }else{
                        $stores['data'][$key]['avatar'] = null;
                    }

                    unset($stores['data'][$key]['account_id']);
                }
            }


            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $stores);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function listDevice(PaginationRequest $request)
    {
        try{
            $limit = $request->limit();
            $filterStore['order'] = $request->order();
            $filterStore['direction'] = $request->direction();
            $storeAccountInfo = $request->get('storeAccountInfo');

            $filter = [];
            $this->makeFilter($request, $filter);

            if (empty($filter['start_date']) || empty($filter['end_date']) || empty($filter['start_time']) || empty($filter['end_time'])){
                return eResponse::responsePagination(STATUS_API_ERROR, __('notification.system.lack-data'), []);
            }

            $idsDevices = $this->filterByArguments($filter, $storeAccountInfo);
            $idsDeviceFromTime = $this->filterByTime($filter, $storeAccountInfo);
            if (empty($idsDevices)){
                return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), ['data' => []]);
            }

            //Lấy ra những id của device còn lại khi bỏ đi những thiết bị không thể book nữa
            $idsDevices = array_diff($idsDevices, $idsDeviceFromTime);
            if (empty($idsDevices)){
                return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), ['data' => []]);
            }

            $devices = $this->deviceRepository->getListDeviceByFilter($limit, ['id' => $idsDevices, 'deleted_at' => true, 'project_id' => $storeAccountInfo['project_id']]);
            if (!empty($devices['data'])){
                $ids = collect($devices['data'])->pluck('id')->toArray();
                //Lấy list các thiết bị đã được book và có thời gian book
                $listDeviceWithBooked = $this->getTimeDeviceBookedInOrder($ids, $filter, $storeAccountInfo);
                foreach ($devices['data'] as $k => $device){
                    $total_time_empty = $devices['data'][$k]['total_time_empty'];//Thời gian trống thực cửa thiết bị
                    $time_empty_admin = Device::MAX_TIME_ADMIN - (int)$device['total_time_admin']; //Thời gian trống của admin
                    $time_empty_store = Device::MAX_TIME_STORE - (int)$device['block_ads'];//Thời gian trống của store sau khi trừ đi block (Vì để tính case ưu tiên khi có đơn quảng cáo chéo và cửa hàng có cũng có dữ liệu)
                    if ((int)$device['total_time_store'] < (int)$device['block_ads']){//Nếu mà thời gian đặt của cửa hàng nhỏ hơn thời gian block
                        $total_time_empty = $total_time_empty + (int)$device['total_time_store'] - (int)$device['block_ads'];//thì tính lại thời gian còn trống của cửa hàng vì phải trừ đi thời gian block
                    }

                    //Nếu mà thiết bị có thời gian book rồi (Ưu tiên thời gian của book chéo trước vì nếu có book chéo thì thông tin cửa hàng được thêm vào sau)
                    if (!empty($listDeviceWithBooked) && !empty($listDeviceWithBooked[$device['id']])){
                        $total_time_empty = $total_time_empty - (int)$listDeviceWithBooked[$device['id']];//Thời gian trống của thiết bị phải trừ đi thời gian đã book rồi

                        if ((int)$listDeviceWithBooked[$device['id']] >= $time_empty_store){//Nếu mà thời gian đã book của thiết bị mà lớn hơn hoặc bằng thời gian trống của cửa hàng
                            $time_empty_admin = $time_empty_admin - (int)$listDeviceWithBooked[$device['id']] + $time_empty_store;//thời gian còn trống của Ant phải trừ thêm thời gian book chéo (Vì book chéo lớn hơn thời gian tống cửa hàng)
                            $time_empty_store = 0;//thời gian còn trống của cửa hàng = 0
                        }else{
                            //Nếu nhỏ hơn thì chỉ cần lấy thời gian trống cửa hàng trừ đi thơi gian đã book của thiết bị
                            $time_empty_store = $time_empty_store - (int)$listDeviceWithBooked[$device['id']];
                            if ((int)$device['total_time_store'] >= (int)$device['block_ads']){
                                $time_empty_store = $time_empty_store - (int)$device['total_time_store'] + (int)$device['block_ads'];
                                if ($time_empty_store < 0){//Khi mà thời gian còn lại sau khi trừ đi book chéo .Nếu còn dư thì trừ tiếp cho thời gian của cửa hàng.Nếu nhỏ hơn 0 thì đã dùng hết thời gian cửa hàng và trả về 0
                                    $time_empty_store = 0;
                                }
                            }
                        }
                    }else{
                        if ((int)$device['total_time_store'] >= (int)$device['block_ads']){
                            $time_empty_store = $time_empty_store - (int)$device['total_time_store'] + (int)$device['block_ads'];
                        }
                    }

                    $devices['data'][$k]['total_time_empty'] = $total_time_empty;
                    $devices['data'][$k]['time_empty_admin'] = $time_empty_admin;
                    $devices['data'][$k]['time_empty_store'] = $time_empty_store;
                }
            }

            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $devices);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function infoBranchOrStore(BaseRequest $request)
    {
        $storeAccountInfo = $request->get('storeAccountInfo');
        if ($storeAccountInfo['role'] === StoreAccount::SUB && !empty($storeAccountInfo['branch_id'])){
            $info = $this->branchRepository->getOneArrayBranchByFilter(['id' => (int)$storeAccountInfo['branch_id'], 'deleted_at' => true]);
        }else{
            $info = $this->storeRepository->getOneArrayOnlyStoreWithAdminAccountByFilter(['id' => (int)$storeAccountInfo['store_id'], 'deleted_at' => true]);
        }

        if (!empty($info)){
            $params = [
                'name' => @$info['name'],
                'email' => $storeAccountInfo['role'] === StoreAccount::SUB ? @$info['email'] : @$info['account']['email'],
                'phone_number' => $storeAccountInfo['role'] === StoreAccount::SUB ? @$info['phone_number'] : @$info['account']['phone_number'],
                'current_point' => @$info['current_point'],
                'total_point' => @$info['total_point']
            ];

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $params);
        }

        return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
    }

    public function saveOrder(SaveOrderRequest $request)
    {
        $data = $request->only(
            [
                'payment',
                'note',
            ]
        );

        DB::beginTransaction();

        $storeAccountInfo = $request->get('storeAccountInfo');
        $idsDevices = eFunction::arrayInteger($request->get('ids_device'));
        $collections = $request->get('collections');
        $type_booking = $request->get('type_booking');
        $time_book = $request->get('time_book');
        $timeFrames = $request->get('timeframes');

        $isAdminStore = eFunction::isAdminStore($storeAccountInfo);
        $isAccountBooking = eFunction::isAccountBooking($storeAccountInfo);
        if (empty($isAdminStore) && empty($isAccountBooking)){
            return eResponse::response(STATUS_API_FALSE, __('notification.system.no-permission-to-create-ads'));
        }

        if ((int)$storeAccountInfo['current_point'] < (int)$data['payment']){
            return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-enough-point'));
        }

        if (empty($timeFrames) || empty($idsDevices) || empty($time_book) || !in_array((int)$type_booking, [Order::TYPE_BOOKING_SELECTED, Order::TYPE_BOOKING_ESTIMATE]) || ((int)$type_booking === Order::TYPE_BOOKING_SELECTED && empty($collections))){
            return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'), ['firstCheck' => true]);
        }

        $filter = [];

        $this->makeFilter($request, $filter);

        $checkErrorDateTime = $this->checkDatetime($request, $timeFrames);
        if (!empty($checkErrorDateTime)){
            return eResponse::responseError(STATUS_API_ERROR, __('notification.system.date-time-error'), $checkErrorDateTime);
        }

        $isCheckTimeFrames = $this->CheckingTimeFrames($timeFrames);
        if (!empty($isCheckTimeFrames)){
            return eResponse::response(STATUS_API_FALSE, __('notification.system.time-coincide'));
        }

        //Check lại filter để lấy thiết bị
        $idsDevicesChecking = $this->filterByArguments($filter, $storeAccountInfo);
        $idsDeviceFromTime = $this->filterByTime($filter, $storeAccountInfo);
        if (empty($idsDevicesChecking)){
            return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'), ['SecondCheck' => true]);
        }

        //Lấy ra những id của device còn lại khi bỏ đi những thiết bị không thể book nữa
        $idsDevicesChecking = array_diff($idsDevicesChecking, $idsDeviceFromTime);
        if (empty($idsDevicesChecking)){
            return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'), ['ThirdCheck' => true]);
        }

        $isCheckingDevice = array_diff($idsDevices, $idsDevicesChecking);//Check xem trông số thiết bị đặt có cái nào không có trong filter ra không

        $devices = $this->deviceRepository->getAllDeviceWithRankByFilter(['id' => $idsDevices, 'deleted_at' => true, 'project_id' => $storeAccountInfo['project_id']]);
        if (empty($devices) || count($idsDevices) !== count($devices) || !empty($isCheckingDevice)){
            return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'), ['FourCheck' => true]);
        }

        $totalTimes = eFunction::getTotalTimeInOrder($timeFrames);
        //Check điểm vào có bằng điểm mình xem không
        $payment = $this->checkPayment($data['payment'], $type_booking, $time_book, $collections, $totalTimes, $devices);
        if (empty($payment)){
            return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
        }

        $data['total_slot'] = !empty($collections) ? count($collections) : 0;
        $data['code'] = eFunction::generateRandomString(14);
        $data['status'] = Order::WAIT;
        $data['store_account_id'] = $storeAccountInfo['id'];
        $data['type_booking'] = $type_booking;
        $data['time_booked'] = $time_book;
        $data['current_slot'] = !empty($collections) ? count($collections) : 0;
        $data['current_time_booked'] = $time_book;
        eFunction::FillUpStore($storeAccountInfo, $data);

        if (!empty($isAccountBooking) && empty($isAdminStore)){
            $data['branch_id'] = $storeAccountInfo['branch_id'];
        }
        try{
            //Tạo đơn hàng
            $order = $this->orderRepository->create($data);
            if (!empty($order)){
                $arrOrderStore = $arrOrderBranch = $arrOrderDevice = $arrStoreCrossDeviceCollection = $arrTimeFrame = [];
                $devices = collect($devices)->keyBy('id')->toArray();

                //Lấy các đơn hàng đã trong khoảng thời gian đã chọn ra(nếu có)
                $arrDeviceWithTime = $xxx = [];
                $timeFrameBooked = $this->timeFrameRepository->getAllTimeFramesByFilter(['start_date' => $filter['start_date'], 'end_date' => $filter['end_date'], 'start_time' => $filter['start_time'], 'end_time' => $filter['end_time']]);
                if(!empty($timeFrameBooked)){
                    $idsOrders = collect($timeFrameBooked)->pluck('order_id')->filter()->unique()->values()->toArray();
                    $orders = $this->orderRepository->getAllOrderWithOrderDeviceByFilter(['id' => $idsOrders, 'deleted_at' => true, 'status' => [Order::CONFIRMED, Order::WAIT]]);
                    if (!empty($orders)){
                        foreach ($orders as $od){
                            if (!empty($od['devices'])){
                                foreach ($od['devices'] as $device){
                                    if (!empty($arrDeviceWithTime[$device['id']])){
                                        $arrDeviceWithTime[$device['id']] = $arrDeviceWithTime[$device['id']] + $od['time_booked'];
                                    }else{
                                        $arrDeviceWithTime[$device['id']] = $od['time_booked'];
                                    }
                                }
                            }
                        }
                    }
                }

                //Gán điển cho từng thiết bị
                foreach ($devices as $k => $device){
                    if (!empty($device['branch']['rank']['coefficient'])){
                        $devices[$k]['point'] = $this->getPointEachDevice($type_booking, $time_book, $collections, $totalTimes, (int)$device['branch']['rank']['coefficient']);
                        $devices[$k]['real_point'] = $this->getRealPointEachDevice($type_booking, $time_book, $collections, $totalTimes, $device, $arrDeviceWithTime);
                    }
                }
                //Thêm cửa hàng vào đơn hàng (order store)
                foreach ($devices as $device){
                    if (!empty($device['store_id'])){
                        if (!empty($arrOrderStore[$device['store_id']])){
                            $arrOrderStore[$device['store_id']]['point'] = $arrOrderStore[$device['store_id']]['point'] + $device['point'];
                            $arrOrderStore[$device['store_id']]['real_point'] = $arrOrderStore[$device['store_id']]['real_point'] + $device['real_point'];
                        }else{
                            $arrOrderStore[$device['store_id']] = [
                                'order_id' => $order->id,
                                'point' => $device['point'],
                                'real_point' => $device['real_point'],
                                'store_account_id' => $storeAccountInfo['id'],
                                'store_id' => (int)@$device['store_id'],
                                'project_id' => (int)$storeAccountInfo['project_id'],
                                'created_at' => eFunction::getDateTimeNow(),
                                'updated_at' => eFunction::getDateTimeNow()
                            ];
                        }
                    }
                }

                if (!empty($arrOrderStore)){
                    $arrOrderStore = array_values($arrOrderStore);
                    $this->orderStoreRepository->insertMulti($arrOrderStore);
                }

                //Lấy lại những cửa hàng đã thêm vào đơn hàng (order store) vừa thêm để thêm chi nhánh vào đơn hàng (order branch)
                $orderStores = $this->orderStoreRepository->getAllOrderStoreByFilter(['order_id' => $order->id, 'project_id' => (int)$storeAccountInfo['project_id'], 'status' => OrderStore::STATUS_USING]);
                if (!empty($orderStores)){
                    $orderStores = collect($orderStores)->keyBy('store_id')->toArray();

                    if (!empty($orderStores)){
                        foreach ($devices as $device){
                            if (!empty($device['store_id']) && !empty($device['branch_id'])){
                                if (!empty($arrOrderBranch[$device['branch_id']])){
                                    $arrOrderBranch[$device['branch_id']]['point'] = $arrOrderBranch[$device['branch_id']]['point'] + $device['point'];
                                    $arrOrderBranch[$device['branch_id']]['real_point'] = $arrOrderBranch[$device['branch_id']]['real_point'] + $device['real_point'];
                                }else{
                                    $arrOrderBranch[$device['branch_id']] = [
                                        'order_id' => $order->id,
                                        'rank' => @$device['branch']['rank']['name'] . ' (' . @$device['branch']['rank']['coefficient'] . ')',
                                        'rank_id' => @$device['branch']['rank']['id'],
                                        'store_account_id' => $storeAccountInfo['id'],
                                        'point' => $device['point'],
                                        'real_point' => $device['real_point'],
                                        'order_store_id' => !empty($orderStores[$device['store_id']]['id']) ? $orderStores[$device['store_id']]['id'] : null,
                                        'branch_id' => (int)@$device['branch_id'],
                                        'project_id' => (int)$storeAccountInfo['project_id'],
                                        'created_at' => eFunction::getDateTimeNow(),
                                        'updated_at' => eFunction::getDateTimeNow()
                                    ];
                                }
                            }
                        }

                        if (!empty($arrOrderBranch)){
                            $arrOrderBranch = array_values($arrOrderBranch);
                            $this->orderBranchRepository->insertMulti($arrOrderBranch);
                        }

                        //Lấy lại những chi nhánh đã thêm vào đơn hàng (order branch) vừa thêm để thêm thiết bị vào đơn hàng (order device)
                        $orderBranches = $this->orderBranchRepository->getAllOrderBranchByFilter(['order_id' => $order->id, 'project_id' => (int)$storeAccountInfo['project_id'], 'status' => OrderBranch::STATUS_USING]);
                        if (!empty($orderBranches)) {
                            $orderBranches = collect($orderBranches)->keyBy('branch_id')->toArray();
                            if (!empty($orderBranches)) {
                                foreach ($devices as $device){
                                    if (!empty($device['branch_id'])){
                                        if (!empty($arrOrderDevice[$device['id']])){
                                            $arrOrderDevice[$device['id']]['point'] = $arrOrderDevice[$device['id']]['point'] + $device['point'];
                                            $arrOrderDevice[$device['id']]['real_point'] = $arrOrderDevice[$device['id']]['real_point'] + $device['real_point'];
                                        }else{
                                            $arrOrderDevice[$device['id']] = [
                                                'order_id' => $order->id,
                                                'point' => $device['point'],
                                                'real_point' => $device['real_point'],
                                                'device_id' => $device['id'],
                                                'block_time' => (int)$device['block_ads'],
                                                'total_time_store' => (int)$device['total_time_store'],
                                                'total_time_admin' => (int)$device['total_time_admin'],
                                                'store_account_id' => $storeAccountInfo['id'],
                                                'order_store_id' => !empty($orderBranches[$device['branch_id']]['order_store_id']) ? $orderBranches[$device['branch_id']]['order_store_id'] : null,
                                                'order_branch_id' => !empty($orderBranches[$device['branch_id']]['id']) ? $orderBranches[$device['branch_id']]['id'] : null,
                                                'project_id' => (int)$storeAccountInfo['project_id'],
                                                'created_at' => eFunction::getDateTimeNow(),
                                                'updated_at' => eFunction::getDateTimeNow()
                                            ];
                                        }
                                    }
                                }

                                //Thêm thiết bị vào đơn hàng (order device)
                                if (!empty($arrOrderDevice)){
                                    $this->orderDeviceRepository->createMulti($arrOrderDevice);
                                }
                            }
                        }
                    }
                }

                //Thêm collection vào đơn hàng
                if (!empty($collections)) {
                    foreach ($collections as $collection) {
                        $arrStoreCrossDeviceCollection[] = [
                            'status' => StoreCrossDeviceCollection::COLLECTION_STATUS_WAIT,
                            'order_id' => (int)$order->id,
                            'collection_id' => (int)@$collection['collection_id'],
                            'volume' => (int)@$collection['volume'],
                            'project_id' => $storeAccountInfo['project_id'],
                            'store_id' => $storeAccountInfo['store_id'],
                            'store_account_id' => $storeAccountInfo['id'],
                            'position' => (int)@$collection['position'] + 1,
                            'second' => (int)@$collection['second'],
                            'type' => (int)@$collection['type'],
                            'created_at' => eFunction::getDateTimeNow(),
                            'updated_at' => eFunction::getDateTimeNow(),
                        ];
                    }
                }

                //Thêm mới bộ sưu tập cửa hàng chéo
                if (!empty($arrStoreCrossDeviceCollection)){
                    $this->storeCrossDeviceCollectionRepository->insertMulti($arrStoreCrossDeviceCollection);
                }
                //Kết thúc orderDevice

                //Bắt đầu khung thời gian
                foreach ($timeFrames as $time) {
                    $arrTimeFrame[] = [
                        'start_date' => @$time['start_date'],
                        'end_date' => @$time['end_date'],
                        'start_time' => @$time['start_time'],
                        'end_time' => @$time['end_time'],
                        'frequency' => @$time['frequency'],
                        'total' => @$time['total'],
                        'order_id' => (int)$order->id,
                        'project_id' => $storeAccountInfo['project_id'],
                        'created_at' => eFunction::getDateTimeNow(),
                        'updated_at' => eFunction::getDateTimeNow()
                    ];
                }
                //Lưu các khung thời gian
                if (!empty($arrTimeFrame)) {
                    $this->timeFrameRepository->createMulti($arrTimeFrame);
                }

                //Thêm hoạt đông trừ tiền của cửa hàng
                $activity = [
                    'type' => LogPoint::TYPE_MINUS_POINT,
                    'point' => $data['payment'],
                    'code' => $data['code'],
                    'transaction' => LogPoint::TRANSACTION['minus'],
                    'order_id' => (int)$order->id,
                    'store_id' => $storeAccountInfo['store_id'],
                    'branch_id' => $storeAccountInfo['branch_id'],
                    'store_account_id' => $storeAccountInfo['id'],
                    'project_id' => $storeAccountInfo['project_id'],
                ];

                $logPointMinus = $this->logPointRepository->create($activity);
                if (!empty($logPointMinus)){
                    //Lấy lại các khung thời gian đã thêm để thêm vào log điểm
                    $timeFrames = $this->timeFrameRepository->getAllTimeFramesByFilter(['order_id' => (int)$order->id]);
                    if (!empty($timeFrames)){
                        $arrTimeFrameLogPoints = [];
                        foreach ($timeFrames as $time){
                            $arrTimeFrameLogPoints[] = [
                                'log_point_id' => $logPointMinus->id,
                                'time_frame_id' => (int)$time['id'],
                                'order_id' => (int)$order->id,
                                'project_id' => $storeAccountInfo['project_id'],
                                'created_at' => eFunction::getDateTimeNow(),
                                'updated_at' => eFunction::getDateTimeNow()
                            ];
                        }

                        if (!empty($arrTimeFrameLogPoints)){
                            $this->timeFrameLogPointRepository->insertMulti($arrTimeFrameLogPoints);
                        }
                    }
                }

                //Cập nhật lại điểm cho store hoặc chi nhánh
                $params['current_point']['decrement'] = $data['payment'];
                if (!empty($isAdminStore)){
                    $this->storeRepository->changePointStoreByFilter(['id' => $storeAccountInfo['store_id'], 'deleted_at' => true], $params);
                }else{
                    $this->branchRepository->changePointBranchByFilter(['id' => $storeAccountInfo['store_id'], 'deleted_at' => true], $params);
                }

                $account = $this->accountRepository->getOneArrayAccountByFilter(['project_id' => $storeAccountInfo['project_id']]);
                if (!empty($account['email'])){
                    event(new SendEmailOrderEvent([
                        'email' => $account['email'],
                        'username' => $account['username'],
                        'title' => 'Đơn hàng mới',
                        'type' => Order::NEW_ORDER,
                        'order_code' => $order->code,
                        'url' => 'api/v1/admin/order/list?page=1&limit=10'
                    ]));
                }
            }

            $branchId = !empty($isAccountBooking) && empty($isAdminStore) ? $storeAccountInfo['branch_id'] : null;
            $activities = eFunction::getActivity($storeAccountInfo, null,  $data['code'], 'book_order', $branchId);
            if (!empty($activities)){
                $this->logOperationRepository->create($activities);
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-create-success'));

        }catch (\Exception $e){

            DB::rollback();
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function detail(DetailOrderRequest $request)
    {
        try{
            $filter = [];
            $storeAccountInfo = $request->get('storeAccountInfo');
            $this->makeFilter($request, $filter);
            $filter['project_id'] = $storeAccountInfo['project_id'];
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
        $storeAccountInfo = $request->get('storeAccountInfo');
        $this->makeFilterOrder($request, $filter);
        if (empty($filter['order_id']) || empty($filter['order_store_id'])){
            return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
        }

        $orderStore = $this->orderStoreRepository->getOneArrayOrderStoreByFilter(['order_id' => (int)$filter['order_id'], 'id' => (int)$filter['order_store_id'], 'project_id' => $storeAccountInfo['project_id']]);
        $params['totalPoints'] = @$orderStore['point'];
        $params['store'] = @$orderStore['store'];
        $params['branchCount'] = @$orderStore['branches_count'];

        if (!empty($filter['order_branch_id'])){
            $orderBranch = $this->orderBranchRepository->getOneArrayOrderBranchByFilter(['order_id' => (int)$filter['order_id'], 'id' => (int)$filter['order_branch_id'], 'order_store_id' => (int)$filter['order_store_id'], 'project_id' => $storeAccountInfo['project_id']]);
            $params['totalMedias'] = $this->storeCrossDeviceCollectionRepository->countAllStoreCrossDeviceCollectionByFilter(['order_id' => (int)$filter['order_id'], 'project_id' => $storeAccountInfo['project_id']]);
            $params['totalPoints'] = @$orderBranch['point'];
            $params['branch'] = @$orderBranch['branch'];
            $params['deviceCount'] = @$orderBranch['devices_count'];
        }

        return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $params);
    }

    public function listStoreInDetail(ListStorePaginationRequest $request)
    {
        try{
            $filter = [];
            $limit = $request->limit();
            $storeAccountInfo = $request->get('storeAccountInfo');
            $this->makeFilterOrder($request, $filter);
            $filter['project_id'] = $storeAccountInfo['project_id'];

            if (!empty($filter['key_word'])){
                $stores = $this->storeRepository->getAllStoreByFilter(['key_word' => $filter['key_word'], 'project_id' => $storeAccountInfo['project_id']]);
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

    public function listBranchInDetail(ListBranchPaginationRequest $request)
    {
        try{
            $filter = [];
            $limit = $request->limit();
            $this->makeFilterOrder($request, $filter);
            $storeAccountInfo = $request->get('storeAccountInfo');
            $filter['project_id'] = $storeAccountInfo['project_id'];

            if (!empty($filter['key_word'])){
                $branches = $this->branchRepository->getAllBranchForStoreByFilter(['key_word' => $filter['key_word'], 'project_id' => $storeAccountInfo['project_id']]);
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
            $storeAccountInfo = $request->get('storeAccountInfo');
            $filter['project_id'] = $storeAccountInfo['project_id'];

            if (!empty($filter['key_word'])){
                $collections = $this->collectionRepository->getAllCollectionByFilter(['key_word' => $filter['key_word'], 'project_id' => $storeAccountInfo['project_id']]);
                if (empty($collections)){
                    return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), []);
                }

                $filterCollection['collection_id'] = collect($collections)->pluck('id')->values()->toArray();
            }

            $orderBranch = $this->orderBranchRepository->getOneArrayOrderBranchByFilter(['order_id' => (int)$filter['order_id'], 'id' => (int)$filter['order_branch_id'], 'order_store_id' => (int)$filter['order_store_id'], 'project_id' => $storeAccountInfo['project_id']]);
            $order = $this->orderRepository->getOneArrayOrderByFilter(['id' => $filter['order_id'], 'project_id' => $storeAccountInfo['project_id'], 'deleted_at' => true]);

            if (empty($orderBranch) || empty($order) || empty($order['time_frames'])){
                return eResponse::responsePagination(STATUS_API_FALSE, __('notification.system.data-not-found'));
            }
            $totalTimes = eFunction::getTotalTimeInOrder($order['time_frames']);

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

    public function listDeviceInDetail(ListDevicePaginationRequest $request)
    {
        try{
            $filter = [];
            $limit = $request->limit();
            $this->makeFilterOrder($request, $filter);
            $storeAccountInfo = $request->get('storeAccountInfo');
            $filter['project_id'] = $storeAccountInfo['project_id'];
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

    public function listCollectionOrder(ListCollectionOrderRequest $request)
    {
        $orderId = $request->get('order_id');
        $keyWord = $request->get('key_word');
        $storeAccountInfo = $request->get('storeAccountInfo');
        $order = $this->orderRepository->getOneArrayOrderByFilter(['id' => (int)$orderId]);
        if (!empty($keyWord)){
            $collections = $this->collectionRepository->getAllCollectionByFilter(['key_word' => $keyWord, 'project_id' => $storeAccountInfo['project_id']]);
            if (empty($collections)){
                return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), []);
            }

            $filterCollection['collection_id'] = collect($collections)->pluck('id')->values()->toArray();
        }

        $filterCollection['order_id'] = (int)$orderId;
        $filterCollection['status'] = [StoreCrossDeviceCollection::COLLECTION_STATUS_WAIT, StoreCrossDeviceCollection::COLLECTION_STATUS_CONFIRMED];
        $filterCollection['project_id'] = $storeAccountInfo['project_id'];

        $collections = $this->storeCrossDeviceCollectionRepository->getAllStoreCrossDeviceCollectionByFilter($filterCollection);
        if (!empty($collections) && !empty($order)){
            foreach ($collections as $k => $collection){
                if (!empty($collection['collection']['source'])){
                    $collections[$k]['source'] = asset($collection['collection']['source']);
                }

                if (!empty($collection['collection']['source_thumb'])){
                    $collections[$k]['source_thumb'] = asset($collection['collection']['source_thumb']);
                }

            }
        }

        $params = [
            'collections' => $collections,
            'current_slot' => @$order['current_slot'],
            'current_time_booked' => @$order['current_time_booked'],
            'total_slot' => @$order['total_slot'],
            'time_booked' => @$order['time_booked'],
            'time_frames' => @$order['time_frames']
        ];

        return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $params);
    }

    public function updateCollectionOrder(UpdateCollectionOrderRequest $request)
    {
        $orderId = $request->get('order_id');

        try{

            DB::beginTransaction();
            $collections = $request->get('collections');
            $storeAccountInfo = $request->get('storeAccountInfo');
            $isAdminStore = eFunction::isAdminStore($storeAccountInfo);
            $order = $this->orderRepository->getOneObjectOrderByFilter(['id' => $orderId, 'project_id' => $storeAccountInfo['project_id'], 'deleted_at' => true]);
            $timeFrames = $this->timeFrameRepository->getAllTimeFramesByFilter(['order_id' => (int)$orderId]);

            if (empty($order) || empty($timeFrames)){
                return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
            }

            $isCheckTimeFrames = $this->CheckingTimeFrames($timeFrames);
            if (!empty($isCheckTimeFrames)){
                return eResponse::response(STATUS_API_FALSE, __('notification.system.time-coincide'));
            }

            if (in_array($order->status, [Order::COMPLETED, Order::DECLINED, Order::CANCELED])){
                return eResponse::response(STATUS_API_FALSE, __('notification.system.order-done'));
            }

            //Nếu mà không có dữ liệu gửi vào => collection bị xóa hết
            if (!empty($collections)){
                $totalSecond = collect($collections)->sum('second');
                if ((int)$order->time_booked < (int)$totalSecond || ((int)$order->type_booking === Order::TYPE_BOOKING_SELECTED && (int)$order->total_slot < count($collections))){
                    return eResponse::response(STATUS_API_FALSE, __('notification.system.time-limit-exceeded'));
                }

                $idsCollection = collect($collections)->pluck('collection_id')->values()->toArray();
                if (!empty($idsCollection)){
                    $filterCollection = [
                        'id' => $idsCollection,
                        'project_id' => $storeAccountInfo['project_id'],
                        'store_id' => $storeAccountInfo['store_id'],
                        'deleted_at' => true
                    ];

                    if (empty($isAdminStore)){
                        $filterCollection['store_account_id'] = $storeAccountInfo['id'];
                    }

                    $CheckingCollection = $this->collectionRepository->getAllCollectionByFilter($filterCollection);
                    if (empty($CheckingCollection) || count($CheckingCollection) !== count($idsCollection)){
                        return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
                    }

                    $userInstance = new StoreCrossDeviceCollection();
                    $index = 'id';

                    $arrAddCollection = $arrOldCollections = $arrIdsOldCollections = [];
                    $storeCrossCollections = $this->storeCrossDeviceCollectionRepository->getAllStoreCrossDeviceCollectionByFilter(['order_id' => (int)$orderId, 'status' => [StoreCrossDeviceCollection::COLLECTION_STATUS_WAIT, StoreCrossDeviceCollection::COLLECTION_STATUS_CONFIRMED]]);
                    if (!empty($storeCrossCollections)){
                        $storeCrossCollections = collect($storeCrossCollections)->keyBy('id')->toArray();
                    }
                    foreach ($collections as $collection){
                        if (!empty($collection['collection_id']) && isset($collection['position']) && !empty($collection['second']) && !empty($collection['type'])){
                            if (empty($collection['store_cross_device_collection_id'])){
                                $arrAddCollection[] = [
                                    'order_id' => (int)$orderId,
                                    'status' => StoreCrossDeviceCollection::COLLECTION_STATUS_WAIT,
                                    'collection_id' => (int)@$collection['collection_id'],
                                    'project_id' => $storeAccountInfo['project_id'],
                                    'store_id' => $storeAccountInfo['store_id'],
                                    'volume' => in_array((int)@$collection['volume'], [StoreDeviceCollection::ENABLE_VOLUME, StoreDeviceCollection::DISABLE_VOLUME]) ? (int)@$collection['volume'] : StoreDeviceCollection::ENABLE_VOLUME,
                                    'store_account_id' => $storeAccountInfo['id'],
                                    'position' => (int)@$collection['position'] + 1,
                                    'second' => (int)@$collection['second'],
                                    'type' => (int)@$collection['type'],
                                    'created_at' => eFunction::getDateTimeNow(),
                                    'updated_at' => eFunction::getDateTimeNow(),
                                ];
                            }else{
                                //Check những collection cũ nếu thay đổi thời gian hoặc trạng thái tắt âm/bật âm với video thì mới cập nhật lại
                                $arrIdsOldCollections[] = $collection['store_cross_device_collection_id'];
                                if (!empty($storeCrossCollections[$collection['store_cross_device_collection_id']]) && ((int)$storeCrossCollections[$collection['store_cross_device_collection_id']]['volume'] !== @(int)$collection['volume'] || $storeCrossCollections[$collection['store_cross_device_collection_id']]['second'] !== @(int)$collection['second'])){
                                    $arrOldCollections[] = [
                                        'id' => (int)$collection['store_cross_device_collection_id'],
                                        'status' => StoreCrossDeviceCollection::COLLECTION_STATUS_WAIT,
                                        'volume' => in_array((int)@$collection['volume'], [StoreDeviceCollection::ENABLE_VOLUME, StoreDeviceCollection::DISABLE_VOLUME]) ? (int)@$collection['volume'] : StoreDeviceCollection::ENABLE_VOLUME,
                                        'position' => (int)@$collection['position'] + 1,
                                        'second' => (int)@$collection['second'],
                                    ];
                                }
                            }
                        }
                    }

                    if (!empty($arrOldCollections)){
                        //Cập nhật lại collection còn lại
                        \Batch::update($userInstance, $arrOldCollections, $index);
                    }

                    if(!empty($arrIdsOldCollections)){
                        if (!empty($storeCrossCollections)){
                            $idsOldDeviceCollection = collect($storeCrossCollections)->pluck('id')->values()->toArray();
                            $idsDeviceCollectionDelete = array_diff($idsOldDeviceCollection, $arrIdsOldCollections);
                            if (!empty($idsDeviceCollectionDelete)){
                                $this->storeCrossDeviceCollectionRepository->deleteAllStoreCrossDeviceCollectionByFilter(['id' => $idsDeviceCollectionDelete, 'project_id' => $storeAccountInfo['project_id']]);
                            }
                        }
                    }else{
                        $this->storeCrossDeviceCollectionRepository->deleteAllStoreCrossDeviceCollectionByFilter(['order_id' => (int)$orderId, 'project_id' => $storeAccountInfo['project_id']]);
                    }

                    if (!empty($arrAddCollection)){
                        $this->storeCrossDeviceCollectionRepository->insertMulti($arrAddCollection);
                    }

                    $this->orderRepository->update($order, ['current_time_booked' => $totalSecond, 'current_slot' => count($collections)]);

                    $orderDevices = $this->orderDeviceRepository->getAllOrderDeviceByFilter(['order_id' => (int)$orderId, 'status' => OrderDevice::STATUS_USING]);
                    if (!empty($orderDevices)){
                        $idsDevices = collect($orderDevices)->pluck('device_id')->unique()->filter()->values()->toArray();
                        if (!empty($idsDevices)){
                            $devices = $this->deviceRepository->getAllDeviceByFilter(['id' => $idsDevices, 'deleted_at' => true]);
                            if(!empty($devices)){
                                $arrActivities = [];
                                foreach ($devices as $device){
                                    $arrActivities[] = eFunction::getActivity($storeAccountInfo, @$device['id'], @$device['name'], 'update_media_store_cross', null);
                                }

                                if (!empty($arrActivities)){
                                    $this->logOperationRepository->insertMulti($arrActivities);
                                }
                            }
                        }
                    }

                }
            }else{
                //Lấy các collection cũ ra
                $StoreCrossCollections = $this->storeCrossDeviceCollectionRepository->getAllStoreCrossDeviceCollectionByFilter(['order_id' => (int)$orderId, 'project_id' => $storeAccountInfo['project_id']]);
                if(!empty($StoreCrossCollections)){
                    $this->orderRepository->update($order, ['current_time_booked' => 0, 'current_slot' => 0]);
                    $this->storeCrossDeviceCollectionRepository->deleteAllStoreCrossDeviceCollectionByFilter(['order_id' => (int)$orderId, 'project_id' => $storeAccountInfo['project_id']]);
                }
            }


            $orderDevices = $this->orderDeviceRepository->getAllOrderDeviceForQueueByFilter(['order_id' => (int)$orderId, 'status' => OrderDevice::STATUS_USING]);
            if (!empty($orderDevices)){
                foreach ($orderDevices as $orderDevice){
                    if (!empty($orderDevice['device']['device_code']) && isset($orderDevice['device']['is_active']) && (int)$orderDevice['device']['is_active'] === Device::IS_ACTIVE && isset($orderDevice['device']['status']) && (int)$orderDevice['device']['status'] === Device::CONNECT){
                        $params = [
                            'event' => Device::EVENT_CHANGE_MEDIA,
                            'message' => 'Sửa media trong đơn hàng (ADMIN)',
                            'data' => $this->commonService->getDataFromDeviceId((int)$orderDevice['device_id'])
                        ];

                        eFunction::sendMessageQueue($params, $orderDevice['device']['device_code']);
                    }
                }
            }

            $activities = eFunction::getActivity($storeAccountInfo, null,  @$order->code, 'update_book_order', @$order->branch);
            if (!empty($activities)){
                $this->logOperationRepository->create($activities);
            }

            $account = $this->accountRepository->getOneArrayAccountByFilter(['project_id' => $storeAccountInfo['project_id']]);
            if (!empty($account['email'])){
                event(new SendEmailOrderEvent([
                    'email' => $account['email'],
                    'username' => $account['username'],
                    'title' => 'Media Đã thay đổi',
                    'type' => Order::CHANGE_ORDER,
                    'order_code' => $order->code,
                    'url' => 'api/v1/admin/order/list?page=1&limit=10'
                ]));
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-update-success'));

        }catch (\Exception $e){
            DB::rollback();
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    private function CheckingTimeFrames($timeFrames)
    {
        $today = strtotime('today');
        foreach ($timeFrames as $time){
            if ($today >= strtotime($time['start_date']) || $today >= strtotime($time['end_date'])){
                return true;
            }
        }

        return false;
    }

    private function filterByArguments($filter, $storeAccountInfo)
    {
        $filterBranch = $idsBranches = $params = [];
        $time_book = !empty($filter['time_book']) ? (int)$filter['time_book'] : 0;
        if (!empty($filter['rank_id'])){
            $filterBranch['rank_id'] = (int)$filter['rank_id'];
        }

        if (!empty($filter['province_id'])) {
            $filterBranch['province_id'] = (int)$filter['province_id'];
        }

        if (!empty($filter['district_id'])) {
            $filterBranch['district_id'] = (int)$filter['district_id'];
        }

        $filterBranch['project_id'] = $storeAccountInfo['project_id'];
        $filterBranch['deleted_at'] = true;
        $branches = $this->branchRepository->getAllBranchForStoreByFilter($filterBranch);
        if (!empty($branches)){
            $ids = collect($branches)->pluck('id')->toArray();
            if (!empty($filter['sub_brand_id'])){
                $branchSubBrands = $this->branchSubBrandRepository->getAllBranchSubBrandByFilterOrder(['sub_brand_id' => $filter['sub_brand_id'], 'project_id' => $storeAccountInfo['project_id']]);
                if (!empty($branchSubBrands)){
                    $idsBranches = collect($branchSubBrands)->pluck('branch_id')->filter()->unique()->values()->toArray();
                }
            }

            if (!empty($idsBranches)){//Lấy giao của 2 thằng
                $idsBranches = array_values(array_intersect($idsBranches, $ids));
            }else{
                $idsBranches = $ids;
            }
        }

        //Bỏ các chi nhánh của cửa hàng hiện tại (Và các chi nhánh trùng nhãn với chi nhánh hiện tại)
        $thisBranchesForStore = $this->branchRepository->getAllBranchForStoreByFilter(['store_id' => $storeAccountInfo['store_id'], 'project_id' => $storeAccountInfo['project_id'], 'deleted_at' => true]);
        if (!empty($thisBranchesForStore)){
            $IdsThisBranchesForStores = collect($thisBranchesForStore)->pluck('id')->toArray();
            $idsBranches = array_diff($idsBranches, $IdsThisBranchesForStores);


            $thisBranchSubBrands = $this->branchSubBrandRepository->getAllBranchSubBrandByFilterOrder(['branch_id' => $IdsThisBranchesForStores, 'project_id' => $storeAccountInfo['project_id']]);
            if (!empty($thisBranchSubBrands)){
                $idsSubBrands = collect($thisBranchSubBrands)->pluck('sub_brand_id')->filter()->unique()->values()->toArray();
                $branchSubBrands = $this->branchSubBrandRepository->getAllBranchSubBrandByFilterOrder(['sub_brand_id' => $idsSubBrands, 'project_id' => $storeAccountInfo['project_id']]);
                if (!empty($branchSubBrands)){
                    $idsThisBranches = collect($branchSubBrands)->pluck('branch_id')->filter()->unique()->values()->toArray();
                    $idsBranches = array_diff($idsBranches, $idsThisBranches);
                }
            }
        }

        //Bỏ các chi nhánh trùng nhãn với cửa hàng hiện tại
        $thisStoreSubBrands = $this->storeSubBrandRepository->getAllStoreSubBrandByFilter(['store_id' => $storeAccountInfo['store_id'], 'project_id' => $storeAccountInfo['project_id']]);
        if (!empty($thisStoreSubBrands)){
            $idsSubBrands = collect($thisStoreSubBrands)->pluck('sub_brand_id')->filter()->unique()->values()->toArray();
            $branchSubBrands = $this->branchSubBrandRepository->getAllBranchSubBrandByFilterOrder(['sub_brand_id' => $idsSubBrands, 'project_id' => $storeAccountInfo['project_id']]);
            if (!empty($branchSubBrands)){
                $idsThisBranches = collect($branchSubBrands)->pluck('branch_id')->filter()->unique()->values()->toArray();
                $idsBranches = array_diff($idsBranches, $idsThisBranches);
            }
        }

        //Các cửa hàng đã tìm kiếm theo
        if (!empty($filter['store_id'])) {
            $branchesForStoreSelected = $this->branchRepository->getAllBranchForStoreByFilter(['store_id' => $filter['store_id'], 'project_id' => $storeAccountInfo['project_id'], 'deleted_at' => true]);
            if (!empty($branchesForStoreSelected)){
                $IdsBranchesForStoreSelected = collect($branchesForStoreSelected)->pluck('id')->toArray();
                $idsBranches = array_values(array_intersect($idsBranches, $IdsBranchesForStoreSelected));
            }
        }

        if (!empty($idsBranches)){
            $devices = $this->deviceRepository->getAllDeviceByFilter(['branch_id' => $idsBranches, 'deleted_at' => true, 'is_active' => Device::IS_ACTIVE, 'project_id' => $storeAccountInfo['project_id'], 'total_time_empty' => $time_book]);
            if (!empty($devices)){
                $devicesConnect = eFunction::getListDeviceConnect();
                foreach ($devices as $device){
//                    if (!empty($device['device_code']) && in_array($device['device_code'], $devicesConnect)){
                        if ((int)$device['total_time_store'] < (int)$device['block_ads']){
                            $actualFreeTime = (int)$device['total_time_empty'] - ((int)$device['block_ads'] < (int)$device['total_time_store']);
                            if ($actualFreeTime >= $time_book){
                                $params[] = (int)$device['id'];
                            }
                        }else if ((int)$device['total_time_store'] >= (int)$device['block_ads']){
                            $params[] = (int)$device['id'];
                        }
//                    }
                }
            }
        }

        return $params;

    }

    private function filterByTime($filter, $storeAccountInfo)
    {
        //Đoạn này để check các thiết bị đã book trong thời gian đã chọn rồi
        $filterTimeFrame = $params = [];
        $time_book = !empty($filter['time_book']) ? (int)$filter['time_book'] : 0;
        if (!empty($filter['start_date'])){
            $filterTimeFrame['start_date'] = $filter['start_date'];
        }

        if (!empty($filter['end_date'])){
            $filterTimeFrame['end_date'] = $filter['end_date'];
        }

        if (!empty($filter['start_time'])){
            $filterTimeFrame['start_time'] = $filter['start_time'];
        }

        if (!empty($filter['end_time'])){
            $filterTimeFrame['end_time'] = $filter['end_time'];
        }
        $filterTimeFrame['project_id'] = $storeAccountInfo['project_id'];

        $timeFrames = $this->timeFrameRepository->getAllTimeFramesByFilter($filterTimeFrame);
        if (!empty($timeFrames)){
            //Lấy ra các order_device_id
            $idsOrders = collect($timeFrames)->pluck('order_id')->filter()->unique()->values()->toArray();
            if (!empty($idsOrders)){
                $orders = $this->orderRepository->getAllOrderByFilter(['id' => $idsOrders, 'status' => [Order::WAIT, Order::CONFIRMED]]);
                if (!empty($orders)){
                    $idsOrders = collect($orders)->pluck('id')->values()->toArray();
                    //Lấy ra order_device để lấy ra các thiết bị
                    $orderDevices = $this->orderDeviceRepository->getAllOrderDeviceWithOrderByFilter(['order_id' => $idsOrders, 'status' => OrderDevice::STATUS_USING, 'project_id' => $storeAccountInfo['project_id']]);
                    if (!empty($orderDevices)){
                        $idsDevices = collect($orderDevices)->pluck('device_id')->filter()->unique()->values()->toArray();
                        if (!empty($idsDevices)){
                            $devices = $this->deviceRepository->getAllDeviceByFilter(['id' => $idsDevices, 'deleted_at' => true, 'project_id' => $storeAccountInfo['project_id']]);
                            if (!empty($devices)){
                                $add = $del = [];
                                foreach ($devices as $device){
                                    $time_book_in_order = 0;
                                    foreach ($orderDevices as $orderDevice){
                                        if ((int)$orderDevice['device_id'] === (int)$device['id']){
                                            $time_book_in_order = $time_book_in_order + (int)$orderDevice['order']['time_booked'];
                                        }
                                    }

                                    if (((int)$device['total_time_empty'] - $time_book_in_order) <= $time_book){//Nếu mà thời gian còn trống nhỏ hơn thời gian book thì không cho book tiếp
                                        array_push($add, (int)$device['id']);
                                    }else{
                                        array_push($del, (int)$device['id']);
                                    }
                                }

                                $params = array_diff($add, $del);
                            }
                        }
                    }
                }
            }
        }

        //trả về những thiết bị không thể book nữa
        return $params;
    }

    private function getTimeDeviceBookedInOrder($ids, $filter, $storeAccountInfo)
    {
        $listDeviceWithBooked = $filterTimeFrame = [];
        if (!empty($filter['start_date'])){
            $filterTimeFrame['start_date'] = $filter['start_date'];
        }

        if (!empty($filter['end_date'])){
            $filterTimeFrame['end_date'] = $filter['end_date'];
        }

        if (!empty($filter['start_time'])){
            $filterTimeFrame['start_time'] = $filter['start_time'];
        }

        if (!empty($filter['end_time'])){
            $filterTimeFrame['end_time'] = $filter['end_time'];
        }
        $filterTimeFrame['project_id'] = $storeAccountInfo['project_id'];

        $timeFrames = $this->timeFrameRepository->getAllTimeFramesByFilter($filterTimeFrame);
        if (!empty($timeFrames)) {
            //Lấy ra các order_device_id
            $idsOrders = collect($timeFrames)->pluck('order_id')->filter()->unique()->values()->toArray();
            if (!empty($idsOrders)){
                $orders = $this->orderRepository->getAllOrderByFilter(['id' => $idsOrders, 'deleted_at' => true, 'status' => [Order::WAIT, Order::CONFIRMED]]);
                if (!empty($orders)){
                    $idsOrders = collect($orders)->pluck('id')->values()->toArray();
                    $orderDevices = $this->orderDeviceRepository->getAllOrderDeviceByFilter(['device_id' => $ids, 'order_id' => $idsOrders, 'status' => OrderDevice::STATUS_USING, 'project_id' => $storeAccountInfo['project_id']]);
                    if (!empty($orderDevices)){
                        $orders = collect($orders)->keyBy('id')->toArray();
                        foreach ($orderDevices as $orderDevice){
                            if (!empty($orderDevice['order_id']) && !empty($orderDevice['device_id']) && !empty($orders[$orderDevice['order_id']]) && !empty($orders[$orderDevice['order_id']]['time_booked'])){
                                if (empty($listDeviceWithBooked[$orderDevice['device_id']])){
                                    $listDeviceWithBooked[$orderDevice['device_id']] = (int)$orders[$orderDevice['order_id']]['time_booked'];
                                }else{
                                    $listDeviceWithBooked[$orderDevice['device_id']] = $listDeviceWithBooked[$orderDevice['device_id']] + (int)$orders[$orderDevice['order_id']]['time_booked'];
                                }
                            }
                        }
                    }
                }
            }
        }

        return $listDeviceWithBooked;
    }

    private function checkDatetime($request, $timeFrames)
    {
        $arrError = $arrListTime = [];
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $startTime = $request->get('start_time');
        $endTime = $request->get('end_time');

        if (strtotime($startDate) > strtotime($endDate) || strtotime($startTime) > strtotime($endTime) || (strtotime($startDate) === strtotime($endDate) && strtotime($startTime) === strtotime($endTime))){
            return [
                'start_date' =>  __('notification.system.selected-time-not-suitable'),
                'end_date' =>  __('notification.system.selected-time-not-suitable'),
                'start_time' =>  __('notification.system.selected-time-not-suitable'),
                'end_time' =>  __('notification.system.selected-time-not-suitable'),
                'index' => 0
            ];
        }

        foreach ($timeFrames as $key => $time){
            if (!empty($time['start_time']) && !empty($time['end_time']) && !empty($time['start_date']) && !empty($time['end_date'])){
                $start = new DateTime($time['start_date']);
                $end = new DateTime($time['end_date']);

                $end = $end->modify( '+1 day');

                $intervalOne = DateInterval::createFromDateString('1 day');
                $periodOne = new DatePeriod($start, $intervalOne, $end);
                foreach ($periodOne as $dt) {
                    $arrListTime[] = [
                        'start' => $dt->format("Y-m-d") . ' ' . $time['start_time'],
                        'end' =>  $dt->format("Y-m-d") . ' ' . $time['end_time'],
                        'index' => $key
                    ];
                }
            }
        }

        if (empty($arrListTime)){
            return [
                'start_date' =>  __('notification.system.selected-time-not-suitable'),
                'end_date' =>  __('notification.system.selected-time-not-suitable'),
                'start_time' =>  __('notification.system.selected-time-not-suitable'),
                'end_time' =>  __('notification.system.selected-time-not-suitable'),
                'index' => 0
            ];
        }

        foreach ($arrListTime as $key => $time){
            foreach ($arrListTime as $k => $t){
                if ($key !== $k){
                    if ((strtotime($time['start']) <= strtotime($t['start']) && strtotime($time['end']) > strtotime($t['start']) && strtotime($time['end']) <= strtotime($t['end']))
                        || (strtotime($time['start']) >= strtotime($t['start']) && strtotime($time['start']) < strtotime($t['end']) && strtotime($time['end']) >= strtotime($t['end']))
                        || (strtotime($time['start']) <= strtotime($t['start']) && strtotime($time['end']) >= strtotime($t['end']))
                        || (strtotime($time['start']) >= strtotime($t['start']) && strtotime($time['end']) <= strtotime($t['end']))){
                        $arrError = [
                            'start_date' =>  __('notification.system.selected-time-not-suitable'),
                            'end_date' =>  __('notification.system.selected-time-not-suitable'),
                            'start_time' =>  __('notification.system.selected-time-not-suitable'),
                            'end_time' =>  __('notification.system.selected-time-not-suitable'),
                            'index' => (int)$time['index'],
                            'k' => $k + 1,
                            'type' => 2
                        ];
                    }
                }
            }
        }

        return $arrError;
    }

    private function checkPayment($paymentInput, $type_booking, $time_book, $collections, $totalTimes, $devices)
    {
        $payment = 0;
        if ((int)$type_booking === Order::TYPE_BOOKING_SELECTED){
            foreach ($collections as $collection){
                $moneyCollection = eFunction::getPointCollectionAdmin((int)$collection['type'], (int)$collection['second'], $totalTimes, $devices);
                $payment = $payment + $moneyCollection;
            }
        }else{
            $payment = eFunction::getPointCollectionEstimateAdmin((int)$time_book, $totalTimes, $devices);
        }

        $payment = round($payment, 1);

        if ((int)$payment === (int)$paymentInput){
            return true;
        }

        return false;
    }

    private function getPointEachDevice($type_booking, $time_book, $collections, $totalTimes, $coefficient)
    {
        $point = 0;
        if ((int)$type_booking === Order::TYPE_BOOKING_SELECTED){
            foreach ($collections as $collection){
                $pointCollection = $this->getPointCollection((int)$collection['type'], (int)$collection['second'], $totalTimes, $coefficient);
                $point = $point + $pointCollection;
            }
        }else{
            $point = $this->getPointEstimate((int)$time_book, $totalTimes, $coefficient);
        }
        return $point;
    }

    private function getRealPointEachDevice($type_booking, $time_book, $collections, $totalTimes, $device, $arrDeviceWithTime)
    {
        $point = 0;
        $timeEmptyInStore = $device['total_time_store'] > $device['block_ads'] ? Device::MAX_TIME_STORE - $device['total_time_store'] : Device::MAX_TIME_STORE - $device['block_ads'];
        if (!empty($arrDeviceWithTime[$device['id']])){
            $timeEmptyInStore = $timeEmptyInStore - $arrDeviceWithTime[$device['id']];
        }

        if ((int)$type_booking === Order::TYPE_BOOKING_SELECTED){
            $timeToPoint = 0;
            foreach ($collections as $collection){
                if($timeToPoint < $timeEmptyInStore){
                    $pointCollection = $this->getPointCollection((int)$collection['type'], (int)$collection['second'], $totalTimes, (int)$device['branch']['rank']['coefficient']);
                    $point = $point + $pointCollection;
                    $timeToPoint = $timeToPoint + (int)$collection['second'];
                }
            }
        }else{
            $time_book = $time_book > $timeEmptyInStore ? $timeEmptyInStore : $time_book;//Nếu thời gian đặt mà lớn hơn thời gian trống thì chỉ tính tiền thời gian trống không thì tính tiền thời gian đặt
            $point = $this->getPointEstimate((int)$time_book, $totalTimes, (int)$device['branch']['rank']['coefficient']);
        }

        return $point;
    }

    public static function getPointCollection($type, $second, $totalTimes, $coefficient)
    {
        $valuePoint = 0;
        if ((int)$type === Collection::IMAGE){
            if ((int)$second === Device::POINT_TEN){
                $valuePoint = 0.5;
            }else if ((int)$second === Device::POINT_FIFTEEN){
                $valuePoint = 0.7;
            }else if ((int)$second === Device::POINT_THIRTY){
                $valuePoint = 1;
            }
        }else if ((int)$type === Collection::VIDEO){
            $naturalPart = $second/Device::POINT_THIRTY;
            $theRemainder = $second%Device::POINT_THIRTY;
            $valuePoint = (int)$naturalPart;

            if ((int)$theRemainder > 0 && (int)$theRemainder <= Device::POINT_TEN){
                $valuePoint = $valuePoint + 0.5;
            }else if ((int)$theRemainder > Device::POINT_TEN && (int)$theRemainder <= Device::POINT_FIFTEEN){
                $valuePoint = $valuePoint + 0.7;
            }else if ((int)$theRemainder > Device::POINT_FIFTEEN && (int)$theRemainder <= Device::POINT_THIRTY){
                $valuePoint = $valuePoint + 1;
            }
        }

        return $totalTimes*(int)$coefficient*$valuePoint;
    }

    public static function getPointEstimate($second, $totalTimes, $coefficient)
    {
        $naturalPart = $second/Device::POINT_TEN;
        $theRemainder = $second%Device::POINT_TEN;

        if ((int)$theRemainder !== 0){
            $point = $totalTimes*(int)$coefficient*(((int)$naturalPart + 1)*0.5);
        }else{
            $point = $totalTimes*(int)$coefficient*((int)$naturalPart*0.5);
        }

        return $point;
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

        if ($request->has('start_date')) {
            $filter['start_date'] = $request->get('start_date');
        }

        if ($request->has('end_date')) {
            $filter['end_date'] = $request->get('end_date');
        }

        if ($request->has('start_time')) {
            $filter['start_time'] = $request->get('start_time');
        }

        if ($request->has('end_time')) {
            if (strtotime('2012-05-12 ' . date('H:i:s')) === strtotime('2012-05-12 00:00:00')){
                $filter['end_time'] = '23:59:59';
            }else{
                $filter['end_time'] = $request->get('end_time');
            }
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
