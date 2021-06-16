<?php namespace App\Repositories\Postgres\Admin;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface StoreBrandRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function createMultiRecord($params);

    public function getAllStoreBrandByFilter($filter);

    public function countAllStoreBrandByFilter($filter);

    public function deleteAllStoreBrandByFilter($filter);

}
