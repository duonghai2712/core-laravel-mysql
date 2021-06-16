<?php

namespace App\Http\Controllers\Api\Postgres\Admin;


use App\Elibs\eCache;
use App\Elibs\eFunction;
use App\Events\SendEmailRegistrationAccountEvent;
use App\Events\SendEmailResetPasswordEvent;
use App\Http\Requests\Api\Postgres\Admin\Account\ResetPasswordLastStepRequest;
use App\Http\Requests\Api\Postgres\Admin\Account\ResetPasswordRequest;
use App\Http\Requests\Api\Postgres\Admin\Account\ResetPassWordStep2Request;
use App\Http\Requests\Api\Postgres\Admin\Account\SignInRequest;
use App\Http\Requests\Api\Postgres\Admin\Account\SignUpRequest;
use App\Http\Requests\BaseRequest;
use App\Models\Postgres\Admin\Account;
use App\Models\Postgres\Admin\Image;
use App\Repositories\Postgres\Admin\AccountRepositoryInterface;
use App\Repositories\Postgres\Admin\OwnerRepositoryInterface;
use App\Repositories\Postgres\Admin\ProjectRepositoryInterface;
use App\Repositories\Postgres\Admin\ResetPasswordAccountRepositoryInterface;
use App\Services\Postgres\Admin\AccountServiceInterface;
use App\Http\Controllers\Controller;
use App\Elibs\eResponse;
use  DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class ApiAuthAdminController extends Controller
{
    protected $accountRepository;
    protected $accountService;
    protected $projectRepository;
    protected $ownerRepository;
    protected $resetPasswordAccountRepository;

    public function __construct(
        AccountRepositoryInterface $accountRepository,
        OwnerRepositoryInterface $ownerRepository,
        ResetPasswordAccountRepositoryInterface $resetPasswordAccountRepository,
        ProjectRepositoryInterface $projectRepository,
        AccountServiceInterface $accountService
    )
    {
        $this->accountRepository = $accountRepository;
        $this->accountService = $accountService;
        $this->projectRepository = $projectRepository;
        $this->ownerRepository = $ownerRepository;
        $this->resetPasswordAccountRepository = $resetPasswordAccountRepository;
    }

    public function index(SignUpRequest $request)
    {
        $data = $request->only(
            [
                'name',
                'username',
                'email',
                'password'
            ]
        );

        try{
            DB::beginTransaction();

            $project = $this->createProject($request);
            if(empty($project)){
                return eResponse::response(STATUS_API_FALSE,__('notification.system.company-name-exists'));
            }

            $data['project_id'] = $project['id'];
            $data['language'] = !empty($data['language']) ? $data['language'] : Account::LANGUAGE['vi']['key'];

            $account = $this->accountService->signUpByAPI($data);
            if(empty($account)){
                return eResponse::response(STATUS_API_FALSE, __('notification.system.create-fail-account'), []);
            }

            $this->createAntCollection($account->id, $project['id']);
            event(new SendEmailRegistrationAccountEvent(['account' => $data, 'type' => Account::ADMIN]));

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.system.create-successful-project'), $account->toAPIArray());

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }

    }

    public function signIn(SignInRequest $request)
    {

        try {
            $data = $request->only(
                [
                    'username',
                    'password'
                ]
            );

            $accountExists = $this->accountRepository->getOneArrayAccountByFilter(['username' => $data['username']]);
            $expired = eFunction::getThrottleLogin($accountExists, $data['username'], Account::STRING_REDIS);
            if (!empty($expired)){
                return eResponse::response(STATUS_API_EXPIRED_TIME, __('notification.system.blocked-account'), ['expired' => $expired]);
            }

            $account = $this->accountService->signInByAPI($data);
            if (empty($account)) {
                eFunction::setThrottleLogin($accountExists, $data['username'], Account::STRING_REDIS);
                return eResponse::response(STATUS_API_FALSE, __('notification.system.account-not-found'));
            }

            $owner = $this->ownerRepository->getOneArrayOwnerByFilter(['account_id' => $account->id, 'project_id' => $account->project_id, 'deleted_at' => true]);

            $params = [
                'id'=>$account->id,
                'owner_id' => !empty($owner['id']) ? $owner['id'] : null,
                'name' => $account->name,
                "is_active" => $account->is_active,
                "phone_number" => $account->phone_number,
                'language' => $account->language,
                'username'=>$account->username,
                'email' => $account->email,
                'token' => $account->api_access_token,
                'project_id' => $account->project_id,
                'avatar' => !empty($account->profileImage->source)? asset($account->profileImage->source) : ''
            ];

            return eResponse::response(STATUS_API_SUCCESS, __('notification.system.log-in-successfully'), $params);

        } catch (\Exception $e) {
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }

    }

    public function signOut(BaseRequest $request)
    {
        try {
            $token = $request->bearerToken();

            $account = $this->accountRepository->getOneObjectAccountByFilter(['api_access_token' => $token]);
            if (!empty($account)) {
                $this->accountRepository->update($account, ['api_access_token' => '']);
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
            $account = $this->accountRepository->getOneArrayAccountByFilter(['email' => $email, 'deleted_at' => true, 'is_active' => Account::IS_ACTIVE]);
            if (!empty($account)){
                $this->resetPasswordAccountRepository->createOrUpdateByFilter(['email' => $email], ['token' => $token]);
            }
            eCache::add($token, $email . Account::KEY_CACHE . $account['id'], 300);
            event(new SendEmailResetPasswordEvent(['email' => $email, 'token' => $token, 'type' => Account::ADMIN, 'username' => $account['username']]));

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
            $arrAccount = explode(Account::KEY_CACHE, $cacheExists);
            if (empty($arrAccount) || empty($cacheExists)){
                return eResponse::response(STATUS_API_TOKEN_EXPIRED, __('notification.system.Expired'));
            }

            $accountExists = $this->accountRepository->getOneArrayAccountByFilter(['id' => (int)$arrAccount[1], 'email' => $arrAccount[0], 'deleted_at' => true, 'is_active' => Account::IS_ACTIVE]);
            if (empty($accountExists)){
                return eResponse::response(STATUS_API_TOKEN_EXPIRED, __('notification.system.token-not-found'));
            }

            $emailExists = $this->resetPasswordAccountRepository->getOneArrayByFilter(['token' => $token, 'email' => $arrAccount[0]]);
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

            $cacheExists = eCache::get($token);
            $arrAccount = explode(Account::KEY_CACHE, $cacheExists);
            if (empty($arrAccount) || empty($cacheExists)){
                return eResponse::response(STATUS_API_TOKEN_EXPIRED, __('notification.system.Expired'));
            }

            $accountExists = $this->accountRepository->getOneObjectAccountByFilter(['id' => (int)$arrAccount[1], 'email' => $arrAccount[0], 'deleted_at' => true, 'is_active' => Account::IS_ACTIVE]);
            if (empty($accountExists)){
                return eResponse::response(STATUS_API_TOKEN_EXPIRED, __('notification.system.token-not-found'));
            }

            $this->accountRepository->update($accountExists, ['password' => trim($request->get('password'))]);
            $this->resetPasswordAccountRepository->updateResetPasswordByFilter(['token' => $token, 'email' => $arrAccount[0]], ['token' => null]);

            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.system.change-password-success'));

        } catch (\Exception $e) {
            DB::rollback();

            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.change-password-failed'), []);
        }
    }

    private function createProject($request)
    {

        try{
            $project_name = $request->get('project_name');
            if (empty($project_name)){
                return [];
            }

            $project_slug = eFunction::generateSlug($project_name, '-');
            $projectExists = $this->projectRepository->getOneProjectByFilter(['slug' => $project_slug]);

            if(!empty($projectExists)){
                return [];
            }

            $project = $this->projectRepository->create([
                'name' => $project_name,
                'slug' => $project_slug
            ]);

            return $project->toArray();

        }catch(\Exception $e){
            return [];
        }

    }

    private function createAntCollection($account_id, $project_id)
    {
        try{

            $collectionAnt = $this->ownerRepository->create([
                'name' => Image::ANTS_MEDIA,
                'slug' => eFunction::generateSlug(Image::ANTS_MEDIA, '-'),
                'level' => Image::ANT,
                'project_id' => $project_id,
                'account_id' => $account_id,
                'created_at' => eFunction::getDateTimeNow(),
                'updated_at' => eFunction::getDateTimeNow()
            ]);

            return $collectionAnt->toArray();

        }catch(\Exception $e){
            return [];
        }
    }
}
