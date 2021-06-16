<?php namespace App\Repositories\Postgres\Store;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface StoreCrossDeviceCollectionRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function updateAllStoreCrossDeviceCollectionByFilter($filter, $params);

    public function getListStoreCrossDeviceCollectionByFilter($limit, $filter);

    public function getListStoreCrossDeviceCollectionForDashboardByFilter($limit, $filter);

    public function getAllStoreCrossDeviceCollectionByFilter($filter);

    public function getAllStoreCrossDeviceCollectionForDashboardByFilter($filter);

    public function countAllStoreCrossDeviceCollectionByFilter($filter);

    public function deleteAllStoreCrossDeviceCollectionByFilter($filter);

    public function insertMulti($param);
}
