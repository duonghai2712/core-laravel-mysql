<?php namespace App\Repositories\Postgres\Admin;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface AdminDeviceImageRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function getAllAdminDeviceImagesByFilter($filter);

    public function getAllAdminDeviceImagesWithOnlySecondByFilter($filter);

    public function countAllAdminDeviceImagesByFilter($filter);

    public function insertMulti($params);

    public function getAllAdminDeviceImageByFilter($filter);

    public function delAllAdminDeviceImageByFilter($filter);

    public function getAllAdminDeviceImageWithImagesByFilter($filter);
}
