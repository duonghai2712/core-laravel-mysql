<?php

namespace App\Http\Controllers\Api\Postgres\Admin;

use App\Elibs\eFunction;
use App\Http\Requests\Api\Postgres\Admin\Brand\AddSubBrandRequest;
use App\Http\Requests\Api\Postgres\Admin\Brand\CreateBrandRequest;
use App\Http\Requests\Api\Postgres\Admin\Brand\DeleteBrandRequest;
use App\Http\Requests\Api\Postgres\Admin\Brand\DetailBrandRequest;
use App\Http\Requests\Api\Postgres\Admin\Brand\UpdateBrandRequest;
use App\Http\Requests\Api\PaginationRequest;
use App\Http\Requests\Api\Request;
use App\Models\Postgres\Admin\SubBrand;
use App\Repositories\Postgres\Admin\BranchSubBrandRepositoryInterface;
use App\Repositories\Postgres\Admin\BrandRepositoryInterface;
use App\Repositories\Postgres\Admin\SubBrandRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Elibs\eResponse;
use  DB;
use Illuminate\Database\Eloquent\Model;

class ApiBrandController extends Controller
{
    protected $brandRepository;
    protected $subBrandRepository;

    public function __construct(
        BrandRepositoryInterface $brandRepository,
        SubBrandRepositoryInterface $subBrandRepository
    )
    {
        $this->brandRepository = $brandRepository;
        $this->subBrandRepository = $subBrandRepository;
    }

    public function index(PaginationRequest $request)
    {
        $filter = [];
        $limit = $request->limit();
        $this->makeFilter($request, $filter);
        $accountInfo = $request->get('accountInfo');
        eFunction::FillUp($accountInfo, $filter);
        $filter['deleted_at'] = true;

        $brands = $this->brandRepository->getListBrandByFilter($limit, $filter);
        return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $brands);
    }

    public function create(CreateBrandRequest $request)
    {
        $data = $request->only(
            [
                'name',
                'slug',
                'description',
            ]
        );

        $accountInfo = $request->get('accountInfo');
        try{
            DB::beginTransaction();

            eFunction::FillUp($accountInfo, $data);
            $brand = $this->brandRepository->create($data);
            if (!empty($brand) && $request->has('subBrand')){
                $listSubBrand = [];

                $subBrand = $request->get('subBrand');
                foreach ($subBrand as $sBrand){
                    $slug = eFunction::generateSlug($sBrand['name'], '-');
                    if (!isset($listSubBrand[$slug])){
                        $listSubBrand[$slug] = [
                            'name' => $sBrand['name'],
                            'slug' => $slug,
                            'brand_id' => $brand->id,
                            'account_id' => $accountInfo['id'],
                            'project_id' => $accountInfo['project_id'],
                            'created_at' =>  eFunction::getDateTimeNow(),
                            'updated_at' =>  eFunction::getDateTimeNow(),
                        ];
                    }
                }

                if (!empty($listSubBrand)){
                    $listSubBrand = array_values($listSubBrand);
                    $this->subBrandRepository->createMulti($listSubBrand);
                }
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-create-success'), []);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function detail(DetailBrandRequest $request)
    {
        $id = $request->get('id');
        $accountInfo = $request->get('accountInfo');
        $brand = $this->brandRepository->getOneArrayBrandWithSubBrandByFilter(['id' => $id, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
        if (!empty($brand)){
            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $brand);
        }

        return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
    }

    public function update(UpdateBrandRequest $request)
    {
        $data = $request->only(
            [
                'name',
                'slug',
                'description',
            ]
        );

        $id = $request->get('id');
        $accountInfo = $request->get('accountInfo');

        $userInstance = new SubBrand();
        $index = 'id';
        try{

            DB::beginTransaction();
            if ($request->has('idsDelSubBrand')){
                $idsDel = eFunction::arrayInteger($request->get('idsDelSubBrand'));
                if (!empty($idsDel)){
                    $this->subBrandRepository->deleteSubBrandByFilter(['id' => $idsDel, 'deleted_at' => true]);
                }
            }

            $brand = $this->brandRepository->getOneObjectBrandByFilter(['id' => $id, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
            if (empty($brand)){
                return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
            }

            eFunction::FillUp($accountInfo, $data);
            $newBrand = $this->brandRepository->update($brand, $data);
            if (!empty($newBrand)){
                $subBrand = $request->get('subBrand');
                if (!empty($subBrand)){
                    $listSubBrandCheck = $listSubBrandNew = $listSubBrandOld = [];
                    foreach ($subBrand as $k => $sBrand){
                        $slug = eFunction::generateSlug($sBrand['name'], '-');
                        if (isset($listSubBrandCheck[$slug])){
                            return eResponse::response(STATUS_API_ERROR, __('notification.system.child-overlap'), [$sBrand['name'], $k]);
                        }

                        if (!empty($sBrand['id'])){
                            $listSubBrandOld[] = [
                                'id' => (int)$sBrand['id'],
                                'name' => $sBrand['name'],
                                'slug' => $slug,
                                'brand_id' => $newBrand->id,
                                'account_id' => $accountInfo['id'],
                                'project_id' => $accountInfo['project_id'],
                                'created_at' =>  eFunction::getDateTimeNow(),
                                'updated_at' =>  eFunction::getDateTimeNow(),
                            ];
                        }else{
                            $listSubBrandNew[] = [
                                'name' => $sBrand['name'],
                                'slug' => $slug,
                                'brand_id' => $newBrand->id,
                                'account_id' => $accountInfo['id'],
                                'project_id' => $accountInfo['project_id'],
                                'created_at' =>  eFunction::getDateTimeNow(),
                                'updated_at' =>  eFunction::getDateTimeNow(),
                            ];
                        }

                        //Gán lại thứ tự trùng lặp của sub brand khi trả vê
                        $listSubBrandCheck[$slug] = $k;
                    }

                    if (!empty($listSubBrandNew)){
                        $this->subBrandRepository->createMulti($listSubBrandNew);
                    }

                    if (!empty($listSubBrandOld)){
                        \Batch::update($userInstance, $listSubBrandOld, $index);
                    }
                }
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-update-success'), []);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function delete(DeleteBrandRequest $request)
    {
        try{

            DB::beginTransaction();
            $accountInfo = $request->get('accountInfo');
            $ids = eFunction::arrayInteger($request->get('ids'));
            if (!empty($ids)){
                $this->brandRepository->deleteAllBrandByFilter(['id' => $ids, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-delete-success'), []);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function addSubBrandToBrand(AddSubBrandRequest $request)
    {
        $id = $request->get('id');
        $accountInfo = $request->get('accountInfo');

        try{

            DB::beginTransaction();

            $listSubBrandNew = $listSubBrandCheck = [];

            $brand = $this->brandRepository->getOneObjectBrandByFilter(['id' => $id, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
            if (empty($brand)){
                return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
            }

            $subBrand = $request->get('subBrand');
            $subBrandsOld = $this->subBrandRepository->getAllSubBrandByFilter(['brand_id' => $id, 'project_id' => $accountInfo['project_id']]);
            if(!empty($subBrandsOld)){
                $subBrandsOld = collect($subBrandsOld)->keyBy('slug')->toArray();
            }

            if (!empty($subBrand)){
                foreach ($subBrand as $k => $sBrand){
                    $slug = eFunction::generateSlug($sBrand['name'], '-');
                    if (isset($listSubBrandCheck[$slug]) || isset($subBrandsOld[$slug])){
                        return eResponse::response(STATUS_API_ERROR, __('notification.system.child-overlap'), [$sBrand['name'], $k]);
                    }

                    if (!isset($listSubBrandNew[$slug])){
                        $listSubBrandNew[$slug] = [
                            'name' => $sBrand['name'],
                            'slug' => $slug,
                            'brand_id' => $brand->id,
                            'account_id' => $accountInfo['id'],
                            'project_id' => $accountInfo['project_id'],
                            'created_at' =>  eFunction::getDateTimeNow(),
                            'updated_at' =>  eFunction::getDateTimeNow(),
                        ];
                    }

                    $listSubBrandCheck[$slug] = $k;
                }

                if (!empty($listSubBrandNew)){
                    $listSubBrandNew = array_values($listSubBrandNew);
                    $this->subBrandRepository->createMulti($listSubBrandNew);
                }
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-create-success'), []);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function getAllBrand(Request $request)
    {
        try{
            $filter = [];
            $this->makeFilter($request, $filter);
            $accountInfo = $request->get('accountInfo');
            eFunction::FillUp($accountInfo, $filter);
            $filter['deleted_at'] = true;

            $brands = $this->brandRepository->getAllBrandWithHasSubBrandByFilter($filter);
            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $brands);

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
    }

}
