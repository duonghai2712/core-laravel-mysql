<?php namespace App\Repositories\Postgres\Admin;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface ImageLoadingStatusRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function insertMulti($params);
}
