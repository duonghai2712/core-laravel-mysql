<?php namespace App\Repositories\Postgres;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface IndustryRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function createMulti($params);

    public function getAllIndustryByFilter($filter);

    public function deleteAllIndustryByFilter($filter);
}
