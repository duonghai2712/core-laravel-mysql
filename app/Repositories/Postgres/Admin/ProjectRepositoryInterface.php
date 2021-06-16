<?php namespace App\Repositories\Postgres\Admin;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface ProjectRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function getOneProjectByFilter($filter);
}
