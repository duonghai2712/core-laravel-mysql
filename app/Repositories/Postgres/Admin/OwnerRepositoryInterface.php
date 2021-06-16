<?php namespace App\Repositories\Postgres\Admin;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface OwnerRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function insertMulti($params);

    public function getListOwnerByFilter($limit, $filter);

    public function getOneArrayOwnerByFilter($filter);

    public function deleteAllCollectionByFilter($filter);
}
