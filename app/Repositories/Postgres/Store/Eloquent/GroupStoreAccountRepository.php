<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Store\GroupStoreAccountRepositoryInterface;
use \App\Models\Postgres\Store\GroupStoreAccount;

class GroupStoreAccountRepository extends SingleKeyModelRepository implements GroupStoreAccountRepositoryInterface
{

    public function getBlankModel()
    {
        return new GroupStoreAccount();
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

    public function getListGroupStoreAccountByFilter($limit, $filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $data = $query->paginate($limit)->toArray();

        return $data;
    }

    public function getOneObjectGroupStoreAccountByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->first();

        return $data;
    }


    public function getOneArrayGroupStoreAccountByFilter($filter)
    {
        $query = $this->withPermission();

        $this->filter($filter, $query);

        $dataX = $query->first();
        $data = [];

        if (!empty($dataX)){
            $data = $dataX->toArray();
        }

        return $data;
    }

    public function getAllGroupStoreAccountByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    private function withPermission()
    {
        $query = $this->getBlankModel()
            ->with(['permissions' => function($query){
                $query->select(
                    'group_store_account_permissions.id',
                    'group_store_account_permissions.permission_id',
                    'group_store_account_permissions.group_store_account_id',
                    'group_store_account_permissions.view',
                    'group_store_account_permissions.add',
                    'group_store_account_permissions.update',
                    'group_store_account_permissions.delete'
                );
            }]);
        return $query;
    }

    public function deleteAllGroupStoreAccountByFilter($filter)
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
                $query = $query->whereIn('group_store_accounts.id', $filter['id']);
            } else {
                $query = $query->where('group_store_accounts.id', $filter['id']);
            }
        }

        if (isset($filter['deleted_at'])) {
            $query = $query->where('group_store_accounts.deleted_at', null);
        }

        if (isset($filter['store_id'])) {
            $query = $query->where('group_store_accounts.store_id', $filter['store_id']);
        }

        if (isset($filter['store_account_id'])) {
            $query = $query->where('group_store_accounts.store_account_id', $filter['store_account_id']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('group_store_accounts.project_id', $filter['project_id']);
        }

    }
}
