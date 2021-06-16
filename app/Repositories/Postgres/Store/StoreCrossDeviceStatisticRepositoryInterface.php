<?php namespace App\Repositories\Postgres\Store;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface StoreCrossDeviceStatisticRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function insertMulti($params);

    public function getListStoreCrossDeviceStatisticByFilter($limit, $filter);

    public function getAllStoreCrossDeviceStatisticByFilter($filter);

    public function countTotalTimePlayCrossUsedByFilter($filter);

}
