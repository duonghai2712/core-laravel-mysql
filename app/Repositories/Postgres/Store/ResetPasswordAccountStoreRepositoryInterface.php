<?php namespace App\Repositories\Postgres\Store;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface ResetPasswordAccountStoreRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function getOneObjectByFilter($filter);

    public function createOrUpdateByFilter($filter, $data);

    public function getOneArrayByFilter($filter);

    public function updateResetPasswordByFilter($filter, $params);
}
