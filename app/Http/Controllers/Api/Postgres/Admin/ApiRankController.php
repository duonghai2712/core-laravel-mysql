<?php

namespace App\Http\Controllers\Api\Postgres\Admin;

use App\Elibs\eFunction;
use App\Http\Requests\Api\PaginationRequest;
use App\Http\Requests\Api\Postgres\Admin\Rank\CreateRankRequest;
use App\Http\Requests\Api\Postgres\Admin\Rank\DeleteRankRequest;
use App\Http\Requests\Api\Postgres\Admin\Rank\DetailRankRequest;
use App\Http\Requests\Api\Postgres\Admin\Rank\UpdateRankRequest;
use App\Models\Postgres\Admin\Rank;

use App\Repositories\Postgres\Admin\RankRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Elibs\eResponse;
use Illuminate\Support\Str;
use  DB;

class ApiRankController extends Controller
{
    protected $rankRepository;
    public function __construct(RankRepositoryInterface $rankRepository)
    {
        $this->rankRepository = $rankRepository;
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

            $ranks = $this->rankRepository->getListRankByFilter($limit, $filter);

            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $ranks);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function create(CreateRankRequest $request)
    {
        $data = $request->only(
            [
                'name',
                'slug',
                'description',
                'coefficient'
            ]
        );

        try{
            DB::beginTransaction();
            if (!in_array((int)$data['coefficient'], Rank::ARRAY_COEFFICIENT)){
                return eResponse::response(STATUS_API_ERROR, __('notification.api-form-coefficient-incorrect'), []);
            }

            $accountInfo = $request->get('accountInfo');
            eFunction::FillUp($accountInfo, $data);
            $this->rankRepository->create($data);
            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-create-success'), []);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function detail(DetailRankRequest $request)
    {
        $id = $request->get('id');
        $accountInfo = $request->get('accountInfo');
        $rank = $this->rankRepository->getOneArrayRankByFilter(['id' => $id, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
        if (!empty($rank)){
            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $rank);
        }

        return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
    }

    public function update(UpdateRankRequest $request)
    {
        $data = $request->only(
            [
                'name',
                'slug',
                'description',
                'coefficient'
            ]
        );

        $id = $request->get('id');
        try{

            DB::beginTransaction();
            $accountInfo = $request->get('accountInfo');
            if (!in_array((int)$data['coefficient'], Rank::ARRAY_COEFFICIENT)){
                return eResponse::response(STATUS_API_ERROR, __('notification.api-form-coefficient-incorrect'), []);
            }

            $rank = $this->rankRepository->getOneObjectRankByFilter(['id' => $id, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
            if (!empty($rank)){
                eFunction::FillUp($accountInfo, $data);
                $this->rankRepository->update($rank, $data);
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-update-success'), []);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function delete(DeleteRankRequest $request)
    {
        try{

            DB::beginTransaction();

            $accountInfo = $request->get('accountInfo');

            $ids = eFunction::arrayInteger($request->get('ids'));
            if (!empty($ids)){
                $this->rankRepository->deleteAllRankByFilter(['id' => $ids, 'project_id' => $accountInfo['project_id'], 'deleted_at' => true]);
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
    }

}
