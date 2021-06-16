<?php namespace App\Repositories\Postgres\Admin;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface BranchBrandRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function createMultiRecord($params);

    public function getAllBranchBrandByFilter($filter);

    public function getAllBranchBrandWithOnlyBranchIdByFilter($filter);

    public function deleteAllBranchBrandByFilter($filter);
}
