<?php namespace App\Repositories\Postgres\Admin;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface DeviceLoadingStatusRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function getOneObjectDeviceLoadingStatusByFilter($filter);

    public function insertMulti($params);
}
