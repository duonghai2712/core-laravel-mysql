<?php namespace App\Repositories\Postgres\Store;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface GroupStoreAccountRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function getOneArrayGroupStoreAccountByFilter($filter);

    public function getAllGroupStoreAccountByFilter($filter);

    public function getOneObjectGroupStoreAccountByFilter($filter);

    public function deleteAllGroupStoreAccountByFilter($filter);

    public function getListGroupStoreAccountByFilter($limit, $filter);
}
