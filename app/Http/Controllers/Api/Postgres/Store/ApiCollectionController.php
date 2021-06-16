<?php

namespace App\Http\Controllers\Api\Postgres\Store;

use App\Elibs\eFunction;
use App\Http\Controllers\Controller;
use App\Elibs\eResponse;
use App\Http\Requests\Api\PaginationRequest;
use App\Http\Requests\Api\Postgres\Store\Collection\CreateCollectionRequest;
use App\Http\Requests\Api\Postgres\Store\Collection\DeleteCollectionRequest;
use App\Models\Postgres\Admin\Device;
use App\Models\Postgres\Store\Collection;
use App\Models\Postgres\Store\StoreCrossDeviceCollection;
use App\Repositories\Postgres\Store\CollectionRepositoryInterface;
use App\Repositories\Postgres\Store\LogOperationRepositoryInterface;
use App\Repositories\Postgres\Store\StoreCrossDeviceCollectionRepositoryInterface;
use App\Repositories\Postgres\Store\StoreDeviceCollectionRepositoryInterface;
use App\Services\Postgres\Store\FileUploadCollectionServiceInterface;
use  DB;


class ApiCollectionController extends Controller
{

    protected $logOperationRepository;
    protected $collectionRepository;
    protected $storeCrossDeviceCollectionRepository;
    protected $storeDeviceCollectionRepository;
    protected $fileUploadCollectionService;

    public function __construct(
        CollectionRepositoryInterface $collectionRepository,
        LogOperationRepositoryInterface $logOperationRepository,
        StoreCrossDeviceCollectionRepositoryInterface $storeCrossDeviceCollectionRepository,
        StoreDeviceCollectionRepositoryInterface $storeDeviceCollectionRepository,
        FileUploadCollectionServiceInterface $fileUploadCollectionService
    )
    {
        $this->storeDeviceCollectionRepository = $storeDeviceCollectionRepository;
        $this->collectionRepository = $collectionRepository;
        $this->logOperationRepository = $logOperationRepository;
        $this->storeCrossDeviceCollectionRepository = $storeCrossDeviceCollectionRepository;
        $this->fileUploadCollectionService = $fileUploadCollectionService;
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
            $filter['level'] = Collection::COLLECTION;

            $collections = $this->collectionRepository->getListCollectionByFilter($limit, $filter);
            if (!empty($collections['data'])){
                $idsCollection = collect($collections['data'])->pluck('id')->values()->toArray();
                $storeCrossCollections = $this->storeCrossDeviceCollectionRepository->getAllStoreCrossDeviceCollectionByFilter(['collection_id' => $idsCollection, 'status' => [StoreCrossDeviceCollection::COLLECTION_STATUS_WAIT, StoreCrossDeviceCollection::COLLECTION_STATUS_CONFIRMED], 'project_id' => $storeAccountInfo['project_id']]);
                $keyByCollectionID = collect($storeCrossCollections)->keyBy('collection_id')->toArray();
                $collections = eFunction::addFullUrlImageAndCheckDevice($collections, $keyByCollectionID);
            }

            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $collections);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function create(CreateCollectionRequest $request)
    {

        DB::beginTransaction();
        try{
            $files = $request->file('files');
            $storeAccountInfo = $request->get('storeAccountInfo');

            $collections = [];
            $isUploaded = false;
            foreach ($files as $file){
                $collection = $this->fileUploadCollectionService->upload('store_collection', $file, $storeAccountInfo, Collection::COLLECTION);
                if (!empty($collection)){
                    $isUploaded = eFunction::checkFileSize($collection);
                    $collections[] = $collection;
                }
            }

            if (!empty($isUploaded)){
                return eResponse::response(STATUS_API_ERROR, __('notification.api-form-max'));
            }

            if (!empty($collections)){
                $this->collectionRepository->createMulti($collections);
            }

            $activities = eFunction::getActivity($storeAccountInfo, null, '', 'create_collection', null);
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

    public function delete(DeleteCollectionRequest $request)
    {
        try{

            DB::beginTransaction();

            $storeAccountInfo = $request->get('storeAccountInfo');

            $ids = eFunction::arrayInteger($request->get('ids'));
            if (!empty($ids)){
                $collections = $this->storeDeviceCollectionRepository->countAllStoreDeviceCollectionsByFilter(['collection_id' => $ids, 'store_id' => $storeAccountInfo['store_id']]);
                $collectionCross = $this->storeCrossDeviceCollectionRepository->countAllStoreCrossDeviceCollectionByFilter(['collection_id' => $ids, 'status' =>[StoreCrossDeviceCollection::COLLECTION_STATUS_WAIT, StoreCrossDeviceCollection::COLLECTION_STATUS_CONFIRMED], 'project_id' => $storeAccountInfo['project_id']]);
                if (!empty($collections) || !empty($collectionCross)){
                    return eResponse::response(STATUS_API_ERROR, __('notification.system.data-using'));
                }

                $this->collectionRepository->deleteAllCollectionByFilter(['id' => $ids, 'store_id' => $storeAccountInfo['store_id'], 'project_id' => $storeAccountInfo['project_id'], 'deleted_at' => true]);
            }

            $activities = eFunction::getActivity($storeAccountInfo, null, '', 'delete_collection', null);
            if (!empty($activities)){
                $this->logOperationRepository->create($activities);
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-delete-success'), []);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
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
