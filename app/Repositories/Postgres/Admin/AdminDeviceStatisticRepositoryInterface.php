<?php namespace App\Repositories\Postgres\Admin;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface AdminDeviceStatisticRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function insertMulti($params);
}
