<?php namespace App\Repositories\Postgres\Admin;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface ResetPasswordAccountRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function getOneObjectByFilter($filter);

    public function createOrUpdateByFilter($filter, $data);

    public function getOneArrayByFilter($filter);

    public function updateResetPasswordByFilter($filter, $params);
}
