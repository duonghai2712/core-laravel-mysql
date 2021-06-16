<?php namespace App\Repositories\Postgres\Store;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface StoreDeviceCollectionRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function insertMulti($params);

    public function getAllStoreDeviceCollectionByFilter($filter);

    public function getAllStoreDeviceCollectionWithOnlySecondByFilter($filter);

    public function delAllStoreDeviceCollectionByFilter($filter);

    public function getAllStoreDeviceCollectionWithCollectionsByFilter($filter);

    public function countAllStoreDeviceCollectionsByFilter($filter);
}
