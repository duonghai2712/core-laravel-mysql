<?php

namespace App\Http\Controllers\Api\Postgres\Store;

use App\Elibs\eFunction;
use App\Http\Controllers\Controller;
use App\Elibs\eResponse;
use App\Http\Requests\Api\PaginationRequest;
use App\Http\Requests\Api\Postgres\Store\GroupAccount\CreateGroupAccountRequest;
use App\Http\Requests\Api\Postgres\Store\GroupAccount\DeleteGroupAccountRequest;
use App\Http\Requests\Api\Postgres\Store\GroupAccount\DetailGroupAccountRequest;
use App\Http\Requests\Api\Postgres\Store\GroupAccount\UpdateGroupAccountRequest;
use App\Http\Requests\BaseRequest;
use App\Models\Postgres\Store\GroupStoreAccountPermission;
use App\Models\Postgres\Store\Permission;
use App\Repositories\Postgres\Store\GroupStoreAccountPermissionRepositoryInterface;
use App\Repositories\Postgres\Store\GroupStoreAccountRepositoryInterface;
use App\Repositories\Postgres\Store\LogOperationRepositoryInterface;
use App\Repositories\Postgres\Store\PermissionRepositoryInterface;
use App\Repositories\Postgres\Store\StoreAccountRepositoryInterface;
use  DB;


class ApiGroupStoreAccountController extends Controller
{
    protected $logOperationRepository;
    protected $storeAccountRepository;
    protected $permissionRepository;
    protected $groupStoreAccountRepository;
    protected $groupStoreAccountPermissionRepository;
    public function __construct(
        PermissionRepositoryInterface $permissionRepository,
        StoreAccountRepositoryInterface $storeAccountRepository,
        LogOperationRepositoryInterface $logOperationRepository,
        GroupStoreAccountRepositoryInterface $groupStoreAccountRepository,
        GroupStoreAccountPermissionRepositoryInterface $groupStoreAccountPermissionRepository
    )
    {
        $this->storeAccountRepository = $storeAccountRepository;
        $this->permissionRepository = $permissionRepository;
        $this->logOperationRepository = $logOperationRepository;
        $this->groupStoreAccountRepository = $groupStoreAccountRepository;
        $this->groupStoreAccountPermissionRepository = $groupStoreAccountPermissionRepository;
    }

    public function index(PaginationRequest $request)
    {
        try{
            $limit = $request->limit();
            $storeAccountInfo = $request->get('storeAccountInfo');
            $filter['order'] = $request->order();
            $filter['direction'] = $request->direction();
            $filter['deleted_at'] = true;

            eFunction::FillUpStore($storeAccountInfo, $filter);
            $this->makeFilter($request, $filter);

            $groupStoreAccounts = $this->groupStoreAccountRepository->getListGroupStoreAccountByFilter($limit, $filter);

            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $groupStoreAccounts);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function create(CreateGroupAccountRequest $request)
    {
        try{

            DB::beginTransaction();
            $storeAccountInfo = $request->get('storeAccountInfo');

            $data['name'] = $request->get('name');
            $data['store_account_id'] = $storeAccountInfo['id'];
            eFunction::FillUpStore($storeAccountInfo, $data);
            $data['slug'] = eFunction::generateSlug($data['name'], '-');

            $groupStoreAccount = $this->groupStoreAccountRepository->create($data);
            if (!empty($groupStoreAccount)){
                $permissions = $request->get('permissions');
                if (!empty($permissions)){
                    $permisstionIds = collect($permissions)->pluck('permission_id')->filter()->unique()->values()->toArray();
                    $permissionChecking = $this->permissionRepository->countAllPermissionsByFilter(['id' => $permisstionIds, 'deleted_at' => true]);
                    if (empty($permissionChecking) || $permissionChecking !== count($permisstionIds)){
                        return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
                    }
                    $groupStoreAccountPermission = [];
                    foreach ($permissions as $permission){
                        if (!empty($permission['permission_id'])){
                            $params = [
                                'permission_id' => (int)$permission['permission_id'],
                                'view' => !empty($permission['view']) ? Permission::IS_ENABLE : Permission::DISABLE,
                                'add' => !empty($permission['add']) ? Permission::IS_ENABLE : Permission::DISABLE,
                                'update' => !empty($permission['update']) ? Permission::IS_ENABLE : Permission::DISABLE,
                                'delete' => !empty($permission['delete']) ? Permission::IS_ENABLE : Permission::DISABLE,
                                'group_store_account_id' => $groupStoreAccount->id,
                                'store_id' => $storeAccountInfo['store_id'],
                                'store_account_id' => $storeAccountInfo['id'],
                                'project_id' => $storeAccountInfo['project_id'],
                                'created_at' => eFunction::getDateTimeNow(),
                                'updated_at' => eFunction::getDateTimeNow(),
                            ];

                            $groupStoreAccountPermission[] = $params;
                        }
                    }

                    if (!empty($groupStoreAccountPermission)){
                        $this->groupStoreAccountPermissionRepository->createMulti($groupStoreAccountPermission);
                    }

                    $activities = eFunction::getActivity($storeAccountInfo, null, $data['name'], 'create_group_account', null);
                    if (!empty($activities)){
                        $this->logOperationRepository->create($activities);
                    }
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

    public function detail(DetailGroupAccountRequest $request)
    {
        $id = $request->get('id');
        $storeAccountInfo = $request->get('storeAccountInfo');
        $groupStoreAccount = $this->groupStoreAccountRepository->getOneArrayGroupStoreAccountByFilter(['id' => $id, 'project_id' => $storeAccountInfo['project_id'], 'store_id' => $storeAccountInfo['store_id'], 'deleted_at' => true]);
        if (!empty($groupStoreAccount)){
            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $groupStoreAccount);
        }

        return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
    }

    public function update(UpdateGroupAccountRequest $request)
    {
        try{
            DB::beginTransaction();
            $id = $request->get('id');
            $storeAccountInfo = $request->get('storeAccountInfo');

            $groupStoreAccount = $this->groupStoreAccountRepository->getOneObjectGroupStoreAccountByFilter(['id' => $id, 'project_id' => $storeAccountInfo['project_id'], 'store_id' => $storeAccountInfo['store_id'], 'deleted_at' => true]);
            if (empty($groupStoreAccount)){
                return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
            }

            $data['name'] = $request->get('name');
            eFunction::FillUpStore($storeAccountInfo, $data);
            $data['store_account_id'] = $storeAccountInfo['id'];
            $data['slug'] = eFunction::generateSlug($data['name'], '-');

            $userInstance = new GroupStoreAccountPermission();
            $index = 'id';

            $newGroupStoreAccount = $this->groupStoreAccountRepository->update($groupStoreAccount, $data);
            if (!empty($newGroupStoreAccount)){
                $permissions = $request->get('permissions');
                if (!empty($permissions)) {
                    $idsPermissions = collect($permissions)->pluck('permission_id')->filter()->unique()->values()->toArray();
                    $idsGroupStoreAccountPermissions = collect($permissions)->pluck('group_store_account_permission_id')->filter()->unique()->values()->toArray();

                    if (!empty($idsPermissions) && !empty($idsGroupStoreAccountPermissions)) {
                        $permissionChecking = $this->permissionRepository->countAllPermissionsByFilter(['id' => $idsPermissions,'deleted_at' => true]);
                        if (empty($permissionChecking) || $permissionChecking !== count($idsPermissions)) {
                            return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
                        }

                        $groupStoreAccountPermissionChecking = $this->groupStoreAccountPermissionRepository->countAllGroupStoreAccountPermissionsByFilter(['id' => $idsGroupStoreAccountPermissions, 'project_id' => $storeAccountInfo['project_id']]);
                        if (empty($groupStoreAccountPermissionChecking) || $groupStoreAccountPermissionChecking !== count($idsGroupStoreAccountPermissions)) {
                            return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
                        }

                        $groupStoreAccountPermission = [];
                        foreach ($permissions as $permission){
                            $params = [
                                'id' => $permission['group_store_account_permission_id'],
                                'view' => !empty($permission['view']) ? Permission::IS_ENABLE : Permission::DISABLE,
                                'add' => !empty($permission['add']) ? Permission::IS_ENABLE : Permission::DISABLE,
                                'update' => !empty($permission['update']) ? Permission::IS_ENABLE : Permission::DISABLE,
                                'delete' => !empty($permission['delete']) ? Permission::IS_ENABLE : Permission::DISABLE,
                                'permission_id' => $permission['permission_id'],
                                'group_store_account_id' => $newGroupStoreAccount->id,
                                'store_id' => $storeAccountInfo['store_id'],
                                'store_account_id' => $storeAccountInfo['id'],
                                'project_id' => $storeAccountInfo['project_id'],
                                'created_at' => eFunction::getDateTimeNow(),
                                'updated_at' => eFunction::getDateTimeNow(),
                            ];

                            $groupStoreAccountPermission[] = $params;
                        }


                        if (!empty($groupStoreAccountPermission)){
                            \Batch::update($userInstance, $groupStoreAccountPermission, $index);
                        }
                    }
                }
            }

            $this->storeAccountRepository->updateApiTokenAllStoreAccountByFilter(['group_store_account_id' => $id, 'deleted_at' => true]);
            $activities = eFunction::getActivity($storeAccountInfo, null, $data['name'], 'update_group_account', null);
            if (!empty($activities)){
                $this->logOperationRepository->create($activities);
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-update-success'), []);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function delete(DeleteGroupAccountRequest $request)
    {
        try{

            DB::beginTransaction();

            $storeAccountInfo = $request->get('storeAccountInfo');
            $ids = eFunction::arrayInteger($request->get('ids'));
            if (!empty($ids)){
                $this->groupStoreAccountRepository->deleteAllGroupStoreAccountByFilter(['id' => $ids, 'project_id' => $storeAccountInfo['project_id'], 'store_id' => $storeAccountInfo['store_id'], 'deleted_at' => true]);
                $this->groupStoreAccountPermissionRepository->deleteAllGroupStoreAccountPermissionByFilter(['id' => $ids, 'project_id' => $storeAccountInfo['project_id'],  'store_id' => $storeAccountInfo['store_id']]);
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-delete-success'), []);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function allGroupStoreAccounts(BaseRequest $request)
    {
        $storeAccountInfo = $request->get('storeAccountInfo');
        $groupStoreAccounts = $this->groupStoreAccountRepository->getAllGroupStoreAccountByFilter(['project_id' => $storeAccountInfo['project_id'], 'store_id' => $storeAccountInfo['store_id'], 'deleted_at' => true]);
        if (!empty($groupStoreAccounts)){
            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $groupStoreAccounts);
        }

        return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
    }

    private function makeFilter($request, &$filter)
    {
        if ($request->has('key_word')) {
            $filter['key_word'] = $request->get('key_word');
        }

        if ($request->has('start_date')) {
            $filter['start_date'] = date('Y-m-d H:i:s', strtotime($request->get('start_date')));
        }

        if ($request->has('end_date')) {
            $filter['end_date'] = date('Y-m-d H:i:s', strtotime($request->get('end_date')));
        }
    }
}
