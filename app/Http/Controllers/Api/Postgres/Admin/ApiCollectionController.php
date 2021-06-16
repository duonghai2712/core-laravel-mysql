<?php

namespace App\Http\Controllers\Api\Postgres\Admin;

use App\Elibs\eFunction;
use App\Http\Controllers\Controller;
use App\Elibs\eResponse;
use App\Http\Requests\Api\PaginationRequest;
use App\Http\Requests\Api\Postgres\Admin\Collection\CreateCollectionRequest;
use App\Http\Requests\Api\Postgres\Admin\Collection\CreateMediaRequest;
use App\Http\Requests\Api\Postgres\Admin\Collection\DeleteCollectionRequest;
use App\Http\Requests\Api\Postgres\Admin\Collection\DeleteMediaRequest;
use App\Http\Requests\Api\Postgres\Admin\Collection\PaginationCollectionRequest;
use App\Models\Postgres\Admin\Image;
use App\Models\Postgres\Admin\Owner;
use App\Repositories\Postgres\Admin\AdminDeviceImageRepositoryInterface;
use App\Repositories\Postgres\Admin\ImageRepositoryInterface;
use App\Repositories\Postgres\Admin\OwnerRepositoryInterface;
use App\Services\Postgres\Admin\FileUploadServiceInterface;
use DateInterval;
use DatePeriod;
use DateTime;
use  DB;

class ApiCollectionController extends Controller
{

    protected $imageRepository;
    protected $adminDeviceImageRepository;
    protected $ownerRepository;
    protected $fileUploadService;
    public function __construct(
        ImageRepositoryInterface $imageRepository,
        AdminDeviceImageRepositoryInterface $adminDeviceImageRepository,
        OwnerRepositoryInterface $ownerRepository,
        FileUploadServiceInterface $fileUploadService
    )
    {
        $this->adminDeviceImageRepository = $adminDeviceImageRepository;
        $this->imageRepository = $imageRepository;
        $this->ownerRepository = $ownerRepository;
        $this->fileUploadService = $fileUploadService;
    }

    public function index(PaginationRequest $request)
    {
        try{
            $filter = [];
            $limit = $request->limit();
            $this->makeFilter($request, $filter);
            $accountInfo = $request->get('accountInfo');
            $filter['deleted_at'] = true;
            $filter['project_id'] = $accountInfo['project_id'];

            $owners = $this->ownerRepository->getListOwnerByFilter($limit, $filter);
            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $owners);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function listMedia(PaginationCollectionRequest $request)
    {

        try{
            $filter = [];
            $limit = $request->limit();
            $this->makeFilter($request, $filter);
            $accountInfo = $request->get('accountInfo');
            $filter['deleted_at'] = true;
            $filter['level'] = Image::COLLECTION;
            $filter['project_id'] = $accountInfo['project_id'];

            $images = $this->imageRepository->getListImageByFilter($limit, $filter);
            $images = eFunction::addFullUrlImageAndCheckDevice($images, []);

            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $images);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function createMedia(CreateMediaRequest $request)
    {
        DB::beginTransaction();
        try{
            $ownerId = $request->get('owner_id');
            $files = $request->file('files');
            $accountInfo = $request->get('accountInfo');

            $images = [];
            $isUploaded = false;
            foreach ($files as $file){
                $image = $this->fileUploadService->upload('admin_collection', $file, $accountInfo, Image::COLLECTION, $ownerId);
                if (!empty($image)){
                    $isUploaded = eFunction::checkFileSize($image);
                    $images[] = $image;
                }
            }

            if (!empty($isUploaded)){
                return eResponse::response(STATUS_API_ERROR, __('notification.api-form-max'));
            }

            if (!empty($images)){
                $this->imageRepository->createMulti($images);
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-create-success'));

        }catch (\Exception $e){
            DB::rollback();
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function deleteMedia(DeleteMediaRequest $request)
    {
        try{

            DB::beginTransaction();
            $accountInfo = $request->get('accountInfo');
            $ids = eFunction::arrayInteger($request->get('ids'));

            if (!empty($ids)){
                $adminDeviceImages = $this->adminDeviceImageRepository->getAllAdminDeviceImagesByFilter(['image_id' => $ids]);
                if (!empty($adminDeviceImages)){
                    return eResponse::response(STATUS_API_ERROR, __('notification.system.data-using'));
                }

                $this->imageRepository->deleteAllImageByFilter(['id' => $ids, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
            }
            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-delete-success'), []);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function createCollection(CreateCollectionRequest $request)
    {
        $data = $request->only(
            [
                'customer_account_id',
                'name',
                'slug'
            ]
        );

        DB::beginTransaction();
        try{
            $accountInfo = $request->get('accountInfo');

            $data['project_id'] = $accountInfo['project_id'];
            $data['account_id'] = $accountInfo['id'];
            $data['level'] = Owner::VERSION_FIRST;

            $owner = $this->ownerRepository->create($data);
            if (!empty($owner)){
                $files = $request->file('files');

                $images = [];
                $isUploaded = false;
                foreach ($files as $file){
                    $image = $this->fileUploadService->upload('admin_collection', $file, $accountInfo, Image::COLLECTION, $owner->id);
                    if (!empty($image)){
                        $isUploaded = eFunction::checkFileSize($image);
                        $images[] = $image;
                    }
                }

                if (!empty($isUploaded)){
                    return eResponse::response(STATUS_API_ERROR, __('notification.api-form-max'));
                }

                if (!empty($images)){
                    $this->imageRepository->createMulti($images);
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

    public function deleteCollection(DeleteCollectionRequest $request)
    {
        try{

            DB::beginTransaction();

            $accountInfo = $request->get('accountInfo');
            $ids = eFunction::arrayInteger($request->get('ids'));
            if (!empty($ids)){
                //Lấy tất cả id của các ảnh trong nhóm bộ sưu tập này ra
                //Xem nó đã được gán vào thiết bị nào chưa.Nếu có trong thiết bị thì xóa nó khỏi thiết bị và sắp xếp lại thứ tự và tính tổng lại thời gian.
                $images = $this->imageRepository->getAllImageByFilter(['owner_id' => $ids, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
                if (!empty($images)){
                    $imageIds = collect($images)->keyBy('id')->values()->toArray();
                    $adminDeviceImages = $this->adminDeviceImageRepository->getAllAdminDeviceImagesByFilter(['image_id' => $imageIds]);
                    if (!empty($adminDeviceImages)){
                        return eResponse::response(STATUS_API_ERROR, __('notification.system.data-using'));
                    }
                }

                $this->ownerRepository->deleteAllCollectionByFilter(['id' => $ids, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
                $this->imageRepository->deleteAllImageByFilter(['owner_id' => $ids, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
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

        if ($request->has('owner_id')) {
            $filter['owner_id'] = $request->get('owner_id');
        }
    }
}
