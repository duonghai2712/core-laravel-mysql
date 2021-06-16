<?php namespace App\Repositories\Postgres\Admin;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface SubBrandRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function deleteSubBrandByFilter($filter);

    public function createMulti($params);

    public function countAllSubBrandByFilter($filter);

    public function getAllSubBrandByFilter($filter);

    public function updateSubBrandBuFilter($filter , $params);
}
