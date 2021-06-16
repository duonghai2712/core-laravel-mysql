<?php namespace App\Repositories\Postgres\Eloquent;

use App\Repositories\Postgres\FileRepositoryInterface;
use App\Models\Postgres\File;
use \App\Repositories\Eloquent\SingleKeyModelRepository;

class FileRepository extends SingleKeyModelRepository implements FileRepositoryInterface
{
    public function getBlankModel()
    {
        return new File();
    }

    public function rules()
    {
        return [
        ];
    }

    public function messages()
    {
        return [
        ];
    }

    public function getByFileCategoryType($fileCategoryType, $order, $direction, $offset, $limit)
    {
        $query = File::whereFileCategoryType($fileCategoryType)->whereIsEnabled(true);

        return $this->getWithQueryBuilder($query, ['id'], 'id', $order, $direction, $offset, $limit);
    }
}
