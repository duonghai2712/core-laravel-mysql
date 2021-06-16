<?php namespace App\Repositories\Postgres\Admin;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface ImageRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    public function getListImageByFilter($limit, $filter);

    public function deleteAllImageByFilter($filter);

    public function createMulti($params);

    public function getAllImageByFilter($filter);

    public function countAllImageByFilter($filter);
}
