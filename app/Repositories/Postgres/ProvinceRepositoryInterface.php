<?php namespace App\Repositories\Postgres;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface ProvinceRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function createMulti($params);

    public function getAllProvinceByFilter($filter);

    public function deleteAllProvinceByFilter($filter);
}
