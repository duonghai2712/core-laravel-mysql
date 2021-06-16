<?php

namespace App\Http\Controllers\Api\Postgres\Store;

use App\Elibs\eCrypt;
use App\Elibs\eFunction;
use App\Http\Controllers\Controller;
use App\Elibs\eResponse;
use App\Http\Requests\Api\PaginationRequest;
use App\Http\Requests\Api\Postgres\Store\Device\AddCollectionRequest;
use App\Http\Requests\Api\Postgres\Store\Device\BlockAdsRequest;
use App\Http\Requests\Api\Postgres\Store\Device\DeleteCollectionRequest;
use App\Http\Requests\Api\Postgres\Store\Device\DetailDeviceRequest;
use App\Http\Requests\Api\Postgres\Store\Device\UpdateDeivceRequest;
use App\Models\Postgres\Admin\Device;
use App\Models\Postgres\Admin\DeviceStatistic;
use App\Models\Postgres\Store\Order;
use App\Models\Postgres\Store\StoreDeviceCollection;
use App\Repositories\Postgres\Admin\DeviceRepositoryInterface;
use App\Repositories\Postgres\Admin\DeviceStatisticRepositoryInterface;
use App\Repositories\Postgres\Store\CollectionRepositoryInterface;
use App\Repositories\Postgres\Store\LogOperationRepositoryInterface;
use App\Repositories\Postgres\Store\OrderDeviceRepositoryInterface;
use App\Repositories\Postgres\Store\OrderRepositoryInterface;
use App\Repositories\Postgres\Store\StoreDeviceCollectionRepositoryInterface;
use App\Repositories\Postgres\Store\TimeFrameRepositoryInterface;
use App\Services\CommonServiceInterface;
use App\Services\ExcelServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DateInterval;
use DatePeriod;
use DateTime;
use  DB;

class   ApiDeviceController extends Controller
{
    protected $deviceRepository;
    protected  $deviceStatisticRepository;
    protected $commonService;
    protected $orderDeviceRepository;
    protected $logOperationRepository;
    protected $collectionRepository;
    protected $excelService;
    protected $timeFrameRepository;
    protected $orderRepository;
    protected $storeDeviceCollectionRepository;

    public function __construct(
        DeviceRepositoryInterface $deviceRepository,
        TimeFrameRepositoryInterface $timeFrameRepository,
        OrderRepositoryInterface $orderRepository,
        OrderDeviceRepositoryInterface $orderDeviceRepository,
        ExcelServiceInterface $excelService,
        DeviceStatisticRepositoryInterface $deviceStatisticRepository,
        LogOperationRepositoryInterface $logOperationRepository,
        CommonServiceInterface $commonService,
        CollectionRepositoryInterface $collectionRepository,
        StoreDeviceCollectionRepositoryInterface $storeDeviceCollectionRepository
    )
    {
        $this->deviceRepository = $deviceRepository;
        $this->commonService = $commonService;
        $this->excelService = $excelService;
        $this->timeFrameRepository = $timeFrameRepository;
        $this->orderRepository = $orderRepository;
        $this->orderDeviceRepository = $orderDeviceRepository;
        $this->logOperationRepository = $logOperationRepository;
        $this->collectionRepository = $collectionRepository;
        $this->deviceStatisticRepository = $deviceStatisticRepository;
        $this->storeDeviceCollectionRepository = $storeDeviceCollectionRepository;
    }

    public function index(PaginationRequest $request)
    {
        try{
            $filter = [];
            $limit = $request->limit();
            $storeAccountInfo = $request->get('storeAccountInfo');
            eFunction::FillUpStore($storeAccountInfo, $filter);
            $this->makeFilter($request, $filter);
            $this->makeFilterStatus($request, $filter);
            $filter['deleted_at'] = true;

            $devices = $this->deviceRepository->getListDeviceByFilter($limit, $filter);
            $devices = eFunction::getStatusDevices($devices);

            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $devices);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function blockAds(BlockAdsRequest $request)
    {
        try{
            $id = $request->get('id');
            $blockTime = $request->get('time');
            $storeAccountInfo = $request->get('storeAccountInfo');
            $device = $this->deviceRepository->getOneObjectDeviceByFilter(['id' => $id, 'project_id' => $storeAccountInfo['project_id'], 'deleted_at' => true]);
            if (empty($device)){
                return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
            }
            if ($blockTime > (Device::MAX_TIME_STORE - $device->total_time_store)){
                return eResponse::response(STATUS_API_FALSE, __('notification.api-exceeded'));
            }

            $this->deviceRepository->update($device, [Device::BLOCK_ADS => (int)$blockTime]);
            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-update-success'));

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function detail(DetailDeviceRequest $request)
    {
        $id = $request->get('id');
        $storeAccountInfo = $request->get('storeAccountInfo');

        $deviceWithCollection = $this->deviceRepository->getDetailDeviceWithCollectionSelfByFilter(['id' => $id, 'project_id' => $storeAccountInfo['project_id'], 'deleted_at' => true]);
        if (!empty($deviceWithCollection)){
            $deviceWithCollection = eFunction::getFullUrlCollectionInDevice($deviceWithCollection);
            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $deviceWithCollection);
        }

        return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
    }

    public function addCollection(AddCollectionRequest $request)
    {
        DB::beginTransaction();
        try{
            $collections = $request->get('collections');
            $storeAccountInfo = $request->get('storeAccountInfo');
            $id = $request->get('id');

            $device = $this->deviceRepository->getOneArrayDeviceByFilter(['id' => (int)$id, 'deleted_at' => true, 'project_id' => $storeAccountInfo['project_id']]);
            if (!empty($collections) && is_array($collections) && !empty($device)){
                $isActiveDevice = eFunction::activeDevice($device);
                if (empty($isActiveDevice)){
                    return eResponse::response(STATUS_API_FALSE, __('notification.system.device-blocked'));
                }

                $totalSecond = collect($collections)->sum('second');
                $idsCollection = collect($collections)->pluck('collection_id')->values()->toArray();
                if (!empty($idsCollection)){
                    $addedCollection = $this->storeDeviceCollectionRepository->getAllStoreDeviceCollectionWithOnlySecondByFilter(['device_id' => $id, 'project_id' => $storeAccountInfo['project_id']]);
                    $totalSecond = $totalSecond + collect($addedCollection)->sum('second');
                    if ($totalSecond > Device::MAX_TIME_STORE){
                        return eResponse::response(STATUS_API_FALSE, __('notification.system.time-limit-exceeded'));
                    }

                    $CheckingCollection = $this->collectionRepository->countAllCollectionByFilter(['id' => $idsCollection, 'project_id' => $storeAccountInfo['project_id'], 'store_id' => $storeAccountInfo['store_id'], 'deleted_at' => true]);
                    if (empty($CheckingCollection) || $CheckingCollection !== count($idsCollection)){
                        return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
                    }

                    $this->changeTimeOfDevice($id, $totalSecond, $storeAccountInfo, Device::TYPE_ADD);

                    $arrAddCollection = [];
                    foreach ($collections as $collection){
                        if (!empty($collection['collection_id']) && isset($collection['position']) && !empty($collection['second']) && !empty($collection['type'])){
                            $arrAddCollection[] = [
                                'device_id' => (int)$id,
                                'collection_id' => (int)@$collection['collection_id'],
                                'project_id' => $storeAccountInfo['project_id'],
                                'store_id' => $storeAccountInfo['store_id'],
                                'volume' => in_array((int)@$collection['volume'], [StoreDeviceCollection::ENABLE_VOLUME, StoreDeviceCollection::DISABLE_VOLUME]) ? (int)@$collection['volume'] : StoreDeviceCollection::ENABLE_VOLUME,
                                'store_account_id' => $storeAccountInfo['id'],
                                'position' => !empty($addedCollection) ? count($addedCollection) + (int)@$collection['position'] + 1 : (int)@$collection['position'] + 1,
                                'second' => (int)@$collection['second'],
                                'type' => (int)@$collection['type'],
                                'created_at' => eFunction::getDateTimeNow(),
                                'updated_at' => eFunction::getDateTimeNow(),
                            ];
                        }
                    }

                    if (!empty($arrAddCollection)){
                        $this->storeDeviceCollectionRepository->insertMulti($arrAddCollection);
                    }

                    if (!empty($device['device_code']) && isset($device['is_active']) && (int)$device['is_active'] === Device::IS_ACTIVE && !empty($device['status']) && (int)$device['status'] === Device::CONNECT){
                        $params = [
                            'event' => Device::EVENT_CHANGE_MEDIA,
                            'message' => 'Thêm media trong thiết bị (STORE)',
                            'data' => $this->commonService->getDataFromDeviceId($id)
                        ];

                        eFunction::sendMessageQueue($params, $device['device_code']);
                    }

                    $activities = eFunction::getActivity($storeAccountInfo,  @$device['id'], @$device['name'], 'update_media_store', null);
                    if (!empty($activities)){
                        $this->logOperationRepository->create($activities);
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

    public function updateCollection(UpdateDeivceRequest $request)
    {
        DB::beginTransaction();
        try{

            $collections = $request->get('collections');
            $storeAccountInfo = $request->get('storeAccountInfo');
            $id = $request->get('id');
            $device = $this->deviceRepository->getOneArrayDeviceByFilter(['id' => (int)$id, 'deleted_At' => true, 'project_id' => $storeAccountInfo['project_id']]);

            if (!empty($collections) && !empty($device)){
                $userInstance = new StoreDeviceCollection();
                $index = 'id';

                $totalSecond = collect($collections)->sum('second');
                if ($totalSecond > Device::MAX_TIME_STORE){
                    return eResponse::response(STATUS_API_FALSE, __('notification.system.time-limit-exceeded'));
                }
                $this->changeTimeOfDevice($id, $totalSecond, $storeAccountInfo, Device::TYPE_UPDATE);

                $arrNewPositionStoreDeviceCollection = [];
                foreach ($collections as $k => $coll){
                    $arrNewPositionStoreDeviceCollection[] = [
                        'id' => (int)@$coll['store_device_collection_id'],
                        'volume' => (int)@$coll['volume'],
                        'second' => (int)@$coll['second'],
                        'position' => $k + 1
                    ];
                }

                if (!empty($arrNewPositionStoreDeviceCollection)){
                    \Batch::update($userInstance, $arrNewPositionStoreDeviceCollection, $index);
                }

                if (!empty($device['device_code']) && isset($device['is_active']) && (int)$device['is_active'] === Device::IS_ACTIVE && !empty($device['status']) && (int)$device['status'] === Device::CONNECT){
                    $params = [
                        'event' => Device::EVENT_CHANGE_MEDIA,
                        'message' => 'Cập nhật media trong thiết bị (STORE)',
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

    public function deleteCollection(DeleteCollectionRequest $request)
    {
        DB::beginTransaction();
        try{
            $id = $request->get('id');
            $storeAccountInfo = $request->get('storeAccountInfo');

            $ids = eFunction::arrayInteger($request->get('ids'));
            $device = $this->deviceRepository->getOneObjectDeviceByFilter(['id' => $id, 'deleted_at' => true]);
            if (!empty($ids) && !empty($device)){
                $isActiveDevice = eFunction::activeDevice($device->toArray());
                if (empty($isActiveDevice)){
                    return eResponse::response(STATUS_API_FALSE, __('notification.system.device-blocked'));
                }

                //Lấy ra theo thứ tự của collection
                $storeDeviceCollections = $this->storeDeviceCollectionRepository->getAllStoreDeviceCollectionByFilter(['device_id' => $id, 'store_id' => $storeAccountInfo['store_id'], 'project_id' => $storeAccountInfo['project_id']]);
                if (!empty($storeDeviceCollections)){
                    $totalSecondDeleted = $totalSecond = 0;
                    $arrStoreDeviceCollections = [];
                    foreach ($storeDeviceCollections as $storeDeviceCollection){
                        if (!empty($storeDeviceCollections['second']) && in_array($storeDeviceCollections['id'], $ids)){
                            $totalSecondDeleted = $totalSecondDeleted + (int)$storeDeviceCollections['second'];
                        }

                        if (!in_array($storeDeviceCollection['id'], $ids)){
                            $arrStoreDeviceCollections[] = $storeDeviceCollection;
                        }
                    }

                    if (!empty($arrStoreDeviceCollections)) {
                        //Tổng thời gian còn lại của thiết bị với admin
                        $totalSecond = collect($arrStoreDeviceCollections)->sum('second');

                        $userInstance = new StoreDeviceCollection();
                        $index = 'id';

                        $arrUpdateStoreDeviceCollections = [];
                        foreach ($storeDeviceCollections as $k => $coll){
                            $arrUpdateStoreDeviceCollections[] = [
                                'id' => $coll['id'],
                                'position' => $k + 1
                            ];
                        }

                        if (!empty($arrUpdateStoreDeviceCollections)){
                            \Batch::update($userInstance, $arrUpdateStoreDeviceCollections, $index);
                        }
                    }

                    //update lại thời gian của device còn khả dụng
                    $this->commonService->changeTimeOfDeviceStore($device, $totalSecond, (int)@$device->total_time_empty + $totalSecondDeleted);
                }

                $this->storeDeviceCollectionRepository->delAllStoreDeviceCollectionByFilter(['id' => $ids, 'store_id' => $storeAccountInfo['store_id'], 'project_id' => $storeAccountInfo['project_id']]);

                if (!empty($device->device_code) && isset($device->is_active) && (int)$device->is_active === Device::IS_ACTIVE && !empty($device->status) && (int)$device->status === Device::CONNECT){
                    $params = [
                        'event' => Device::EVENT_CHANGE_MEDIA,
                        'message' => 'Xóa media trong thiết bị (STORE)',
                        'data' => $this->commonService->getDataFromDeviceId($id)
                    ];

                    eFunction::sendMessageQueue($params, $device->device_code);
                }

                $activities = eFunction::getActivity($storeAccountInfo, @$device['id'], @$device['name'], 'delete_media_store', null);
                if (!empty($activities)){
                    $this->logOperationRepository->create($activities);
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

    public function statistic(PaginationRequest $request)
    {
        try{
            $filter = [];
            $limit = $request->limit();
            $storeAccountInfo = $request->get('storeAccountInfo');
            eFunction::FillUpStore($storeAccountInfo, $filter);
            $this->makeFilter($request, $filter);
            if (empty($filter['start_date']) || empty($filter['end_date'])){
                $filter['start_date'] = date('Y-m-01');
                $filter['end_date'] = date('Y-m-t');
            }

            if (!empty($filter['key_word'])){
                $devices = $this->deviceRepository->getAllDeviceByFilter(['store_id' => $storeAccountInfo['store_id'], 'key_word' => $filter['key_word']]);
                if (empty($devices)){
                    return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), []);
                }
            }

            if (!empty($filter['is_export'])){
                $deviceStatistics = $this->deviceStatisticRepository->getAllDeviceStatisticForExportByFilter($filter);
            }else{
                $deviceStatistics = $this->deviceStatisticRepository->getListDeviceStatisticByFilter($limit, $filter);
            }

            $start = new DateTime($filter['start_date']);
            $end = new DateTime($filter['end_date']);
            $end = $end->modify( '+1 day');

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($start, $interval, $end);

            $isSeven = 0;
            $arrDate = $arrList = [];
            foreach ($period as $k => $dt) {
                if ($isSeven === 7){
                    $arrDate[] = $arrList;
                    $isSeven = 0;
                    $arrList = [];
                }

                $isSeven = $isSeven + 1;
                $arrList[] = $dt->format("Y-m-d");
            }

            if (!empty($arrList)){
                $arrDate[] = $arrList;
            }

            $deviceStatistics['arrDate'] = $arrDate;

            if (!empty($deviceStatistics['data'])){
                foreach ($deviceStatistics['data'] as $k => $deviceStatistic){
                    if (!empty($deviceStatistic['device'])){
                        $arrStatistics = [];
                        $totalMedia = $totalTimes = 0;
                        if (!empty($deviceStatistic['admin_device_statistics']) && !empty($arrDate)){
                            foreach ($deviceStatistic['admin_device_statistics'] as $statistic){
                                foreach ($arrDate as $ke => $date){
                                    if (in_array($statistic['date_at'], $date)){
                                        $arrStatistics[$ke]['admin'][] = $statistic;
                                    }
                                }

                                $totalTimes = $totalTimes + $statistic['total_time'];
                            }

                            $totalMedia = $totalMedia + count($deviceStatistic['admin_device_statistics']);
                        }

                        if (!empty($deviceStatistic['store_device_statistics']) && !empty($arrDate)){
                            foreach ($deviceStatistic['store_device_statistics'] as $key => $statistic){
                                foreach ($arrDate as $ke => $date){
                                    if (in_array($statistic['date_at'], $date)){
                                        $arrStatistics[$ke]['store'][] = $statistic;
                                    }
                                }

                                $totalTimes = $totalTimes + $statistic['total_time'];
                            }

                            $totalMedia = $totalMedia + count($deviceStatistic['store_device_statistics']);
                        }

                        if (!empty($deviceStatistic['store_cross_device_statistics']) && !empty($arrDate)){
                            foreach ($deviceStatistic['store_cross_device_statistics'] as $key => $statistic){
                                foreach ($arrDate as $ke => $date){
                                    if (in_array($statistic['date_at'], $date)){
                                        $arrStatistics[$ke]['store_cross'][] = $statistic;
                                    }
                                }

                                $totalTimes = $totalTimes + $statistic['total_time'];
                            }

                            $totalMedia = $totalMedia + count($deviceStatistic['store_cross_device_statistics']);
                        }

                        foreach ($arrDate as $ke => $date){
                            if (!empty($arrStatistics[$ke]['admin'])){
                                $arrStatistics[$ke]['admin'] = collect($arrStatistics[$ke]['admin'])->sum('total_time');
                            }else{
                                $arrStatistics[$ke]['admin'] = 0;
                            }

                            if (!empty($arrStatistics[$ke]['store'])){
                                $arrStatistics[$ke]['store'] = collect($arrStatistics[$ke]['store'])->sum('total_time');
                            }else{
                                $arrStatistics[$ke]['store'] = 0;
                            }

                            if (!empty($arrStatistics[$ke]['store_cross'])){
                                $arrStatistics[$ke]['store_cross'] = collect($arrStatistics[$ke]['store_cross'])->sum('total_time');
                            }else{
                                $arrStatistics[$ke]['store_cross'] = 0;
                            }
                        }

                        $deviceStatistics['data'][$k]['statistic'] = $arrStatistics;

                        unset($deviceStatistics['data'][$k]['store_device_statistics']);
                        unset($deviceStatistics['data'][$k]['store_cross_device_statistics']);
                        unset($deviceStatistics['data'][$k]['admin_device_statistics']);

                        $deviceStatistics['total_times'] = $totalTimes;
                        $deviceStatistics['total_medias'] = $totalMedia;

                    }
                }

                $deviceStatistics['data'] = array_values($deviceStatistics['data']);
            }

            if (!empty($filter['is_export'])){
                $result = $this->excelService->exportToExcel($storeAccountInfo, 'static/exports/'.$storeAccountInfo['slug'].'/file', DeviceStatistic::FILENAME . strtotime('now'), $deviceStatistics);
                return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), ['path'=>$result]);
            }

            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $deviceStatistics);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    private function changeTimeOfDevice($device_id, $totalSecond, $storeAccountInfo, $type)
    {

        $device = $this->deviceRepository->getOneObjectDeviceByFilter(['id' => (int)$device_id, 'deleted_at' => true, 'project_id' => $storeAccountInfo['project_id']]);
        if (!empty($device)){
            if ($type === Device::TYPE_ADD) {
                $totalTimeEmpty = (int)@$device->total_time_empty - $totalSecond;
                $totalSecond = $totalSecond + $device->total_time_store;
            }else{
                $totalTimeEmpty = (int)@$device->total_time_empty + (int)@$device->total_time_store - $totalSecond;
            }

            $this->deviceRepository->update($device, [
                'total_time_store' => $totalSecond,
                'total_time_empty' => $totalTimeEmpty
            ]);
            return true;
        }
        return false;

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
            $filter['status'] = $request->get('status');
        }

        if ($request->has('own')) {
            $filter['own'] = $request->get('own');
        }

        if ($request->has('start_date')) {
            $filter['start_date'] = $request->get('start_date');
        }

        if ($request->has('end_date')) {
            $filter['end_date'] = $request->get('end_date');
        }

        if ($request->has('is_export')) {
            $filter['is_export'] = $request->get('is_export');
        }
    }

    private function makeFilterStatus($request, &$filter)
    {
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
    }

}
