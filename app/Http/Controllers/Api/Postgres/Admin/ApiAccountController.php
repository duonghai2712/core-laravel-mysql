<?php

namespace App\Http\Controllers\Api\Postgres\Admin;

use App\Http\Requests\Api\Postgres\Admin\Account\DetailAccountRequest;
use App\Http\Requests\Api\Postgres\Admin\Account\UpdateAccountRequest;
use App\Models\Postgres\Admin\Account;
use App\Models\Postgres\Admin\Image;
use App\Repositories\Postgres\Admin\AccountRepositoryInterface;
use App\Repositories\Postgres\Admin\ProjectRepositoryInterface;
use App\Repositories\Postgres\Admin\ResetPasswordAccountRepositoryInterface;
use App\Services\Postgres\Admin\AccountServiceInterface;
use App\Services\Postgres\Admin\FileUploadServiceInterface;;
use App\Http\Controllers\Controller;
use App\Elibs\eResponse;
use  DB;

class ApiAccountController extends Controller
{
    protected $accountRepository;
    protected $accountService;
    protected $projectRepository;
    protected $fileUploadService;

    public function __construct(
        AccountRepositoryInterface $accountRepository,
        ProjectRepositoryInterface $projectRepository,
        FileUploadServiceInterface $fileUploadService,
        AccountServiceInterface $accountService
    )
    {
        $this->accountRepository = $accountRepository;
        $this->accountService = $accountService;
        $this->projectRepository = $projectRepository;
        $this->fileUploadService = $fileUploadService;
    }
    public function index(DetailAccountRequest $request)
    {
        $accountInfo = $request->get('accountInfo');
        $filter['id'] = (int)$accountInfo['id'];
        $filter['project_id'] = (int)$accountInfo['project_id'];
        $filter['is_active'] = Account::IS_ACTIVE;
        $filter['deleted_at'] = true;
        $account = $this->accountRepository->getOneObjectAccountByFilter($filter);
        if (empty($account)){
            return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
        }

        return eResponse::response(STATUS_API_SUCCESS, __('notification.system.successful-data-retrieval'), $account->toAPIArray());
    }

    public function update(UpdateAccountRequest $request)
    {

        $data = $request->only([
            'name',
            'email',
            'phone_number'
        ]);


        if ($request->has('password')) {
            $data['password'] = $request->get('password');
        }

        $id = $request->get('id');
        $accountInfo = $request->get('accountInfo');
        try {
            DB::beginTransaction();

            $account = $this->accountRepository->getOneObjectAccountByFilter(['id' => $id, 'deleted_at' => true, 'is_active' => Account::IS_ACTIVE]);
            if (empty($account)){
                return eResponse::response(STATUS_API_FALSE, __('notification.system.data-not-found'));
            }

            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $image = $this->fileUploadService->upload('account_profile_image', $file, $accountInfo, Image::AVATAR, null);

                if (!empty($image)){
                    $data['profile_image_id'] = $image['id'];
                }
            }

            $newAccount = $this->accountRepository->update($account, $data);
            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-update-success'), $newAccount->toAPIArray());
        } catch (\Exception $e) {
            DB::rollback();
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'));
        }
    }
}
