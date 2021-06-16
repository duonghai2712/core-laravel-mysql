<?php namespace App\Repositories\Postgres\Store;

use App\Repositories\AuthenticationRepositoryInterface;

interface StoreAccountRepositoryInterface extends AuthenticationRepositoryInterface
{
    public function getOneObjectStoreAccountByFilter($filter);

    public function deleteAllStoreAccountByFilter($filter);

    public function getOneArrayStoreAccountWithBranchByFilter($filter);

    public function checkingFieldByFilter($filter);

    public function getListStoreAccountByFilter($limit, $filter);

    public function getOneArrayStoreAccountForLoginByFilter($filter);

    public function getAllStoreAccountByFilter($filter);

    public function updateApiTokenAllStoreAccountByFilter($filter);

    public function getAllStoreAccountWithOnlyBranchIdByFilter($filter);

    public function getOneArrayStoreAccountByFilter($filter);
}
