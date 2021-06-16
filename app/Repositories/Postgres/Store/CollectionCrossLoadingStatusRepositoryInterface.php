<?php namespace App\Repositories\Postgres\Store;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface CollectionCrossLoadingStatusRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function insertMulti($params);
}
