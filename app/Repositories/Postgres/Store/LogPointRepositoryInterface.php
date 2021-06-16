<?php namespace App\Repositories\Postgres\Store;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface LogPointRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function insertMulti($params);

    public function getAllLogPointByFilter($filter);

    public function getListLogPointByFilter($limit, $filter);
}
