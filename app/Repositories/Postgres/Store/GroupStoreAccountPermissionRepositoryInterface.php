<?php namespace App\Repositories\Postgres\Store;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface GroupStoreAccountPermissionRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function createMulti($params);

    public function countAllGroupStoreAccountPermissionsByFilter($filter);

    public function getAllGroupStoreAccountPermissionsByFilter($filter);

    public function deleteAllGroupStoreAccountPermissionByFilter($filter);
}
