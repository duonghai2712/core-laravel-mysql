<?php namespace App\Repositories\Postgres\Admin;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface DeviceRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function getOneArrayDeviceWithStoreAndBranchByFilter($filter);

    public function getOneArrayDeviceByFilter($filter);

    public function getOneArrayDeviceForAppByFilter($filter);

    public function getAllDeviceByFilter($filter);

    public function countAllDeviceByFilter($filter);

    public function getAllDeviceWithCollectionByFilter($filter);

    public function getAllDeviceWithRankByFilter($filter);

    public function getOneObjectDeviceByFilter($filter);

    public function deleteAllDeviceByFilter($filter);

    public function getListDeviceByFilter($limit, $filter);

    public function incrementByFilter($filter, $field, $number);

    public function decrementByFilter($filter, $field, $number);

    public function updateAllDeviceByFilter($filter, $params);

    public function getDetailDeviceWithCollectionSelfByFilter($filter);
}
