<?php namespace App\Repositories\Postgres\Admin;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface RankRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function getOneArrayRankByFilter($filter);

    public function getOneObjectRankByFilter($filter);

    public function getAllRankByFilter($filter);

    public function deleteAllRankByFilter($filter);

    public function getListRankByFilter($limit, $filter);
}
