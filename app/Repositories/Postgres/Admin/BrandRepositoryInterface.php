<?php namespace App\Repositories\Postgres\Admin;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface BrandRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function getOneArrayBrandWithSubBrandByFilter($filter);

    public function getOneObjectBrandByFilter($filter);

    public function getAllBrandByFilter($filter);

    public function countAllBrandByFilter($filter);

    public function getAllBrandWithHasSubBrandByFilter($filter);

    public function getListBrandByFilter($limit, $filter);

    public function deleteAllBrandByFilter($filter);
}
