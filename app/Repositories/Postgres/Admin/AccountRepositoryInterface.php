<?php namespace App\Repositories\Postgres\Admin;

use App\Repositories\AuthenticationRepositoryInterface;

interface AccountRepositoryInterface extends AuthenticationRepositoryInterface
{
    public function getOneArrayAccountByFilter($filter);

    public function getOneObjectAccountByFilter($filter);

    public function deleteAllAccountByFilter($filter);

    public function getAllAccountsByFilter($filter);
}
