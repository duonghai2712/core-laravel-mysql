<?php

namespace App\Services\Postgres\Admin\Production;

use App\Repositories\Postgres\Admin\AccountRepositoryInterface;
use App\Services\Postgres\Admin\AccountServiceInterface;
use App\Services\Production\AuthenticationService;

class AccountService extends AuthenticationService implements AccountServiceInterface
{
    public function __construct(
        AccountRepositoryInterface $accountRepository
    )
    {
        $this->authenticationRepository = $accountRepository;
    }

    public function getGuardName()
    {
        return 'admins';
    }

}
