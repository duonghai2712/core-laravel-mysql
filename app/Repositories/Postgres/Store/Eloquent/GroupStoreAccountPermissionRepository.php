<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Store\GroupStoreAccountPermissionRepositoryInterface;
use \App\Models\Postgres\Store\GroupStoreAccountPermission;

class GroupStoreAccountPermissionRepository extends SingleKeyModelRepository implements GroupStoreAccountPermissionRepositoryInterface
{

    public function getBlankModel()
    {
        return new GroupStoreAccountPermission();
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

    public function getAllGroupStoreAccountPermissionsByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function countAllGroupStoreAccountPermissionsByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->count();

        return $data;
    }

    public function deleteAllGroupStoreAccountPermissionByFilter($filter)
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
                $query = $query->whereIn('group_store_account_permissions.id', $filter['id']);
            } else {
                $query = $query->where('group_store_account_permissions.id', $filter['id']);
            }
        }

        if (isset($filter['group_store_account_id'])) {
            if (is_array($filter['group_store_account_id'])) {
                $query = $query->whereIn('group_store_account_permissions.group_store_account_id', $filter['group_store_account_id']);
            } else {
                $query = $query->where('group_store_account_permissions.group_store_account_id', $filter['group_store_account_id']);
            }
        }

        if (isset($filter['store_id'])) {
            $query = $query->where('group_store_account_permissions.store_id', $filter['store_id']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('group_store_account_permissions.project_id', $filter['project_id']);
        }

        if (isset($filter['permission_id'])) {
            $query = $query->where('group_store_account_permissions.permission_id', $filter['permission_id']);
        }
    }

}
