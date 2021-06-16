<?php namespace App\Repositories\Postgres;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface DistrictRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function createMulti($params);

    public function getAllDistrictByFilter($filter);

    public function deleteAllDistrictByFilter($filter);
}
