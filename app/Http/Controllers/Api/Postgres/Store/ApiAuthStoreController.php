<?php

namespace App\Http\Controllers\Api\Postgres\Store;


use App\Elibs\eCache;
use App\Elibs\eFunction;
use App\Events\SendEmailResetPasswordEvent;
use App\Http\Requests\Api\Postgres\Store\Account\ResetPasswordLastStepRequest;
use App\Http\Requests\Api\Postgres\Store\Account\ResetPasswordRequest;
use App\Http\Requests\Api\Postgres\Store\Account\ResetPassWordStep2Request;
use App\Http\Requests\Api\Postgres\Store\Account\SignInRequest;
use App\Http\Requests\BaseRequest;

use App\Models\Postgres\Store\Permission;
use App\Models\Postgres\Store\StoreAccount;
use App\Repositories\Postgres\Admin\BranchRepositoryInterface;
use App\Repositories\Postgres\Store\GroupStoreAccountPermissionRepositoryInterface;
use App\Repositories\Postgres\Store\PermissionRepositoryInterface;
use App\Repositories\Postgres\Store\ResetPasswordAccountStoreRepositoryInterface;
use App\Repositories\Postgres\Store\StoreAccountRepositoryInterface;
use App\Services\Postgres\Store\StoreAccountServiceInterface;
use App\Http\Controllers\Controller;
use App\Elibs\eResponse;
use  DB;
use Illuminate\Support\Str;

class ApiAuthStoreController extends Controller
{
    protected $storeAccountRepository;
    protected $groupStoreAccountPermissionRepository;
    protected $storeAccountService;
    protected $permissionRepository;
    protected $branchRepository;
    protected $resetPasswordStoreAccountRepository;

    public function __construct(
        StoreAccountRepositoryInterface $storeAccountRepository,
        PermissionRepositoryInterface $permissionRepository,
        BranchRepositoryInterface $branchRepository,
        GroupStoreAccountPermissionRepositoryInterface $groupStoreAccountPermissionRepository,
        ResetPasswordAccountStoreRepositoryInterface $resetPasswordStoreAccountRepository,
        StoreAccountServiceInterface $storeAccountService
    )
    {
        $this->permissionRepository = $permissionRepository;
        $this->branchRepository = $branchRepository;
        $this->storeAccountRepository = $storeAccountRepository;
        $this->groupStoreAccountPermissionRepository = $groupStoreAccountPermissionRepository;
        $this->resetPasswordStoreAccountRepository = $resetPasswordStoreAccountRepository;
        $this->storeAccountService = $storeAccountService;
    }

    public function index(SignInRequest $request)
    {
        try {
            $data = $request->only(
                [
                    'username',
                    'password'
                ]
            );

            $storeAccountExists = $this->storeAccountRepository->getOneArrayStoreAccountByFilter(['username' => strtolower($data['username'])]);
            $expired = eFunction::getThrottleLogin($storeAccountExists, $data['username'], StoreAccount::STRING_REDIS);
            if (!empty($expired)){
                return eResponse::response(STATUS_API_EXPIRED_TIME, __('notification.system.blocked-account'), ['expired' => $expired]);
            }

            $storeAccount = $this->storeAccountService->signInByAPI($data);
            if (empty($storeAccount)) {
                eFunction::setThrottleLogin($storeAccountExists, $data['username'], StoreAccount::STRING_REDIS);
                return eResponse::response(STATUS_API_FALSE,__('notification.system.account-not-found'));
            }

            $permissions = [];
            $makeAds = StoreAccount::MAKE_ADS_FALSE;
            if (!empty($storeAccount->role) && (int)$storeAccount->role === StoreAccount::SUB){
                $makeAds = StoreAccount::MAKE_ADS_TRUE;
                if (!empty($storeAccount->group_store_account_id)){
                    $groupStoreAccountPermissions = $this->groupStoreAccountPermissionRepository->getAllGroupStoreAccountPermissionsByFilter(['group_store_account_id' => (int)$storeAccount->group_store_account_id]);
                    $AllPermissions = $this->permissionRepository->getAllPermissionsByFilter(['deleted_at' => true]);
                    if (!empty($groupStoreAccountPermissions)){
                        $groupStoreAccountPermissions = collect($groupStoreAccountPermissions)->keyBy('permission_id')->toArray();
                    }

                    if (!empty($AllPermissions)){
                        foreach ($AllPermissions as $permission){
                            if (!empty($groupStoreAccountPermissions[$permission['id']])){
                                $permissions[$permission['key']] = [
                                    'view' => $groupStoreAccountPermissions[$permission['id']]['view'],
                                    'add' => $groupStoreAccountPermissions[$permission['id']]['add'],
                                    'update' => $groupStoreAccountPermissions[$permission['id']]['update'],
                                    'delete' => $groupStoreAccountPermissions[$permission['id']]['delete'],
                                ];
                            }else{
                                $permissions[$permission['key']] = [
                                    'view' => 0,
                                    'add' => 0,
                                    'update' => 0,
                                    'delete' => 0
                                ];
                            }
                        }
                    }
                }
            }

            if (!empty($storeAccount['branch_id'])){
                $branch = $this->branchRepository->getOneArrayBranchByFilter(['id' => $storeAccount['branch_id']]);
                if (!empty($branch) && !empty($branch['make_ads']) && (int)$branch['make_ads'] === StoreAccount::MAKE_ADS_TRUE){
                    $makeAds = StoreAccount::MAKE_ADS_TRUE;
                }
            }

            $data = [
                'id'=>$storeAccount->id,
                'username'=>$storeAccount->username,
                'email' => $storeAccount->email,
                'make_ads' => $makeAds,
                'store_id' => $storeAccount->store_id,
                'role' => $storeAccount->role,
                'token' => $storeAccount->api_access_token,
                'branch_id' => !empty($storeAccount->branch_id) ? $storeAccount->branch_id : null,
                'project_id' => $storeAccount->project_id,
                'permissions' => $permissions,
                'avatar' => isset($storeAccount->profileStoreImage->source) && !empty($storeAccount->profileStoreImage->source)? asset($storeAccount->profileStoreImage->source) : ''
            ];

            return eResponse::response(STATUS_API_SUCCESS, __('notification.system.log-in-successfully'), $data);

        } catch (\Exception $e) {
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }

    }

    public function signOut(BaseRequest $request)
    {
        DB::beginTransaction();

        try {
            $token = $request->bearerToken();

            $storeAccount = $this->storeAccountRepository->getOneObjectStoreAccountByFilter(['api_access_token' => $token]);
            if (!empty($storeAccount)) {
                $this->storeAccountRepository->update($storeAccount, ['api_access_token' => '']);
                DB::commit();
                return eResponse::response(STATUS_API_SUCCESS, __('notification.system.log-out-successfully'), []);
            }

            return eResponse::response(STATUS_API_FALSE,__('notification.system.data-not-found'));

        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR,__('notification.system.errors'));
        }
    }

    public function createNewPassword(ResetPasswordRequest $request)
    {
        DB::beginTransaction();

        try {
            $email = strtolower(trim($request->get('email')));
            $token = Str::random(60);
            $account = $this->storeAccountRepository->getOneArrayStoreAccountByFilter(['email' => $email, 'deleted_at' => true, 'is_active' => StoreAccount::IS_ACTIVE]);
            if (!empty($account)){
                $this->resetPasswordStoreAccountRepository->createOrUpdateByFilter(['email' => $email], ['token' => $token]);
            }

            eCache::add($token, $email . StoreAccount::KEY_CACHE . $account['id'], 300);
            event(new SendEmailResetPasswordEvent([
                'email' => $email,
                'token' => $token,
                'type' => StoreAccount::STORE,
                'username' => $account['username'],
            ]));

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.system.reset-password'));

        } catch (\Exception $e) {
            DB::rollback();
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function resetPassword(ResetPassWordStep2Request $request)
    {
        try {
            $token = trim($request->get('token'));

            $cacheExists = eCache::get($token);
            if (empty($cacheExists)){
                return eResponse::response(STATUS_API_TOKEN_EXPIRED, __('notification.system.Expired'));
            }

            $arrAccount = explode(StoreAccount::KEY_CACHE, $cacheExists);
            if (empty($arrAccount)){
                return eResponse::response(STATUS_API_TOKEN_EXPIRED, __('notification.system.Expired'));
            }

            $accountExists = $this->storeAccountRepository->getOneArrayStoreAccountByFilter(['id' => (int)$arrAccount[1], 'email' => $arrAccount[0], 'deleted_at' => true, 'is_active' => StoreAccount::IS_ACTIVE]);
            if (empty($accountExists)){
                return eResponse::response(STATUS_API_TOKEN_EXPIRED, __('notification.system.token-not-found'));
            }

            $emailExists = $this->resetPasswordStoreAccountRepository->getOneArrayByFilter(['token' => $token, 'email' => $arrAccount[0]]);
            if (!empty($emailExists)){
                return eResponse::response(STATUS_API_SUCCESS, __('notification.system.token-success'), ['email' => $emailExists['email']]);
            }

            return eResponse::response(STATUS_API_TOKEN_EXPIRED, __('notification.system.token-not-found'));


        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function updateNewPassword(ResetPasswordLastStepRequest $request)
    {
        DB::beginTransaction();

        try {
            $token = trim($request->get('token'));
            $data['password'] = trim($request->get('password'));

            $cacheExists = eCache::get($token);
            if (empty($cacheExists)){
                return eResponse::response(STATUS_API_TOKEN_EXPIRED, __('notification.system.Expired'));
            }

            $arrAccount = explode(StoreAccount::KEY_CACHE, $cacheExists);
            if (empty($arrAccount)){
                return eResponse::response(STATUS_API_TOKEN_EXPIRED, __('notification.system.Expired'));
            }

            $accountExists = $this->storeAccountRepository->getOneObjectStoreAccountByFilter(['id' => (int)$arrAccount[1], 'email' => $arrAccount[0], 'deleted_at' => true, 'is_active' => StoreAccount::IS_ACTIVE]);
            if (empty($accountExists)){
                return eResponse::response(STATUS_API_TOKEN_EXPIRED, __('notification.system.token-not-found'));
            }

            $this->storeAccountRepository->update($accountExists, $data);
            $this->resetPasswordStoreAccountRepository->updateResetPasswordByFilter(['token' => $token, 'email' => $arrAccount[0]], ['token' => null]);

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.system.change-password-success'));


        } catch (\Exception $e) {
            DB::rollback();

            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.change-password-failed'), []);
        }
    }

}
