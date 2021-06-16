<?php namespace App\Repositories\Postgres\Store;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface OrderDeviceRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function getAllOrderDeviceByFilter($filter);

    public function deleteAllOrderDeviceByFilter($filter);

    public function getAllOrderDeviceWithAllByFilter($filter);

    public function getAllOrderDeviceForQueueByFilter($filter);

    public function getListOrderDeviceWithDeviceByFilter($limit, $filter);

    public function createMulti($params);

    public function getAllOrderDeviceWithOrderByFilter($filter);

    public function deleteAllOrderDeviceFromAdminByFilter($filter);

    public function getAllOrderDeviceForAppByFilter($filter);
}
