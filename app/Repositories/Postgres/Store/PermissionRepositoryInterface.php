<?php namespace App\Repositories\Postgres\Store;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface PermissionRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function createMulti($params);

    public function countAllPermissionsByFilter($filter);

    public function getAllPermissionsByFilter($filter);

    public function deleteAllPermissionsByFilter($filter);
}
