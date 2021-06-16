<?php namespace App\Repositories\Postgres\Store;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface TimeFrameRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function createMulti($params);

    public function getAllTimeFramesByFilter($filter);
}
