<?php namespace App\Repositories\Postgres\Store;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface CollectionRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function createMulti($params);

    public function getListCollectionByFilter($limit, $filter);

    public function deleteAllCollectionByFilter($filter);

    public function getAllCollectionByFilter($filter);

    public function countAllCollectionByFilter($filter);

    public function getDataForStatisticMedia($filter);
}
