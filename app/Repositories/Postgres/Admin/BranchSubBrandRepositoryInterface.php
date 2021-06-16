<?php namespace App\Repositories\Postgres\Admin;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface BranchSubBrandRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function createMultiRecord($params);

    public function getAllBranchSubBrandByFilter($filter);

    public function getAllBranchSubBrandWithOnlyBranchIdByFilter($filter);

    public function getAllBranchSubBrandWithSubBrandByFilter($filter);

    public function getAllBranchSubBrandByFilterOrder($filter);

    public function deleteAllBranchSubBrandByFilter($filter);
}
