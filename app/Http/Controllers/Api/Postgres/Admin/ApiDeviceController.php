<?php

namespace App\Http\Controllers\Api\Postgres\Admin;

use App\Elibs\eCrypt;
use App\Elibs\eFunction;
use App\Events\SendEmailDeviceEvent;
use App\Http\Controllers\Controller;
use App\Elibs\eResponse;
use App\Http\Requests\Api\Postgres\Admin\Device\ChangeStatusDeviceRequest;
use App\Http\Requests\Api\Postgres\Admin\Device\CreateCollectionDeviceRequest;
use App\Http\Requests\Api\Postgres\Admin\Device\CreateDeviceRequest;
use App\Http\Requests\Api\Postgres\Admin\Device\DeleteCollectionDeviceRequest;
use App\Http\Requests\Api\Postgres\Admin\Device\DeleteDeviceRequest;
use App\Http\Requests\Api\Postgres\Admin\Device\DetailDeviceRequest;
use App\Http\Requests\Api\Postgres\Admin\Device\UpdateCollectionDeviceRequest;
use App\Http\Requests\Api\Postgres\Admin\Device\UpdateDeviceRequest;
use App\Http\Requests\Api\Postgres\Admin\Device\DetailCollectionDeviceRequest;
use App\Models\Postgres\Admin\Store;
use App\Models\Postgres\Store\Order;
use App\Models\Postgres\Store\OrderBranch;
use App\Models\Postgres\Store\OrderDevice;
use App\Models\Postgres\Store\OrderStore;
use App\Models\Postgres\Store\StoreCrossDeviceCollection;
use App\Repositories\Postgres\Admin\AccountRepositoryInterface;
use App\Repositories\Postgres\Admin\AdminDeviceImageRepositoryInterface;
use App\Repositories\Postgres\Admin\DeviceRepositoryInterface;
use App\Repositories\Postgres\Admin\ImageRepositoryInterface;
use App\Http\Requests\Api\PaginationRequest;
use App\Models\Postgres\Admin\AdminDeviceImage;
use App\Models\Postgres\Admin\Device;
use App\Repositories\Postgres\Admin\StoreRepositoryInterface;
use App\Repositories\Postgres\Store\OrderDeviceRepositoryInterface;
use App\Repositories\Postgres\Store\OrderRepositoryInterface;
use App\Repositories\Postgres\Store\StoreCrossDeviceCollectionRepositoryInterface;
use App\Repositories\Postgres\Store\StoreCrossDeviceStatisticRepositoryInterface;
use App\Repositories\Postgres\Store\StoreDeviceCollectionRepositoryInterface;
use App\Services\CommonServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DateInterval;
use DatePeriod;
use DateTime;
use  DB;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use function PHPUnit\Framework\returnArgument;

class ApiDeviceController extends Controller
{
    protected $deviceRepository;
    protected $imageRepository;
    protected $orderRepository;
    protected $orderDeviceRepository;
    protected $storeDeviceCollectionRepository;
    protected $storeCrossDeviceCollectionRepository;
    protected $storeCrossDeviceStatisticRepository;
    protected $adminDeviceImageRepository;
    protected $commonService;
    protected $storeRepository;
    protected $accountRepository;

    public function __construct(
        DeviceRepositoryInterface $deviceRepository,
        StoreRepositoryInterface $storeRepository,
        StoreCrossDeviceStatisticRepositoryInterface $storeCrossDeviceStatisticRepository,
        AccountRepositoryInterface $accountRepository,
        CommonServiceInterface $commonService,
        StoreDeviceCollectionRepositoryInterface $storeDeviceCollectionRepository,
        StoreCrossDeviceCollectionRepositoryInterface $storeCrossDeviceCollectionRepository,
        OrderDeviceRepositoryInterface $orderDeviceRepository,
        OrderRepositoryInterface $orderRepository,
        ImageRepositoryInterface $imageRepository,
        AdminDeviceImageRepositoryInterface $adminDeviceImageRepository
    )
    {
        $this->deviceRepository = $deviceRepository;
        $this->storeDeviceCollectionRepository = $storeDeviceCollectionRepository;
        $this->storeCrossDeviceCollectionRepository = $storeCrossDeviceCollectionRepository;
        $this->orderDeviceRepository = $orderDeviceRepository;
        $this->imageRepository = $imageRepository;
        $this->orderRepository = $orderRepository;
        $this->commonService = $commonService;
        $this->storeRepository = $storeRepository;
        $this->storeCrossDeviceStatisticRepository = $storeCrossDeviceStatisticRepository;
        $this->accountRepository = $accountRepository;
        $this->adminDeviceImageRepository = $adminDeviceImageRepository;
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

            $devices = $this->deviceRepository->getListDeviceByFilter($limit, $filter);
            $devices = eFunction::getStatusDevices($devices);
            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $devices);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function create(CreateDeviceRequest $request)
    {
        $data = $request->only([
            'name',
            'slug',
            'description',
            'own',
            'store_id',
            'branch_id'
        ]);

        try {

            DB::beginTransaction();
            $accountInfo = $request->get('accountInfo');
            $data['active_code'] = eFunction::randomInt(6);
            $data['block_ads'] = Device::BLOCK_ADS_TIME;
            $data['total_time_empty'] = Device::TOTAL_TIME_EMPTY;
            eFunction::FillUp($accountInfo, $data);
            $this->deviceRepository->create($data);
            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-create-success'));
        } catch (\Exception $e) {
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'));
        }
    }

    public function detail(DetailDeviceRequest $request)
    {
        $id = $request->get('id');
        $accountInfo = $request->get('accountInfo');
        $device = $this->deviceRepository->getOneArrayDeviceWithStoreAndBranchByFilter(['id' => $id, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
        if (!empty($device)){
            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $device);
        }

        return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
    }

    public function update(UpdateDeviceRequest $request)
    {
        $data = $request->only([
            'name',
            'slug',
            'description',
            'own',
            'store_id',
            'branch_id'
        ]);

        $id = $request->get('id');
        try {
            DB::beginTransaction();
            $accountInfo = $request->get('accountInfo');
            $device = $this->deviceRepository->getOneObjectDeviceByFilter(['id' => $id, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
            if (empty($device)){
                return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
            }

            eFunction::FillUp($accountInfo, $data);
            $this->deviceRepository->update($device, $data);

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-update-success'));
        } catch (\Exception $e) {
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'));
        }
    }

    public function delete(DeleteDeviceRequest $request)
    {
        try{

            DB::beginTransaction();

            $accountInfo = $request->get('accountInfo');

            $ids = eFunction::arrayInteger($request->get('ids'));
            if (!empty($ids)){
                $devices = $this->deviceRepository->getAllDeviceByFilter(['id' => $ids, 'deleted_at' => true, 'project_id' => $accountInfo['project_id']]);
                $this->deviceRepository->deleteAllDeviceByFilter(['id' => $ids, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
                $this->adminDeviceImageRepository->delAllAdminDeviceImageByFilter(['device_id' => $ids, 'project_id' => $accountInfo['project_id']]);
                $this->storeDeviceCollectionRepository->delAllStoreDeviceCollectionByFilter(['device_id' => $ids, 'project_id' => $accountInfo['project_id']]);
                $this->orderDeviceRepository->deleteAllOrderDeviceFromAdminByFilter(['device_id' => $ids, 'status' => OrderDevice::STATUS_USING, 'project_id' => $accountInfo['project_id']]);

                foreach ($devices as $device){
                    if (!empty($device['device_code'])){
                        $params = [
                            'event' => Device::EVENT_DELETE_DEVICE,
                            'message' => 'Xóa thiết bị (ADMIN)',
                            'data' => []
                        ];

                        eFunction::sendMessageQueue($params, $device['device_code']);
                    }
                }
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-delete-success'), []);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function changeStatusDevice(ChangeStatusDeviceRequest $request)
    {
        $isActive = $request->get('is_active');
        $status = $request->get('status');
        $id = $request->get('id');
        $accountInfo = $request->get('accountInfo');

        try {
            DB::beginTransaction();
            if (!in_array((int)$isActive, [Device::IS_DISABLE, Device::IS_ACTIVE]) || !in_array((int)$status, [Device::DISCONNECT, Device::CONNECT])){
                return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
            }

            $device = $this->deviceRepository->getOneObjectDeviceByFilter(['id' => (int)$id, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
            if (empty($device)){
                return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
            }

            if (!empty($device->device_code) && isset($device->status)) {
                $arrSendEmail = [
                    'type' => Device::BLOCK_DEVICE,
                    'device_name' => $device->name,
                ];

                $params = [];
                if ((int)$device->status === Device::CONNECT && (int)$isActive === Device::IS_DISABLE && (int)$status === Device::CONNECT) {
                    $arrSendEmail['title'] = Device::DEVICE_LOCK;
                    $arrSendEmail['status'] = Device::PLAY;
                }

                if ((int)$device->status === Device::CONNECT && (int)$isActive === Device::IS_DISABLE && (int)$status === Device::DISCONNECT) {
                    $params = [
                        'event' => Device::EVENT_BLOCK_DEVICE,
                        'message' => 'Block thiết bị (ADMIN)',
                        'data' => []
                    ];

                    $arrSendEmail['title'] = Device::DEVICE_LOCK;
                    $arrSendEmail['status'] = Device::STOP_PLAY;

                } else if ((int)$device->status === Device::DISCONNECT && (int)$isActive === Device::IS_ACTIVE && (int)$status === Device::CONNECT) {
                    $params = [
                        'event' => Device::EVENT_BLOCK_DEVICE,
                        'message' => 'Mở khóa thiết bị (ADMIN)',
                        'data' => $this->commonService->getDataFromDeviceId($id)
                    ];

                    $arrSendEmail['type'] = Device::OPEN_DEVICE;
                    $arrSendEmail['title'] = Device::DEVICE_UNLOCK;
                    $arrSendEmail['status'] = Device::PLAY;

                }

                if (!empty($params)) {
                    eFunction::sendMessageQueue($params, $device->device_code);
                }

                $account = $this->accountRepository->getOneArrayAccountByFilter(['project_id' => $accountInfo['project_id']]);
                if (!empty($account['email'])) {
                    $arrSendEmail['email'] = $account['email'];
                    event(new SendEmailDeviceEvent($arrSendEmail));
                }
            }

            $this->deviceRepository->update($device, ['is_active' => (int)$isActive, 'status' => (int)$status]);


            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-update-success'));
        } catch (\Exception $e) {
            DB::rollback();
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'));
        }
    }

    public function detailCollection(DetailCollectionDeviceRequest $request)
    {
        $id = $request->get('id');
        $accountInfo = $request->get('accountInfo');
        $deviceWithCollection = $this->deviceRepository->getDetailDeviceWithCollectionSelfByFilter(['id' => $id, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
        if (!empty($deviceWithCollection)){
            $deviceWithCollection = eFunction::getFullUrlCollectionInDevice($deviceWithCollection);

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $deviceWithCollection);
        }

        return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
    }

    public function addCollection(CreateCollectionDeviceRequest $request)
    {
        $id = $request->get('id');

        DB::beginTransaction();
        try{
            $collections = $request->get('collections');
            $accountInfo = $request->get('accountInfo');

            $device = $this->deviceRepository->getOneArrayDeviceWithStoreAndBranchByFilter(['id' => $id, 'deleted_at' => true]);
            if (!empty($collections) && is_array($collections) && !empty($device)){
                $isActiveDevice = eFunction::activeDevice($device);
                if (empty($isActiveDevice)){
                    return eResponse::response(STATUS_API_FALSE, __('notification.system.device-blocked'));
                }

                $totalSecond = collect($collections)->sum('second');
                $idsCollection = collect($collections)->pluck('collection_id')->values()->toArray();
                if (!empty($idsCollection)){
                    $addedCollection = $this->adminDeviceImageRepository->getAllAdminDeviceImagesWithOnlySecondByFilter(['device_id' => $id, 'project_id' => $accountInfo['project_id']]);
                    $totalSecond = $totalSecond + collect($addedCollection)->sum('second');
                    if ($totalSecond > Device::MAX_TIME_ADMIN){
                        return eResponse::response(STATUS_API_FALSE, __('notification.system.time-limit-exceeded'));
                    }

                    $CheckingCollection = $this->imageRepository->countAllImageByFilter(['id' => $idsCollection, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
                    if (empty($CheckingCollection) || $CheckingCollection !== count($idsCollection)){
                        return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
                    }

                    $this->checkCrossCollections($id, $totalSecond, $accountInfo);//Chech các đơn quảng cáo chéo
                    $this->changeTimeOfDevice($id, $totalSecond, $accountInfo, Device::TYPE_ADD);//Cập nhật thời gian trống của thiết bị
                    //Lấy số media đã thêm vào rồi để xếp vị trí

                    $arrAddCollection = [];
                    foreach ($collections as $collection){
                        if (!empty($collection['collection_id']) && isset($collection['position']) && !empty($collection['second']) && !empty($collection['type'])){
                            $arrAddCollection[] = [
                                'device_id' => (int)$id,
                                'image_id' => (int)@$collection['collection_id'],
                                'project_id' => $accountInfo['project_id'],
                                'account_id' => $accountInfo['id'],
                                'volume' => in_array((int)@$collection['volume'], [AdminDeviceImage::ENABLE_VOLUME, AdminDeviceImage::DISABLE_VOLUME]) ? (int)@$collection['volume'] : AdminDeviceImage::ENABLE_VOLUME,
                                'position' => !empty($addedCollection) ? count($addedCollection) + (int)@$collection['position'] + 1 : (int)@$collection['position'] + 1,
                                'second' => (int)@$collection['second'],
                                'created_at' => eFunction::getDateTimeNow(),
                                'updated_at' => eFunction::getDateTimeNow(),
                                'type' => (int)@$collection['type'],
                            ];
                        }
                    }

                    if (!empty($arrAddCollection)){
                        $this->adminDeviceImageRepository->insertMulti($arrAddCollection);
                    }

                    if (!empty($device['device_code']) && isset($device['is_active']) && (int)$device['is_active'] === Device::IS_ACTIVE && isset($device['status']) && (int)$device['status'] === Device::CONNECT){
                        $params = [
                            'event' => Device::EVENT_CHANGE_MEDIA,
                            'message' => 'Thêm media cho thiết bị (ADMIN)',
                            'data' => $this->commonService->getDataFromDeviceId($id)
                        ];

                        eFunction::sendMessageQueue($params, $device['device_code']);
                    }
                }
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-create-success'));

        }catch (\Exception $e){
            DB::rollback();
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function updateCollection(UpdateCollectionDeviceRequest $request)
    {
        DB::beginTransaction();
        try{

            $collections = $request->get('collections');
            $accountInfo = $request->get('accountInfo');
            $id = $request->get('id');

            if (!empty($collections)){
                $device = $this->deviceRepository->getOneArrayDeviceByFilter(['id' => (int)$id, 'deleted_at' => true, 'project_id' => $accountInfo['project_id']]);
                if (empty($device)){
                    return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
                }

                $totalSecond = collect($collections)->sum('second');
                if ($totalSecond > Device::MAX_TIME_ADMIN){
                    return eResponse::response(STATUS_API_FALSE, __('notification.system.time-limit-exceeded'));
                }

                $this->checkCrossCollections($id, $totalSecond, $accountInfo);
                $this->changeTimeOfDevice($id, $totalSecond, $accountInfo, Device::TYPE_UPDATE);

                $userInstance = new AdminDeviceImage();
                $index = 'id';

                $arrAdminDeviceImage = [];
                foreach ($collections as $k => $collection){
                    $arrAdminDeviceImage[] = [
                        'id' => (int)@$collection['admin_device_image_id'],
                        'volume' => (int)@$collection['volume'],
                        'second' => (int)@$collection['second'],
                        'position' => $k + 1
                    ];
                }

                if (!empty($arrAdminDeviceImage)){
                    \Batch::update($userInstance, $arrAdminDeviceImage, $index);
                }

                if (!empty($device['device_code']) && isset($device['is_active']) && (int)$device['is_active'] === Device::IS_ACTIVE && !empty($device['status']) && (int)$device['status'] === Device::CONNECT){
                    $params = [
                        'event' => Device::EVENT_CHANGE_MEDIA,
                        'message' => 'Cập nhật media trong thiết bị (ADMIN)',
                        'data' => $this->commonService->getDataFromDeviceId($id)
                    ];

                    eFunction::sendMessageQueue($params, $device['device_code']);
                }
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-update-success'));

        }catch (\Exception $e){
            DB::rollback();
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function deleteCollection(DeleteCollectionDeviceRequest $request)
    {
        DB::beginTransaction();
        try{
            $id = $request->get('id');
            $accountInfo = $request->get('accountInfo');

            $ids = eFunction::arrayInteger($request->get('ids'));
            $device = $this->deviceRepository->getOneObjectDeviceByFilter(['id' => $id, 'deleted_at' => true]);

            if (!empty($ids) && !empty($device)){
                $isActiveDevice = eFunction::activeDevice($device->toArray());
                if (empty($isActiveDevice)){
                    return eResponse::response(STATUS_API_FALSE, __('notification.system.device-blocked'));
                }

                //Lấu ra theo thứ tự của collection
                $adminDeviceImages = $this->adminDeviceImageRepository->getAllAdminDeviceImageByFilter(['device_id' => $id, 'project_id' => $accountInfo['project_id']]);
                if (!empty($adminDeviceImages)){
                    //Lấy những thằng đã xóa
                    $totalSecondDeleted = $totalSecond = 0;
                    $arrAdminDeviceImages = [];
                    foreach ($adminDeviceImages as $adminDeviceImage){
                        if (!empty($adminDeviceImage['second']) && in_array($adminDeviceImage['id'], $ids)){
                            $totalSecondDeleted = $totalSecondDeleted + (int)$adminDeviceImage['second'];
                        }

                        if (!in_array($adminDeviceImage['id'], $ids)){
                            $arrAdminDeviceImages[] = $adminDeviceImage;
                        }
                    }

                    if (!empty($arrAdminDeviceImages)){
                        //Tổng thời gian còn lại của thiết bị với admin
                        $totalSecond = collect($arrAdminDeviceImages)->sum('second');

                        $userInstance = new AdminDeviceImage();
                        $index = 'id';

                        $arrUpdateAdminDeviceImages = [];
                        foreach ($arrAdminDeviceImages as $k => $adminDeviceImage){
                            $arrUpdateAdminDeviceImages[] = [
                                'id' => $adminDeviceImage['id'],
                                'position' => $k + 1
                            ];
                        }

                        if (!empty($arrUpdateAdminDeviceImages)){
                            \Batch::update($userInstance, $arrUpdateAdminDeviceImages, $index);
                        }
                    }

                    //update lại thời gian của device còn khả dụng
                    $this->commonService->changeTimeOfDeviceAdmin($device, $totalSecond, (int)@$device->total_time_empty + $totalSecondDeleted);
                }

                $this->adminDeviceImageRepository->delAllAdminDeviceImageByFilter(['id' => $ids, 'project_id' => $accountInfo['project_id']]);

                if (!empty($device->device_code) && isset($device->is_active) && (int)$device->is_active === Device::IS_ACTIVE && !empty($device->status) && (int)$device->status === Device::CONNECT){
                    $params = [
                        'event' => Device::EVENT_CHANGE_MEDIA,
                        'message' => 'Xóa media trong thiết bị (ADMIN)',
                        'data' => $this->commonService->getDataFromDeviceId($id)
                    ];

                    eFunction::sendMessageQueue($params, $device->device_code);
                }
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-delete-success'));

        }catch (\Exception $e){
            DB::rollback();
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    private function changeTimeOfDevice($device_id, $totalSecond, $accountInfo, $type)
    {
        $device = $this->deviceRepository->getOneObjectDeviceByFilter(['id' => (int)$device_id, 'deleted_at' => true, 'project_id' => $accountInfo['project_id']]);
        if (!empty($device)){
            if ($type === Device::TYPE_ADD) {
                $totalTimeEmpty = (int)@$device->total_time_empty - $totalSecond;
                $totalSecond = $totalSecond + $device->total_time_admin;
            }else{
                $totalTimeEmpty = (int)@$device->total_time_empty + (int)@$device->total_time_admin - $totalSecond;
            }
            $this->commonService->changeTimeOfDeviceAdmin($device, $totalSecond, $totalTimeEmpty);
            return true;
        }
        return false;

    }

    private function checkCrossCollections($device_id, $totalSecond, $accountInfo)
    {
        $device = $this->deviceRepository->getOneObjectDeviceByFilter(['id' => (int)$device_id, 'deleted_at' => true, 'project_id' => $accountInfo['project_id']]);
        if (!empty($device)) {
            $totalTimeAdmin = $totalSecond + (int)$device->total_time_admin;//Tổng thời gian đã sử dụng của ant với thiết bi này
            if($totalTimeAdmin <= Device::MAX_TIME_ADMIN){
                $orderDevices = $this->orderDeviceRepository->getAllOrderDeviceWithOrderByFilter(['device_id' => (int)$device_id, 'status' => OrderDevice::STATUS_USING]);
                if (!empty($orderDevices)){
                    $idsOrders = collect($orderDevices)->pluck('order_id')->filter()->unique()->values()->toArray();
                    $orders = $this->orderRepository->getAllOrderWithOrderByByFilter(['id' => $idsOrders, 'deleted_at' => true, 'status' => [Order::WAIT, Order::CONFIRMED]]);
                    if (!empty($orders)){
                        $totalTimeBooked = collect($orders)->sum('time_booked');//thời gian mà thiết bị này đã được đặt
                        if ($totalTimeBooked > Order::MIN_TIME_BOOKED){
                            $arrIdsOrderDel = [];

                            foreach ($orderDevices as $orderDevice){
                                $totalTimeStore = (int)$orderDevice['block_time'] > (int)$orderDevice['total_time_store'] ? (int)$orderDevice['block_time'] : (int)$orderDevice['total_time_store'];//Thời gian của cửa hàng
                                $totalTimeEmptyStore = Device::MAX_TIME_STORE - $totalTimeStore;
                                if ($totalTimeBooked > $totalTimeEmptyStore && (Device::MAX_TIME_ADMIN - $totalTimeAdmin) < ($totalTimeBooked - $totalTimeStore)){
                                    //Nếu mà thời gian đơn hàng đặt sang ant lớn hơn thời gian trống của ant
                                    $arrIdsOrderDel[] = $orderDevice['order_id'];
                                    $totalTimeBooked = $totalTimeBooked - $orderDevice['order']['time_booked'];
                                }
                            }

                            if (!empty($arrIdsOrderDel)){//Nếu có các đơn hàng cần xóa thiết bi này
                                //Lấy tất cả thiết bị với đơn hàng và chi nhánh với đơn hàng, cửa hàng với đơn hàng và đơn hàng.

                                $orderDeviceWithAll = $this->orderDeviceRepository->getAllOrderDeviceWithAllByFilter(['device_id' => $device_id, 'order_id' => $arrIdsOrderDel, 'status' => OrderDevice::STATUS_USING]);
                                if (!empty($orderDeviceWithAll)){
                                    $OrderDeviceInstance = new OrderDevice();
                                    $OrderBranchInstance = new OrderBranch();
                                    $OrderStoreInstance = new OrderStore();
                                    $OrderInstance = new Order();
                                    $StoreInstance = new Store();
                                    $index = 'id';

                                    //Check xem thiết bị này đã chạy đơn hàng bao nhiêu rồi để trừ đi
                                    $storeCrossDeviceStatistics = $this->storeCrossDeviceStatisticRepository->getAllStoreCrossDeviceStatisticByFilter(['order_id' => $arrIdsOrderDel, 'device_id' => (int)$device_id]);
                                    $arrPointStoreUsed = [];
                                    if (!empty($storeCrossDeviceStatistics)){//Nếu thiết bị đã chạy rồi
                                        foreach ($storeCrossDeviceStatistics as $storeCrossDeviceStatistic){
                                            if (empty($arrPointStoreUsed[$storeCrossDeviceStatistic['order_id']][$storeCrossDeviceStatistic['device_id']])){
                                                $arrPointStoreUsed[$storeCrossDeviceStatistic['order_id']][$storeCrossDeviceStatistic['device_id']] = (int)$storeCrossDeviceStatistic['total_time'];
                                            }else{
                                                $arrPointStoreUsed[$storeCrossDeviceStatistic['order_id']][$storeCrossDeviceStatistic['device_id']] = (int) $arrPointStoreUsed[$storeCrossDeviceStatistic['order_id']][$storeCrossDeviceStatistic['device_id']] + (int)$storeCrossDeviceStatistic['total_time'];
                                            }
                                        }
                                    }

                                    $arrOrderDevices = $arrOrderBranches = $arrOrderStores = $arrOrders = $arrStoreUpdate = $arrStoreBooked = $arrStoreCross = $arrIdsStores = [];
                                    foreach ($orderDeviceWithAll as $orderDevice){
                                        $totalTimeOrder = $totalTimeCollectionOrder = 0;

                                        //Cập nhật lại cho order_devices
                                        $arrOrderDevices[] = [
                                            'id' => @(int)$orderDevice['id'],
                                            'point' => 0,
                                            'real_point' => 0,
                                            'status' => OrderDevice::STATUS_DELETE
                                        ];

                                        //Cập nhật lại cho order_branches
                                        $arrOrderBranches[] = [
                                            'id' => @(int)$orderDevice['order_branch']['id'],
                                            'point' => @(int)$orderDevice['order_branch']['point'] - @(int)$orderDevice['point'],
                                            'real_point' => @(int)$orderDevice['order_branch']['real_point'] - @(int)$orderDevice['real_point']
                                        ];

                                        //Cập nhật lại cho order_stores
                                        $arrOrderStores[] = [
                                            'id' => @(int)$orderDevice['order_store']['id'],
                                            'point' => @(int)$orderDevice['order_store']['point'] - @(int)$orderDevice['point'],
                                            'real_point' => @(int)$orderDevice['order_store']['real_point'] - @(int)$orderDevice['real_point']
                                        ];

                                        //Cập nhật lại cho orders
                                        $arrOrders[] = [
                                            'id' => @(int)$orderDevice['order']['id'],
                                            'payment' => @(int)$orderDevice['order']['payment'] - @(int)$orderDevice['point']
                                        ];

                                        if (!empty($orderDevice['order']['time_frames'])){
                                            $totalTimeOrder = eFunction::getTotalTimeInOrder($orderDevice['order']['time_frames']);
                                        }

                                        if (!empty($orderDevice['order']['store_cross_device_collections'])){
                                            $totalTimeCollectionOrder = collect($orderDevice['order']['store_cross_device_collections'])->sum('second');
                                        }

                                        if (!empty($totalTimeOrder) && !empty($totalTimeCollectionOrder) && !empty($arrPointStoreUsed[$orderDevice['order_id']][$orderDevice['device_id']])){
                                            $pointLeft = $orderDevice['point'] - $orderDevice['point']*($arrPointStoreUsed[$orderDevice['order_id']][$orderDevice['device_id']]/($totalTimeOrder*$totalTimeCollectionOrder));
                                            $realPointLeft = $orderDevice['real_point'] - $orderDevice['real_point']*($arrPointStoreUsed[$orderDevice['order_id']][$orderDevice['device_id']]/($totalTimeOrder*$totalTimeCollectionOrder));
                                        }else{
                                            $pointLeft = $orderDevice['point'];
                                            $realPointLeft = $orderDevice['real_point'];
                                        }

                                        //Công lại điểm cho các cửa hàng đi đặt đơn
                                        if (!empty($arrStoreBooked[$orderDevice['order']['store_id']]) && !empty($orderDevice['order']['store_id'])) {
                                            $arrStoreBooked[$orderDevice['order']['store_id']] = $arrStoreBooked[$orderDevice['order']['store_id']] + $pointLeft;
                                        } else {
                                            $arrStoreBooked[$orderDevice['order']['store_id']] = $pointLeft;
                                        }

                                        //Trừ điểm cho các cửa hang được đặt đơn
                                        if (!empty($arrStoreCross[$orderDevice['order_store']['store_id']]) && !empty($orderDevice['order_store']['store_id'])) {
                                            $arrStoreCross[$orderDevice['order_store']['store_id']] = $arrStoreCross[$orderDevice['order_store']['store_id']] + $realPointLeft;
                                        } else {
                                            $arrStoreCross[$orderDevice['order_store']['store_id']] = $realPointLeft;
                                        }

                                        //Thêm list id của cửa hàng để check
                                        $arrIdsStores[] = (int)@$orderDevice['order_store']['store_id'];
                                        $arrIdsStores[] = (int)@$orderDevice['order']['store_id'];
                                    }

                                    if (!empty($arrIdsStores)){
                                        $arrIdsStores = array_values(array_unique($arrIdsStores));
                                        $stores = $this->storeRepository->getAllStoreByFilter(['id' => $arrIdsStores, 'deleted_at' => true]);
                                        if (!empty($stores)){
                                            foreach ($stores as $store){
                                                if (!empty($arrStoreBooked[$store['id']]) && !empty($arrStoreCross[$store['id']])){
                                                    //Nếu có trường hợp này thì cũng qùy
                                                }else if(!empty($arrStoreBooked[$store['id']])){
                                                    //Trường hợp cửa hàng là đi đặt thì cộng lại tiền cho cửa hàng đó
                                                    $arrStoreUpdate[] = [
                                                        'id' => $store['id'],
                                                        'current_point' => $store['current_point'] + $arrStoreBooked[$store['id']],
                                                        'total_point' => $store['total_point'] + $arrStoreBooked[$store['id']]
                                                    ];
                                                }else if(!empty($arrStoreCross[$store['id']])){
                                                    //Trường hợp là được đặt thì trừ lại điểm vì hủy
                                                    if(($store['current_point'] - $arrStoreCross[$store['id']]) < 0){
                                                        $arrStoreUpdate[] = [
                                                            'id' => $store['id'],
                                                            'debt_point' => $arrStoreCross[$store['id']] - $store['current_point']
                                                        ];
                                                    }else{
                                                        $arrStoreUpdate[] = [
                                                            'id' => $store['id'],
                                                            'current_point' => $store['current_point'] - $arrStoreCross[$store['id']]
                                                        ];
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    if (!empty($arrOrderDevices)){
                                        \Batch::update($OrderDeviceInstance, $arrOrderDevices, $index);
                                    }

                                    if (!empty($arrOrderBranches)){
                                        \Batch::update($OrderBranchInstance, $arrOrderBranches, $index);
                                    }

                                    if (!empty($arrOrderStores)){
                                        \Batch::update($OrderStoreInstance, $arrOrderStores, $index);
                                    }

                                    if (!empty($arrOrders)){
                                        \Batch::update($OrderInstance, $arrOrders, $index);
                                    }

                                    if (!empty($arrStoreUpdate)){
                                        \Batch::update($StoreInstance, $arrStoreUpdate, $index);
                                    }

                                }
                            }
                        }
                    }
                }
            }
        }

        return true;
    }

    private function makeFilter($request, &$filter)
    {
        if ($request->has('key_word')) {
            $filter['key_word'] = $request->get('key_word');
        }

        if ($request->has('branch_id')) {
            $filter['branch_id'] = $request->get('branch_id');
        }

        if ($request->has('status')) {
            $status = $request->get('status');
            if ((int)$status === Device::VIEW_CONNECT){
                $filter['device_code'] = eFunction::getListDeviceConnect();
            }

            if ((int)$status === Device::VIEW_DISCONNECT){
                $filter['not_in_device_code'] = eFunction::getListDeviceConnect();
            }

            if ((int)$status === Device::VIEW_NOT_USE){
                $filter['device_code'] = null;
            }
        }

        if ($request->has('own')) {
            $filter['own'] = $request->get('own');
        }

        if ($request->has('store_id')) {
            $filter['store_id'] = $request->get('store_id');
        }
    }
}
