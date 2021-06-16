<?php namespace App\Repositories\Postgres\Store;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface OrderRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function getAllOrderByFilter($filter);

    public function updateAllOrderByFilter($filter, $params);

    public function getListOrderByFilter($limit, $filter);

    public function getListOrderCrossByFilter($limit, $filter);

    public function getOneArrayOrderByFilter($filter);

    public function getAllOrderWithOrderByByFilter($filter);

    public function getAllOrderWithOrderDeviceByFilter($filter);

    public function getAllOrderWithTimeFrameByFilter($filter);

    public function getOneObjectOrderByFilter($filter);
}
