<?php namespace App\Repositories\Postgres\Admin;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface BranchRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function getOneArrayBranchWithBrandByFilter($filter);

    public function getOneArrayBranchByFilter($filter);

    public function getAllBranchForStoreByFilter($filter);

    public function getAllBranchByFilter($filter);

    public function getOneObjectBranchByFilter($filter);

    public function deleteAllBranchByFilter($filter);

    public function changePointBranchByFilter($filter, $params);

    public function getListBranchByFilter($limit, $filter);
}
