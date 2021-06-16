<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use \App\Repositories\Postgres\Admin\ImageRepositoryInterface;
use \App\Models\Postgres\Admin\Image;
use \App\Repositories\Eloquent\SingleKeyModelRepository;

class ImageRepository extends SingleKeyModelRepository implements ImageRepositoryInterface
{

    public function getBlankModel()
    {
        return new Image();
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

    public function createMulti($params)
    {
        if(!empty($params) && is_array($params)){
            $insertUsers = $this->getBlankModel()->insert($params);
            if($insertUsers){
                return true;
            }
        }
        return false;
    }

    public function getListImageByFilter($limit, $filter)
    {
        $query = $this->withStoreAccount();
        $this->filter($filter, $query);

        $data = $query->paginate($limit)->toArray();

        return $data;
    }

    public function deleteAllImageByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);
        $data = $query->delete();

        return $data;
    }
    public function getAllImageByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);
        $data = $query->get()->toArray();

        return $data;
    }

    public function countAllImageByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);
        $data = $query->count();

        return $data;
    }


    private function withStoreAccount()
    {
        $query = $this->getBlankModel()->select([
            "id",
            "name",
            "source",
            "source_thumb",
            "file_size",
            "width",
            "type",
            "height",
            "account_id",
            "mimes",
            "duration",
            "created_at",
            "dimension"
        ])
            ->with(['createdBy' => function($query){
                $query->select('accounts.id', 'accounts.name', 'accounts.username', 'accounts.email');
            }])
            ->with(['devices' => function($query){
                $query->select('admin_device_images.id', 'admin_device_images.device_id', 'admin_device_images.image_id', 'devices.id');
            }]);
        return $query;
    }

    private function filter($filter, &$query)
    {

        if (isset($filter['key_word'])) {
            $query = $query->search($filter['key_word']);
        }

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('images.id', $filter['id']);
            } else {
                $query = $query->where('images.id', $filter['id']);
            }
        }

        if (isset($filter['type'])) {
            $query = $query->where('images.type', $filter['type']);
        }

        if (isset($filter['deleted_at'])) {
            $query = $query->where('images.deleted_at', null);
        }

        if (isset($filter['level'])) {
            $query = $query->where('images.level', $filter['level']);
        }

        if (isset($filter['account_id'])) {
            $query = $query->where('images.account_id', $filter['account_id']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('images.project_id', $filter['project_id']);
        }

    }

}
