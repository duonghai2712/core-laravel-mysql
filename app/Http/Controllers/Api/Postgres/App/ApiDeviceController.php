<?php

namespace App\Http\Controllers\Api\Postgres\App;

use App\Elibs\eCrypt;
use App\Elibs\eFunction;
use App\Http\Controllers\Controller;
use App\Elibs\eResponse;
use App\Http\Requests\Api\Postgres\App\CheckStatusCollectionDownloadRequest;
use App\Http\Requests\Api\Postgres\App\StatisticRequest;
use App\Http\Requests\BaseRequest;
use App\Models\Postgres\Admin\Device;
use App\Repositories\Postgres\Admin\AdminDeviceImageRepositoryInterface;
use App\Repositories\Postgres\Admin\DeviceLoadingStatusRepositoryInterface;
use App\Repositories\Postgres\Admin\ImageLoadingStatusRepositoryInterface;
use App\Repositories\Postgres\Store\CollectionCrossLoadingStatusRepositoryInterface;
use App\Repositories\Postgres\Store\CollectionLoadingStatusRepositoryInterface;
use App\Repositories\Postgres\Store\OrderDeviceRepositoryInterface;
use App\Repositories\Postgres\Store\StoreDeviceCollectionRepositoryInterface;
use App\Services\CommonServiceInterface;
use Illuminate\Support\Str;
use App\Http\Requests\Api\Postgres\App\SignInRequest;
use App\Repositories\Postgres\Admin\DeviceRepositoryInterface;
use  DB;


class ApiDeviceController extends Controller
{

    protected $deviceRepository;
    protected $imageLoadingStatusRepository;
    protected $deviceLoadingStatusRepository;
    protected $collectionLoadingStatusRepository;
    protected $collectionCrossLoadingStatusRepository;
    protected $adminDeviceImageRepository;
    protected $storeDeviceCollectionRepository;
    protected $orderDeviceRepository;
    protected $commonService;

    public function __construct(
        DeviceRepositoryInterface $deviceRepository,
        CommonServiceInterface $commonService,
        AdminDeviceImageRepositoryInterface $adminDeviceImageRepository,
        ImageLoadingStatusRepositoryInterface $imageLoadingStatusRepository,
        DeviceLoadingStatusRepositoryInterface $deviceLoadingStatusRepository,
        CollectionLoadingStatusRepositoryInterface $collectionLoadingStatusRepository,
        StoreDeviceCollectionRepositoryInterface $storeDeviceCollectionRepository,
        CollectionCrossLoadingStatusRepositoryInterface $collectionCrossLoadingStatusRepository,
        OrderDeviceRepositoryInterface $orderDeviceRepository
    )
    {
        $this->deviceRepository = $deviceRepository;
        $this->imageLoadingStatusRepository = $imageLoadingStatusRepository;
        $this->deviceLoadingStatusRepository = $deviceLoadingStatusRepository;
        $this->collectionLoadingStatusRepository = $collectionLoadingStatusRepository;
        $this->collectionCrossLoadingStatusRepository = $collectionCrossLoadingStatusRepository;
        $this->commonService = $commonService;
        $this->adminDeviceImageRepository = $adminDeviceImageRepository;
        $this->storeDeviceCollectionRepository = $storeDeviceCollectionRepository;
        $this->orderDeviceRepository = $orderDeviceRepository;
    }

    public function index(SignInRequest $request)
    {
        DB::beginTransaction();
        try{
            $data = $request->only(
                [
                    'device_code',
                    'active_code',
                    'model',
                    'width',
                    'height',
                    'size',
                    'os'
                ]
            );


            $deviceActiveCode = $this->deviceRepository->getOneObjectDeviceByFilter(['active_code' => $data['active_code'], 'deleted_at' => true]);
            if (empty($deviceActiveCode)){
                return eResponse::response(STATUS_API_ERROR, __('notification.system.active-code-device-not-found'), []);
            }

            if (!empty($deviceActiveCode) && !empty($deviceActiveCode['device_code']) && $deviceActiveCode['device_code'] !== $data['device_code']){
                return eResponse::response(STATUS_API_ERROR, __('notification.system.exists-device'), []);
            }

            $deviceCode = $this->deviceRepository->getOneObjectDeviceByFilter(['device_code' => $data['device_code'], 'deleted_at' => true]);
            if (!empty($deviceCode) && !empty($deviceCode['active_code']) && (int)$deviceCode['active_code'] !== (int)$data['active_code']){
                return eResponse::response(STATUS_API_ERROR, __('notification.system.exists-device'), []);
            }

            $data['device_token'] = Str::random(31);
            $data['status'] = Device::CONNECT;

            $this->deviceRepository->update($deviceActiveCode, $data);

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), ['device_token' =>  $data['device_token']]);

        }catch(\Exception $e){
            DB::rollback();
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }

    }

    public function download(BaseRequest $request)
    {
        $device_info = $request->get('device_info');
        \Log::info('Device download : ' . implode(', ', $device_info));
        $params = $this->commonService->getDataFromDeviceId($device_info['id']);
        return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), ['data' => eCrypt::encryptAES(json_encode($params, true))]);
    }

    public function checkStatusCollectionDownload(CheckStatusCollectionDownloadRequest $request)
    {
        try {
            DB::beginTransaction();
            $device_info = $request->get('device_info');
            $data = json_decode(eCrypt::decryptAES($request->get('data')));
            if (!empty($data)){
                $deviceLoadingStatus = $this->deviceLoadingStatusRepository->getOneObjectDeviceLoadingStatusByFilter(['device_id' => (int)$device_info['id'], 'project_id' => (int)$device_info['project_id']]);
                if (empty($deviceLoadingStatus)){
                    $deviceLoadingStatus = $this->deviceLoadingStatusRepository->create([
                        'device_id' =>  (int)$device_info['id'],
                        'store_id' =>  (int)$device_info['store_id'],
                        'branch_id' =>  (int)$device_info['branch_id'],
                        'project_id' =>  (int)$device_info['project_id'],
                    ]);
                }

                if (!empty($deviceLoadingStatus)){
                    $arrImageLoading = $arrCollectionLoading = $arrCollectionCrossLoading = [];
                    foreach ($data as $val){
                        if (!empty($val->own)){
                            if ((int)$val->own === Device::OWN_DOWNLOAD_ANT){
                                $arrImageLoading[] = [
                                    'device_id' => $device_info['id'],
                                    'project_id' => $device_info['project_id'],
                                    'device_loading_status_id' => (int)@$deviceLoadingStatus->id,
                                    'image_id' => (int)@$val->collection_id,
                                    'type' => (int)@$val->type,
                                    'status' => (int)@$val->status,
                                    'store_id' => $device_info['store_id'],
                                    'branch_id' => $device_info['branch_id'],
                                    'time_at' => date('H:i:s', strtotime('now')),
                                    'date_at' => date('Y-m-d', strtotime('now')),
                                    'created_at' => eFunction::getDateTimeNow(),
                                    'updated_at' =>  eFunction::getDateTimeNow(),
                                ];

                            }elseif ((int)$val->own === Device::OWN_DOWNLOAD_STORE){
                                $arrCollectionLoading[] = [
                                    'device_id' => $device_info['id'],
                                    'project_id' => $device_info['project_id'],
                                    'device_loading_status_id' => (int)@$deviceLoadingStatus->id,
                                    'collection_id' => (int)@$val->collection_id,
                                    'type' => (int)@$val->type,
                                    'status' => (int)@$val->status,
                                    'store_id' => $device_info['store_id'],
                                    'branch_id' => $device_info['branch_id'],
                                    'time_at' => date('H:i:s', strtotime('now')),
                                    'date_at' => date('Y-m-d', strtotime('now')),
                                    'created_at' => eFunction::getDateTimeNow(),
                                    'updated_at' =>  eFunction::getDateTimeNow(),
                                ];

                            }elseif ((int)$val->own === Device::OWN_DOWNLOAD_STORE_CROSS){
                                $arrCollectionCrossLoading[] = [
                                    'device_id' => $device_info['id'],
                                    'project_id' => $device_info['project_id'],
                                    'device_loading_status_id' => (int)@$deviceLoadingStatus->id,
                                    'collection_id' => (int)@$val->collection_id,
                                    'type' => (int)@$val->type,
                                    'order_id' => (int)@$val->order_id,
                                    'status' => (int)@$val->status,
                                    'store_id' => $device_info['store_id'],
                                    'branch_id' => $device_info['branch_id'],
                                    'time_at' => date('H:i:s', strtotime('now')),
                                    'date_at' => date('Y-m-d', strtotime('now')),
                                    'created_at' => eFunction::getDateTimeNow(),
                                    'updated_at' =>  eFunction::getDateTimeNow(),
                                ];
                            }
                        }
                    }

                    if (!empty($arrImageLoading)){
                        $this->imageLoadingStatusRepository->insertMulti($arrImageLoading);
                    }

                    if (!empty($arrCollectionLoading)){
                        $this->collectionLoadingStatusRepository->insertMulti($arrCollectionLoading);
                    }

                    if (!empty($arrCollectionCrossLoading)){
                        $this->collectionCrossLoadingStatusRepository->insertMulti($arrCollectionCrossLoading);
                    }
                }
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-update-success'), ['data' =>  __('notification.api-form-update-success')]);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'));
        }
    }
}
