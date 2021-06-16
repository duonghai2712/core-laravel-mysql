<?php namespace App\Repositories\Postgres\Store;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface CollectionLoadingStatusRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function insertMulti($params);
}
