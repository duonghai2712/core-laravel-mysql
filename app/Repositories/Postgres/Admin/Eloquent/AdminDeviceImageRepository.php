<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Admin\AdminDeviceImageRepositoryInterface;
use \App\Models\Postgres\Admin\AdminDeviceImage;

class AdminDeviceImageRepository extends SingleKeyModelRepository implements AdminDeviceImageRepositoryInterface
{

    public function getBlankModel()
    {
        return new AdminDeviceImage();
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

    public function getAllAdminDeviceImagesByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);
        $data = $query->get()->toArray();

        return $data;
    }

    public function getAllAdminDeviceImagesWithOnlySecondByFilter($filter)
    {
        $query = $this->getBlankModel()->select(['id', 'second']);
        $this->filter($filter, $query);
        $data = $query->get()->toArray();

        return $data;
    }

    public function countAllAdminDeviceImagesByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);
        $data = $query->count();

        return $data;
    }

    public function insertMulti($params)
    {
        if(!empty($params) && is_array($params)){
            $insertUsers = $this->getBlankModel()->insert($params);
            if($insertUsers){
                return true;
            }
        }
        return false;
    }
    public function getAllAdminDeviceImageByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $data = $query->orderBy('position', 'asc')->get()->toArray();

        return $data;
    }

    public function delAllAdminDeviceImageByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $data = $query->delete();

        return $data;
    }

    public function getAllAdminDeviceImageWithImagesByFilter($filter)
    {
        $query = $this->withImages();
        $this->filter($filter, $query);

        $data = $query->orderBy('position', 'asc')->get()->toArray();

        return $data;
    }

    private function withImages()
    {
        $query = $this->getBlankModel()
            ->with(['device' => function($query){
                $query->select('devices.id', 'devices.store_id', 'devices.branch_id', 'devices.block_ads')
                    ->with(['branch' => function($query){
                        $query->select('branches.id', 'branches.rank_id');
                    }]);
            }])
            ->with(['image' => function($query){
                $query->select('images.id', 'images.source_thumb', 'images.source', 'images.mimes');
            }]);
        return $query;
    }

    private function filter($filter, &$query)
    {
        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('admin_device_images.id', $filter['id']);
            } else {
                $query = $query->where('admin_device_images.id', $filter['id']);
            }
        }

        if (isset($filter['image_id'])) {
            if (is_array($filter['image_id'])) {
                $query = $query->whereIn('admin_device_images.image_id', $filter['image_id']);
            } else {
                $query = $query->where('admin_device_images.image_id', $filter['image_id']);
            }
        }

        if (isset($filter['device_id'])) {
            if (is_array($filter['device_id'])) {
                $query = $query->whereIn('admin_device_images.device_id', $filter['device_id']);
            } else {
                $query = $query->where('admin_device_images.device_id', $filter['device_id']);
            }
        }

        if (isset($filter['type'])) {
            $query = $query->where('admin_device_images.type', $filter['type']);
        }

        if (isset($filter['deleted_at'])) {
            $query = $query->where('admin_device_images.deleted_at', null);
        }

        if (isset($filter['account_id'])) {
            $query = $query->where('admin_device_images.account_id', $filter['account_id']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('admin_device_images.project_id', $filter['project_id']);
        }

        if (isset($filter['owner'])) {
            $query = $query->where('admin_device_images.owner', $filter['owner']);
        }

    }
}
