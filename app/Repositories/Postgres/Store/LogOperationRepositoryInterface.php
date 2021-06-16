<?php namespace App\Repositories\Postgres\Store;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface LogOperationRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function insertMulti($params);

    public function getListLogOperationByFilter($limit, $filter);
}
