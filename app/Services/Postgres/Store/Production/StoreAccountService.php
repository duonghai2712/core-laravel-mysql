<?php namespace App\Services\Postgres\Store\Production;

use App\Repositories\Postgres\Store\StoreAccountRepositoryInterface;
use \App\Services\Postgres\Store\StoreAccountServiceInterface;

use App\Services\Production\AuthenticationService;

class StoreAccountService extends AuthenticationService implements StoreAccountServiceInterface
{
    public function __construct(
        StoreAccountRepositoryInterface $storeAccountRepository
    )
    {
        $this->authenticationRepository = $storeAccountRepository;
    }

    public function getGuardName()
    {
        return 'stores';
    }
}
