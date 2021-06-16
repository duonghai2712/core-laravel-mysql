<?php namespace App\Repositories\Postgres\Admin;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface StoreSubBrandRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function createMultiRecord($params);

    public function countAllStoreSubBrandByFilter($filter);

    public function getAllStoreSubBrandByFilter($filter);

    public function deleteAllStoreSubBrandByFilter($filter);
}
