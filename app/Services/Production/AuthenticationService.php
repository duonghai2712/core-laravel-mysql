<?php

namespace App\Services\Production;

use App\Models\Postgres\Admin\Account;
use App\Repositories\AuthenticationRepositoryInterface;
use App\Repositories\Postgres\Admin\PasswordResettableRepositoryInterface;
use App\Repositories\Postgres\Admin\OauthClientRepositoryInterface;
use App\Services\AuthenticationServiceInterface;
use Illuminate\Support\Arr;

class AuthenticationService implements AuthenticationServiceInterface
{
    /** @var \App\Repositories\AuthenticationRepositoryInterface */
    protected $authenticationRepository;

    /** @var \App\Repositories\Postgres\Admin\OauthClientRepositoryInterface */
    protected $oauthClientRepository;

    /** @var  \App\Repositories\Postgres\Admin\PasswordResettableRepositoryInterface */
    protected $passwordResettableRepository;

    /** @var string $resetEmailTitle */
    protected $resetEmailTitle = 'Reset Password';

    /** @var string $resetEmailTemplate */
    protected $resetEmailTemplate = '';

    public function __construct(
        AuthenticationRepositoryInterface $authenticationRepository,
        PasswordResettableRepositoryInterface $passwordResettableRepository,
        OauthClientRepositoryInterface $oauthClientRepository
    )
    {
        $this->authenticationRepository = $authenticationRepository;
        $this->passwordResettableRepository = $passwordResettableRepository;
        $this->oauthClientRepository = $oauthClientRepository;
    }

    public function signInById($id)
    {
        /** @var \App\Models\AuthenticationBase $user */
        $user = $this->authenticationRepository->find($id);
        if (empty($user)) {
            return false;
        }
        $guard = $this->getGuard();
        $guard->login($user);

        return $guard->user();
    }

    public function signIn($input)
    {
        $rememberMe = (bool)Arr::get($input, 'remember_me', 0);
        $guard = $this->getGuard();
        if (!$guard->attempt(['username' => strtolower($input['username']), 'password' => $input['password'], 'is_active' => Account::IS_ACTIVE], $rememberMe, true)) {
            if (!$guard->attempt(['email' => strtolower($input['username']), 'password' => $input['password'], 'is_active' => Account::IS_ACTIVE], $rememberMe, true)){
                return false;
            }
        }

        return $guard->user();
    }

    public function signUp($input)
    {
        $existingUser = $this->authenticationRepository->findByEmail(Arr::get($input, 'email'));
        if (!empty($existingUser)) {
            return null;
        }

        /** @var \App\Models\AuthenticationBase $account */
        $account = $this->authenticationRepository->create($input);
        if (empty($account)) {
            return false;
        }
        $guard = $this->getGuard();
        $guard->login($account);

        return $guard->user();
    }

    public function register($input)
    {
        $existingAccount = $this->authenticationRepository->findByEmail(Arr::get($input, 'email'));
        if (!empty($existingAccount)) {
            return null;
        }

        /** @var \App\Models\AuthenticationBase $storeAccount */
        $storeAccount = $this->authenticationRepository->create($input);
        if (empty($storeAccount)) {
            return false;
        }
        return $storeAccount;
    }

    public function sendPasswordReset($email)
    {
        return false;
    }

    public function signOut()
    {
        $user = $this->getUser();
        if (empty($user)) {
            return false;
        }
        $guard = $this->getGuard();
        $guard->logout();
        \Session::flush();

        return true;
    }

    public function resignation()
    {
        $user = $this->getUser();
        if (empty($user)) {
            return false;
        }
        $guard = $this->getGuard();
        $guard->logout();
        \Session::flush();
        $this->authenticationRepository->delete($user);

        return true;
    }

    public function setUser($user)
    {
        $guard = $this->getGuard();
        $guard->login($user);
    }

    public function getUser()
    {
        $guard = $this->getGuard();

        return $guard->user();
    }

    public function sendPasswordResetEmail($email)
    {
        $user = $this->authenticationRepository->findByEmail($email);
        if (empty($user)) {
            return false;
        }

        $token = $this->passwordResettableRepository->create($user);

        /** @var \App\Services\MailServiceInterface $mailService */
        $mailService = \App::make('App\Services\MailServiceInterface');


        $result = $mailService->sendMailCommon(
            $this->resetEmailTitle,
            config('mail.from'),
            [
                'name' => '',
                'address' => $user->email
            ],
            $this->resetEmailTemplate,
            [
                'token' => $token
            ]
        );

        return $result;
    }

    public function resetPassword($email, $password, $token)
    {
        $user = $this->authenticationRepository->findByEmail($email);
        if (empty($user)) {
            return false;
        }

        $tokenModel = $this->passwordResettableRepository->exists($user, $token);

        if (empty($tokenModel)) {
            return false;
        }
        $this->authenticationRepository->update($user, ['password' => $password]);

        $this->passwordResettableRepository->delete($token);
        $this->setUser($user);

        return true;
    }

    public function isSignedIn()
    {
        $guard = $this->getGuard();

        return $guard->check();
    }

    public function signInByAPI($input)
    {
        /** @var \App\Models\AuthenticationBase $user */
        $user = $this->signIn($input);
        if (empty($user)) {
            return null;
        }

        return $this->setAPIAccessToken($user);
    }

    public function signUpByAPI($input)
    {
        /** @var \App\Models\AuthenticationBase $user */
        $user = $this->signUp($input);
        if (empty($user)) {
            return null;
        }

        return $this->setAPIAccessToken($user);
    }

    public function registerStoreAccount($input)
    {
        /** @var \App\Models\AuthenticationBase $account */
        $account = $this->register($input);
        if (empty($account)) {
            return null;
        }

        return $account;
    }

    public function setAPIAccessToken($user)
    {
        $user->setAPIAccessToken();
        $this->authenticationRepository->save($user);

        return $user;
    }

    /**
     * @return string
     */
    public function getGuardName()
    {
        return '';
    }

    /**
     * @return \Illuminate\Contracts\Auth\Guard
     */
    protected function getGuard()
    {
        return \Auth::guard($this->getGuardName());
    }

    public function checkClient($request)
    {
        $oauthClient = $this->oauthClientRepository->findByIdAndSecret(
            $request->get('client_id'),
            $request->get('client_secret')
        );
        return !empty($oauthClient);
    }

    /**
     * @param $email
     * @return bool
     */
    public function resetPasswordApi($email)
    {
        try{

            $user = $this->authenticationRepository->findByEmail($email);

            if(empty($user)){
                return false;
            }

            $password = str_random(10);

            $result = $this->authenticationRepository->update($user, ['password' => $password]);

            if(empty($result)){
                return false;
            }

            $mailService = \App::make('App\Services\MailServiceInterface');

            $mailResult = $mailService->sendMailCommon(
                $this->resetEmailTitle,
                config('mail.from'),
                [
                    'name' => '',
                    'address' => $user->email
                ],
                $this->resetEmailTemplate,
                [
                    'new_password' => $password
                ]
            );

            return $mailResult;


        }catch (\Exception $e){
            return false;
        }
    }
}
