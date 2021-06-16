<?php namespace App\Repositories\Postgres\Store;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface OrderBranchRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function insertMulti($params);

    public function getAllOrderBranchByFilter($filter);

    public function deleteAllOrderBranchByFilter($filter);

    public function getAllOrderBranchWithBranchByFilter($filter);

    public function getAllOrderBranchWithBranchAndStoreAccountByFilter($filter);

    public function getListOrderBranchByFilter($limit, $filter);

    public function getListOrderBranchCrossByFilter($limit, $filter);

    public function getAllOrderBranchWithOrderAndBranchByFilter($filter);

    public function getOneArrayOrderBranchByFilter($filter);
}
