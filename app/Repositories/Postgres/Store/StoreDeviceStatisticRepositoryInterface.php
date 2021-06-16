<?php namespace App\Repositories\Postgres\Store;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface StoreDeviceStatisticRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function insertMulti($params);

    public function countTotalTimeStoreUsedByFilter($filter);

    public function getDataForChart($filter);

    public function getDataForStatisticMedia($filter);
}
