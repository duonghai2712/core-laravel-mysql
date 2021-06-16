<?php

namespace App\Http\Controllers\Api\Postgres\Store;

use App\Elibs\eFunction;
use App\Events\SendEmailRegistrationAccountEvent;
use App\Http\Controllers\Controller;
use App\Elibs\eResponse;
use App\Http\Requests\Api\PaginationRequest;
use App\Http\Requests\Api\Postgres\Store\StoreAccount\CreateStoreAccountRequest;
use App\Http\Requests\Api\Postgres\Store\StoreAccount\DeleteStoreAccountRequest;
use App\Http\Requests\Api\Postgres\Store\StoreAccount\DetailStoreAccountRequest;
use App\Http\Requests\Api\Postgres\Store\StoreAccount\MakeAdsRequest;
use App\Http\Requests\Api\Postgres\Store\StoreAccount\UpdateStoreAccountRequest;
use App\Http\Requests\BaseRequest;
use App\Models\Postgres\Admin\Account;
use App\Models\Postgres\Store\StoreAccount;
use App\Repositories\Postgres\Admin\BranchRepositoryInterface;
use App\Repositories\Postgres\Store\LogOperationRepositoryInterface;
use App\Repositories\Postgres\Store\PermissionRepositoryInterface;
use App\Repositories\Postgres\Store\StoreAccountRepositoryInterface;
use App\Services\Postgres\Store\StoreAccountServiceInterface;
use  DB;

class ApiStoreAccountController extends Controller
{

    protected $branchRepository;
    protected $logOperationRepository;
    protected $storeAccountRepository;
    protected $storeAccountService;
    protected $permissionRepository;
    public function __construct(
        StoreAccountRepositoryInterface $storeAccountRepository,
        LogOperationRepositoryInterface $logOperationRepository,
        BranchRepositoryInterface $branchRepository,
        StoreAccountServiceInterface $storeAccountService,
        PermissionRepositoryInterface $permissionRepository
    )
    {
        $this->branchRepository = $branchRepository;
        $this->logOperationRepository = $logOperationRepository;
        $this->storeAccountRepository = $storeAccountRepository;
        $this->storeAccountService = $storeAccountService;
        $this->permissionRepository = $permissionRepository;
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
            $filter['role'] = StoreAccount::SUB;

            $storeAccounts = $this->storeAccountRepository->getListStoreAccountByFilter($limit, $filter);
            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $storeAccounts);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function create(CreateStoreAccountRequest $request)
    {
        $data = $request->only([
            'username',
            'representative',
            'email',
            'phone_number',
            'group_store_account_id',
            'password',
            'branch_id',
            'is_active',
        ]);

        $makeAds = $request->get('make_ads');
        try {

            DB::beginTransaction();
            $storeAccountInfo = $request->get('storeAccountInfo');
            eFunction::FillUpStore($storeAccountInfo, $data);
            $data['role'] = StoreAccount::SUB;
            $data['language'] = !empty($data['language']) ? $data['language'] : Account::LANGUAGE['vi']['key'];
            $storeAccount = $this->storeAccountService->registerStoreAccount($data);

            if (!empty($data['branch_id']) && !empty($storeAccount)){
                $branch = $this->branchRepository->getOneObjectBranchByFilter(['id' => $data['branch_id'], 'deleted_at' => true]);
                if (!empty($branch)){
                    $this->branchRepository->update($branch, ['store_account_id' => $storeAccount->id, 'make_ads' => !empty($makeAds) && (int)$makeAds === StoreAccount::MAKE_ADS_TRUE ? StoreAccount::MAKE_ADS_TRUE : StoreAccount::MAKE_ADS_FALSE]);
                }
            }

            $activities = eFunction::getActivity($storeAccountInfo, null, $data['username'], 'create_account', null);
            if (!empty($activities)){
                $this->logOperationRepository->create($activities);
            }

            event(new SendEmailRegistrationAccountEvent([
                'account' => $data,
                'type' => StoreAccount::STORE
            ]));

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-create-success'));
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'));
        }
    }

    public function detail(DetailStoreAccountRequest $request)
    {
        $id = $request->get('id');
        $storeAccountInfo = $request->get('storeAccountInfo');
        $storeAccount = $this->storeAccountRepository->getOneArrayStoreAccountWithBranchByFilter(['id' => $id, 'store_id' => $storeAccountInfo['store_id'], 'project_id' => $storeAccountInfo['project_id'], 'deleted_at' => true]);
        if (!empty($storeAccount)){
            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $storeAccount);
        }

        return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
    }

    public function update(UpdateStoreAccountRequest $request)
    {
        $data = $request->only([
            'username',
            'representative',
            'email',
            'phone_number',
            'branch_id',
            'group_store_account_id',
            'password',
            'is_active',
            'make_ads'
        ]);

        $id = $request->get('id');
        $makeAds = $request->get('make_ads');
        try {

            DB::beginTransaction();
            $storeAccountInfo = $request->get('storeAccountInfo');
            $storeAccount = $this->storeAccountRepository->getOneObjectStoreAccountByFilter(['id' => $id, 'store_id' => $storeAccountInfo['store_id'], 'project_id' => $storeAccountInfo['project_id'], 'deleted_at' => true]);
            if (empty($storeAccount)){
                return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
            }

            eFunction::FillUpStore($storeAccountInfo, $data);
            $data['role'] = StoreAccount::SUB;
            $data['language'] = !empty($data['language']) ? $data['language'] : Account::LANGUAGE['vi']['key'];
            $this->storeAccountRepository->update($storeAccount,$data);

            if (!empty($data['branch_id'])){
                $branch = $this->branchRepository->getOneObjectBranchByFilter(['id' => $data['branch_id'], 'deleted_at' => true]);
                if (!empty($branch)){
                    $this->branchRepository->update($branch, ['store_account_id' => $storeAccount->id, 'make_ads' => !empty($makeAds) && (int)$makeAds === StoreAccount::MAKE_ADS_TRUE ? StoreAccount::MAKE_ADS_TRUE : StoreAccount::MAKE_ADS_FALSE]);
                }
            }

            $activities = eFunction::getActivity($storeAccountInfo, null, $data['username'], 'update_account', null);
            if (!empty($activities)){
                $this->logOperationRepository->create($activities);
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-create-success'));
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'));
        }
    }

    public function delete(DeleteStoreAccountRequest $request)
    {
        try{

            DB::beginTransaction();
            $storeAccountInfo = $request->get('storeAccountInfo');
            $ids = eFunction::arrayInteger($request->get('ids'));
            if (!empty($ids)){
                $this->storeAccountRepository->deleteAllStoreAccountByFilter(['id' => $ids, 'store_id' => $storeAccountInfo['store_id'], 'project_id' => $storeAccountInfo['project_id']]);
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-delete-success'), []);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function changeMakeAds(MakeAdsRequest $request)
    {
        try {
            DB::beginTransaction();
            $id = $request->get('id');
            $makeAds = $request->get('make_ads');
            $storeAccountInfo = $request->get('storeAccountInfo');
            $branch = $this->branchRepository->getOneObjectBranchByFilter(['store_account_id' => (int)$id, 'deleted_at' => true, 'project_id' => $storeAccountInfo['project_id']]);
            if (!empty($branch)){
                $this->branchRepository->update($branch, ['make_ads' => !empty($makeAds) && (int)$makeAds === StoreAccount::MAKE_ADS_TRUE ? StoreAccount::MAKE_ADS_TRUE : StoreAccount::MAKE_ADS_FALSE]);
            }

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-update-success'));
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'));
        }
    }

    public function allPermissions()
    {
        $permissions = $this->permissionRepository->getAllPermissionsByFilter(['deleted_at' => true]);
        if (!empty($permissions)){
            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $permissions);
        }

        return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
    }

    private function makeFilter($request, &$filter)
    {
        if ($request->has('key_word')) {
            $filter['key_word'] = $request->get('key_word');
        }

        if ($request->has('is_active')) {
            $filter['is_active'] = $request->get('is_active');
        }

        if ($request->has('group_store_account_id')) {
            $filter['group_store_account_id'] = $request->get('group_store_account_id');
        }
    }
}
