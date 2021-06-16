<?php namespace App\Repositories\Postgres\Admin;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface DeviceStatisticRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function insertMulti($params);

    public function getListDeviceStatisticByFilter($limit, $filter);

    public function getAllDeviceStatisticForExportByFilter($filter);

    public function getOneObjectDeviceStatisticByFilter($filter);
}
