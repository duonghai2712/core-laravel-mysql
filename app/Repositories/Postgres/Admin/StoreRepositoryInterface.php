<?php namespace App\Repositories\Postgres\Admin;

use App\Repositories\AuthenticationRepositoryInterface;

interface StoreRepositoryInterface extends AuthenticationRepositoryInterface
{
    public function getAllStoreByFilter($filter);

    public function getAllStoreWithBrandByFilter($filter);

    public function getOneArrayStoreByFilter($filter);

    public function getOneArrayOnlyStoreByFilter($filter);

    public function getOneArrayOnlyStoreWithAdminAccountByFilter($filter);

    public function deleteAllStoreByFilter($filter);

    public function getOneArrayStoreWithBrandAndSubBrandByFilter($filter);

    public function getOneObjectStoreByFilter($filter);

    public function getListStoreByFilter($limit, $filter);

    public function changePointStoreByFilter($filter, $params);

    public function getOneArrayStoreWithAccountByFilter($filter);
}
