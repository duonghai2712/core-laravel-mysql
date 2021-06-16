<?php namespace App\Repositories\Postgres\Store;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface OrderStoreRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function insertMulti($params);

    public function getAllOrderStoreByFilter($filter);

    public function deleteAllOrderStoreByFilter($filter);

    public function getListOrderStoreByFilter($limit, $filter);

    public function getOneArrayOrderStoreByFilter($filter);
}
