<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Store\PermissionRepositoryInterface;
use \App\Models\Postgres\Store\Permission;

class PermissionRepository extends SingleKeyModelRepository implements PermissionRepositoryInterface
{

    public function getBlankModel()
    {
        return new Permission();
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

    public function getAllPermissionsByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function countAllPermissionsByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->count();

        return $data;
    }

    public function deleteAllPermissionsByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->delete();

        return $data;
    }

    private function filter($filter, &$query)
    {

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('permissions.id', $filter['id']);
            } else {
                $query = $query->where('permissions.id', $filter['id']);
            }
        }

        if (isset($filter['deleted_at'])) {
            $query = $query->where('permissions.deleted_at', null);
        }

    }

}
