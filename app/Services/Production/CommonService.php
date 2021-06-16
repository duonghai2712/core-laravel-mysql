<?php namespace App\Services\Production;

use App\Elibs\eCrypt;
use App\Elibs\eFunction;
use App\Models\Postgres\Admin\Device;
use App\Models\Postgres\Admin\Store;
use App\Models\Postgres\Store\LogPoint;
use App\Models\Postgres\Store\Order;
use App\Models\Postgres\Store\OrderDevice;
use App\Models\Postgres\Store\StoreAccount;
use App\Models\Postgres\Store\StoreCrossDeviceCollection;
use App\Repositories\Postgres\Admin\AdminDeviceImageRepositoryInterface;
use App\Repositories\Postgres\Admin\AdminDeviceStatisticRepositoryInterface;
use App\Repositories\Postgres\Admin\BranchRepositoryInterface;
use App\Repositories\Postgres\Admin\DeviceRepositoryInterface;
use App\Repositories\Postgres\Admin\DeviceStatisticRepositoryInterface;
use App\Repositories\Postgres\Admin\StoreRepositoryInterface;
use App\Repositories\Postgres\Store\LogPointRepositoryInterface;
use App\Repositories\Postgres\Store\OrderDeviceRepositoryInterface;
use App\Repositories\Postgres\Store\OrderRepositoryInterface;
use App\Repositories\Postgres\Store\StoreAccountRepositoryInterface;
use App\Repositories\Postgres\Store\StoreCrossDeviceCollectionRepositoryInterface;
use App\Repositories\Postgres\Store\StoreCrossDeviceStatisticRepositoryInterface;
use App\Repositories\Postgres\Store\StoreDeviceCollectionRepositoryInterface;
use App\Repositories\Postgres\Store\StoreDeviceStatisticRepositoryInterface;
use App\Repositories\Postgres\Store\TimeFrameRepositoryInterface;
use \App\Services\CommonServiceInterface;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class CommonService extends BaseService implements CommonServiceInterface
{
    protected $storeRepository;
    protected $branchRepository;
    protected $storeAccountRepository;
    protected $logPointRepository;
    protected $adminDeviceImageRepository;
    protected $orderDeviceRepository;
    protected $timeFrameRepository;
    protected $storeCrossDeviceCollectionRepository;
    protected $storeDeviceCollectionRepository;
    protected $deviceRepository;
    protected $orderRepository;
    protected $deviceStatisticRepository;
    protected $adminDeviceStatisticRepository;
    protected $storeDeviceStatisticRepository;
    protected $storeCrossDeviceStatisticRepository;


    public function __construct(
        AdminDeviceImageRepositoryInterface $adminDeviceImageRepository,
        LogPointRepositoryInterface $logPointRepository,
        BranchRepositoryInterface $branchRepository,
        StoreAccountRepositoryInterface $storeAccountRepository,
        OrderRepositoryInterface $orderRepository,
        OrderDeviceRepositoryInterface $orderDeviceRepository,
        TimeFrameRepositoryInterface $timeFrameRepository,
        DeviceRepositoryInterface $deviceRepository,
        StoreRepositoryInterface $storeRepository,
        DeviceStatisticRepositoryInterface $deviceStatisticRepository,
        AdminDeviceStatisticRepositoryInterface $adminDeviceStatisticRepository,
        StoreDeviceStatisticRepositoryInterface $storeDeviceStatisticRepository,
        StoreCrossDeviceStatisticRepositoryInterface $storeCrossDeviceStatisticRepository,
        StoreCrossDeviceCollectionRepositoryInterface $storeCrossDeviceCollectionRepository,
        StoreDeviceCollectionRepositoryInterface $storeDeviceCollectionRepository
    )
    {
        $this->adminDeviceImageRepository = $adminDeviceImageRepository;
        $this->orderDeviceRepository = $orderDeviceRepository;
        $this->timeFrameRepository = $timeFrameRepository;
        $this->logPointRepository = $logPointRepository;
        $this->storeCrossDeviceCollectionRepository = $storeCrossDeviceCollectionRepository;
        $this->storeDeviceCollectionRepository = $storeDeviceCollectionRepository;
        $this->deviceRepository = $deviceRepository;
        $this->storeRepository = $storeRepository;
        $this->orderRepository = $orderRepository;
        $this->branchRepository = $branchRepository;
        $this->storeAccountRepository = $storeAccountRepository;
        $this->deviceStatisticRepository = $deviceStatisticRepository;
        $this->adminDeviceStatisticRepository = $adminDeviceStatisticRepository;
        $this->storeDeviceStatisticRepository = $storeDeviceStatisticRepository;
        $this->storeCrossDeviceStatisticRepository = $storeCrossDeviceStatisticRepository;
    }

    public function getDataFromDeviceId($device_id)
    {
        $params = [];
        $adminDeviceImages = $this->adminDeviceImageRepository->getAllAdminDeviceImageWithImagesByFilter(['device_id' => $device_id]);
        if (!empty($adminDeviceImages)){
            foreach ($adminDeviceImages as $val){
                if (!empty($val['image']['source'])){
                    $params[] = [
                        'second' => (int)@$val['second'],
                        'type' => (int)@$val['type'],
                        'volume' => (int)@$val['volume'],
                        'own' => Device::OWN_DOWNLOAD_ANT,
                        'device_id' => (int)$device_id,
                        'unique_id' => @(string)$val['image_id'] . '-ant-' . (string)$device_id . '-' . Device::OWN_DOWNLOAD_ANT,
                        'collection_id' => (int)@$val['image_id'],
                        'order_id' => Device::NONE,
                        'source' => asset($val['image']['source']),
                        'block_ads' => (int)@$val['device']['block_ads'],
                        'store_id' => (int)@$val['device']['store_id'],
                        'branch_id' => (int)@$val['device']['branch_id'],
                        'rank_id' => (int)@$val['device']['branch']['rank_id'],
                        'start_date' => "",
                        'end_date' => "",
                        'start_time' => "",
                        'end_time' => ""
                    ];
                }
            }
        }

        $storeDeviceCollections = $this->storeDeviceCollectionRepository->getAllStoreDeviceCollectionWithCollectionsByFilter(['device_id' => $device_id]);
        if (!empty($storeDeviceCollections)){
            foreach ($storeDeviceCollections as $val){
                if (!empty($val['collection']['source'])){
                    $params[] = [
                        'second' => (int)@$val['second'],
                        'type' => (int)@$val['type'],
                        'volume' => (int)@$val['volume'],
                        'own' => Device::OWN_DOWNLOAD_STORE,
                        'device_id' => (int)$device_id,
                        'collection_id' => (int)@$val['collection_id'],
                        'order_id' => Device::NONE,
                        'unique_id' => @(string)$val['collection_id'] . '-ant-' . (string)$device_id . '-' . Device::OWN_DOWNLOAD_STORE,
                        'source' => asset($val['collection']['source']),
                        'block_ads' => (int)@$val['device']['block_ads'],
                        'store_id' => (int)@$val['device']['store_id'],
                        'branch_id' => (int)@$val['device']['branch_id'],
                        'rank_id' => (int)@$val['device']['branch']['rank_id'],
                        'start_date' => "",
                        'end_date' => "",
                        'start_time' => "",
                        'end_time' => ""
                    ];
                }
            }
        }

        $orderDevices = $this->orderDeviceRepository->getAllOrderDeviceForAppByFilter(['device_id' => $device_id, 'status' => OrderDevice::STATUS_USING]);
        if (!empty($orderDevices)){
            $orderDevicesKeyByOrderID = collect($orderDevices)->keyBy('order_id')->toArray();
            $idsOrders = collect($orderDevices)->pluck('order_id')->filter()->unique()->values()->toArray();
            $orders = $this->orderRepository->getAllOrderByFilter(['id' => $idsOrders, 'status' => Order::CONFIRMED]);
            if (!empty($orders)){
                $idsRealOrders = collect($orders)->pluck('id')->values()->toArray();
                if (!empty($idsRealOrders)){
                    $timeFrames = $this->timeFrameRepository->getAllTimeFramesByFilter(['order_id' => $idsRealOrders]);
                    $storeCrossDeviceCollections = $this->storeCrossDeviceCollectionRepository->getAllStoreCrossDeviceCollectionByFilter(['order_id' => $idsRealOrders, 'status' => StoreCrossDeviceCollection::COLLECTION_STATUS_CONFIRMED]);
                    if (!empty($timeFrames) && !empty($storeCrossDeviceCollections)){
                        foreach ($storeCrossDeviceCollections as $key => $collection){
                            if (!empty($collection['collection']['source'])) {
                                foreach ($timeFrames as $k => $time) {
                                    if (!empty($collection['order_id']) && !empty($time['order_id']) && (int)$collection['order_id'] === (int)$time['order_id']){
                                        $unique = @(string)$collection['order_id'] . '-a-' . @(string)$collection['collection_id'] . '-n-' . (string)$collection['id'] . '-t-' . (string)$time['id'];
                                        $params[] = [
                                            'second' => (int)@$collection['second'],
                                            'type' => (int)@$collection['type'],
                                            'volume' => (int)@$collection['volume'],
                                            'own' => Device::OWN_DOWNLOAD_STORE_CROSS,
                                            'device_id' => (int)$device_id,
                                            'unique_id' => $unique,
                                            'collection_id' => (int)@$collection['collection_id'],
                                            'source' => asset($collection['collection']['source']),
                                            'order_id' => (int)@$collection['order_id'],
                                            'block_ads' => !empty($orderDevicesKeyByOrderID[@$collection['order_id']]['block_time']) ? (int)@$orderDevicesKeyByOrderID[@$collection['order_id']]['block_time'] : Device::NONE,
                                            'store_id' => !empty($orderDevicesKeyByOrderID[@$collection['order_id']]['device']['store_id']) ? (int)@$orderDevicesKeyByOrderID[@$collection['order_id']]['device']['store_id'] : Device::NONE,
                                            'branch_id' => !empty($orderDevicesKeyByOrderID[@$collection['order_id']]['device']['branch_id']) ? (int)@$orderDevicesKeyByOrderID[@$collection['order_id']]['device']['branch_id'] : Device::NONE,
                                            'rank_id' => !empty($orderDevicesKeyByOrderID[@$collection['order_id']]['device']['branch']['rank_id']) ? (int)@$orderDevicesKeyByOrderID[@$collection['order_id']]['device']['branch']['rank_id'] : Device::NONE,
                                            'start_date' => trim(@$time['start_date']),
                                            'end_date' => trim(@$time['end_date']),
                                            'start_time' => trim(@$time['start_time']),
                                            'end_time' => trim(@$time['end_time']),
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }


        return $params;
    }

    public function isActiveDevice($device)
    {
        if (isset($device['is_active']) && (int)$device['is_active'] === Device::IS_ACTIVE){
            return true;
        }

        return false;
    }

    public function getMessageQueueRabbit($queue_name)
    {
        $connection = new AMQPStreamConnection(env('RABBITMQ_HOST', 'localhost'), env('RABBITMQ_PORT', 5672),  env('RABBITMQ_USER', 'guest'),  env('RABBITMQ_PASSWORD', 'guest'));
        $channel = $connection->channel();
        $channel->queue_declare($queue_name, true, true, false, false);

        $callback = function ($msg) {
            \Log::info('Received Data');
            if (!empty($msg->body)){
                self::dataProcessing($msg->body);
            }

            $msg->ack();
        };


        $channel->basic_qos(null, 1, null);
        $channel->basic_consume($queue_name, '', false, false, false, false, $callback);

        while ($channel->is_open()) {
            $channel->wait();
        }
    }

    public function dataProcessing($params)
    {
        $params = json_decode($params);
        $device_code = @$params[0]->device_id;
        $data = !empty($params[0]->data) ? eCrypt::decryptAES($params[0]->data) : json_encode([]);
        if (!empty($device_code)){
            $device = $this->deviceRepository->getOneArrayDeviceForAppByFilter(['device_code' => $device_code, 'deleted_at' => true]);
            if (empty($device)){
                $device = $this->deviceRepository->getOneArrayDeviceForAppByFilter(['device_code' => $device_code, 'isDelete' => true]);
            }

            if (!empty($device)){
                $deviceStatistic = $this->deviceStatisticRepository->getOneObjectDeviceStatisticByFilter(['device_id' => $device['id']]);
                if (empty($deviceStatistic)){
                    $deviceStatistic = $this->deviceStatisticRepository->create([
                        'store_id' => @(int)$device['store_id'],
                        'branch_id' => @(int)$device['branch_id'],
                        'device_id' => @(int)$device['id'],
                        'project_id' => @(int)$device['project_id']
                    ]);
                }

                if (!empty($deviceStatistic)){
                    $data = json_decode($data);
                    $arrAdmin = $arrStore = $arrStoreCross = [];
                    if (!empty($data)){
                        foreach ($data as $val){
                            if (!empty($val->own)){
                                if ((int)$val->own === Device::OWN_DOWNLOAD_ANT){
                                    $arrAdmin[] = [
                                        'device_id' => $device['id'],
                                        'project_id' => @$device['project_id'],
                                        'device_statistic_id' => (int)@$deviceStatistic->id,
                                        'image_id' => (int)@$val->collection_id,
                                        'type' => (int)@$val->type,
                                        'store_id' => (int)@$val->store_id,
                                        'branch_id' => (int)@$val->branch_id,
                                        'rank_id' => (int)@$val->rank_id,
                                        'date_at' => @$val->date_at,
                                        'second' => (int)@$val->second,
                                        'total_time' => (int)@$val->total_time,
                                        'number_time' => (int)@$val->number_times,
                                        'created_at' => eFunction::getDateTimeNow(),
                                        'updated_at' =>  eFunction::getDateTimeNow(),
                                    ];

                                }elseif ((int)$val->own === Device::OWN_DOWNLOAD_STORE){
                                    $arrStore[] = [
                                        'device_id' => $device['id'],
                                        'project_id' => @$device['project_id'],
                                        'device_statistic_id' => (int)@$deviceStatistic->id,
                                        'collection_id' => (int)@$val->collection_id,
                                        'type' => (int)@$val->type,
                                        'store_id' => (int)@$val->store_id,
                                        'branch_id' => (int)@$val->branch_id,
                                        'rank_id' => (int)@$val->rank_id,
                                        'date_at' => @$val->date_at,
                                        'second' => (int)@$val->second,
                                        'total_time' => (int)@$val->total_time,
                                        'number_time' => (int)@$val->number_times,
                                        'created_at' => eFunction::getDateTimeNow(),
                                        'updated_at' =>  eFunction::getDateTimeNow(),
                                    ];

                                }elseif ((int)$val->own === Device::OWN_DOWNLOAD_STORE_CROSS){
                                    $arrStoreCross[] = [
                                        'device_id' => $device['id'],
                                        'project_id' => @$device['project_id'],
                                        'device_statistic_id' => (int)@$deviceStatistic->id,
                                        'collection_id' => (int)@$val->collection_id,
                                        'type' => (int)@$val->type,
                                        'store_id' => (int)@$val->store_id,
                                        'branch_id' => (int)@$val->branch_id,
                                        'order_id' => (int)@$val->order_id,
                                        'rank_id' => (int)@$val->rank_id,
                                        'date_at' => @$val->date_at,
                                        'second' => (int)@$val->second,
                                        'total_time' => (int)@$val->total_time,
                                        'number_time' => (int)@$val->number_times,
                                        'created_at' => eFunction::getDateTimeNow(),
                                        'updated_at' =>  eFunction::getDateTimeNow(),
                                    ];
                                }
                            }
                        }
                    }

                    $totalSecondGetMoney = Device::MAX_TIME_STORE - (int)$device['block_ads'];//Nếu mà store không có thời gian và không có quảng cáo chéo thì cửa hàng được công tiền 3 phút
                    \Log::info('Device Code 1 : ' . $device_code . ' - Second 1 : ' . $totalSecondGetMoney . ' - Coefficient 1 : ' . @(int)$device['branch']['rank']['coefficient']);

                    if (!empty($arrStore)){
                        $totalSeconds = collect($arrStore)->sum('second');
                        if ($totalSeconds >= (int)$device['block_ads']){
                            $totalSecondGetMoney = $totalSecondGetMoney - $totalSeconds + (int)$device['block_ads'];
                        }
                    }

                    \Log::info('Device Code 2 : ' . $device_code . ' - Second 2 : ' . $totalSecondGetMoney . ' - Coefficient 2 : ' . @(int)$device['branch']['rank']['coefficient']);

                    if (!empty($arrStoreCross)){
                        $totalSecondCrosses = collect($arrStoreCross)->sum('second');
                        if (($totalSecondCrosses + (int)$device['block_ads']) <= Device::MAX_TIME_STORE && $totalSecondGetMoney > $totalSecondCrosses){
                            $totalSecondGetMoney = $totalSecondGetMoney - $totalSecondCrosses;
                        }else{
                            $totalSecondGetMoney = 0;
                        }
                    }

                    \Log::info('Device Code 3 : ' . $device_code . ' - Second 3 : ' . $totalSecondGetMoney . ' - Coefficient 3 : ' . @(int)$device['branch']['rank']['coefficient']);

                    if (!empty($totalSecondGetMoney) && !empty($device['branch']['rank']['coefficient'])){
                        \Log::info('Log Have time : 1');
                        $pointFromSecond = eFunction::getPointPlusForStore((int)$device['branch']['rank']['coefficient'], $totalSecondGetMoney);
                        $branch = $this->branchRepository->getOneObjectBranchByFilter(['id' => (int)$device['branch_id'], 'deleted_at' => true]);
                        \Log::info('Make Ads : ' . $branch->make_ads . ' Branch ID : ' . (int)$device['branch_id']);
                        if (!empty($branch) && !empty($branch->make_ads) && (int)$branch->make_ads === StoreAccount::MAKE_ADS_TRUE){
                            if (!empty($branch->debt_point)){
                                if (($pointFromSecond - $branch->debt_point) >= 0){
                                    $params['current_point'] = $branch->current_point + $pointFromSecond - $branch->debt_point;
                                    $params['debt_point'] = 0;
                                }else{
                                    $params['debt_point'] = $branch->debt_point - $pointFromSecond;
                                }
                            }else{
                                $params['current_point'] = $branch->current_point + $pointFromSecond;
                            }

                            $params['total_point'] = $branch->total_point + $pointFromSecond;

                            \Log::info('Code 4 Make Ads : ' . $branch->make_ads . ' Branch ID : ' . (int)$device['branch_id']);

                            $this->branchRepository->update($branch, $params);
                        }else{
                            $store = $this->storeRepository->getOneObjectStoreByFilter(['id' => (int)$device['store_id'], 'deleted_at' => true]);
                            if(!empty($store)){
                                if (!empty($store->debt_point)){
                                    if (($pointFromSecond - $store->debt_point) >= 0){
                                        $params['current_point'] = $store->current_point + $pointFromSecond - $store->debt_point;
                                        $params['debt_point'] = 0;
                                    }else{
                                        $params['debt_point'] = $store->debt_point - $pointFromSecond;
                                    }
                                }else{
                                    $params['current_point'] = $store->current_point + $pointFromSecond;
                                }

                                $params['total_point'] = $store->total_point + $pointFromSecond;
                            }

                            $this->storeRepository->update($store, $params);
                        }

                        //Thêm hoạt đông cộng tiền tiền của cửa hàng
                        $activity = [
                            'type' => LogPoint::TYPE_BONUS_POINT,
                            'time' => $totalSecondGetMoney,
                            'point' => eFunction::getPointPlusForStore(@(int)$device['branch']['rank']['coefficient'], $totalSecondGetMoney),
                            'transaction' => LogPoint::TRANSACTION['plus_ant'],
                            'store_id' => (int)$device['store_id'],
                            'device_id' => $device['id'],
                            'branch_id' => (int)$device['branch_id'],
                            'project_id' => (int)@$device['project_id'],
                        ];

                        $this->logPointRepository->create($activity);
                    }

                    if (!empty($arrAdmin)){
                        $this->adminDeviceStatisticRepository->insertMulti($arrAdmin);
                    }

                    if (!empty($arrStore)){
                        $this->storeDeviceStatisticRepository->insertMulti($arrStore);
                    }

                    if (!empty($arrStoreCross)){
                        $this->storeCrossDeviceStatisticRepository->insertMulti($arrStoreCross);
                    }
                }
            }
        }
    }

    public function changeTimeOfDeviceAdmin($device, $totalTimeAdmin, $totalTimeEmpty)
    {
        $this->deviceRepository->update($device, [
            'total_time_admin' => $totalTimeAdmin,
            'total_time_empty' => $totalTimeEmpty
        ]);
    }

    public function changeTimeOfDeviceStore($device, $totalTimeStore, $totalTimeEmpty)
    {
        $this->deviceRepository->update($device, [
            'total_time_store' => $totalTimeStore,
            'total_time_empty' => $totalTimeEmpty
        ]);
    }
}
