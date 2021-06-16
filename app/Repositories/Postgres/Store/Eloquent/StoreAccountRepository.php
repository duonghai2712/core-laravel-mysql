<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Repositories\Eloquent\AuthenticationRepository;
use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Store\StoreAccountRepositoryInterface;
use \App\Models\Postgres\Store\StoreAccount;

class StoreAccountRepository extends AuthenticationRepository implements StoreAccountRepositoryInterface
{

    protected $querySearchTargets = ['name', 'email'];

    public function getBlankModel()
    {
        return new StoreAccount();
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

    public function getListStoreAccountByFilter($limit, $filter)
    {
        $query = $this->withBranch();
        $this->filter($filter, $query);
        $arrFields = [
            "id",
            "email",
            "phone_number",
            "username",
            "is_active",
            "profile_collection_id",
            "group_store_account_id",
            "store_id",
            "branch_id",
            "representative",
        ];

        $data = $query->select($arrFields)->paginate($limit)->toArray();

        return $data;
    }

    public function getOneObjectStoreAccountByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $data = $query->first();

        return $data;
    }

    public function getOneArrayStoreAccountByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $dataX = $query->first();
        $data = [];

        if (!empty($dataX)){
            $data = $dataX->toArray();
        }

        return $data;
    }

    public function getOneArrayStoreAccountForLoginByFilter($filter)
    {
        $query = $this->withStore();
        $this->filter($filter, $query);

        $dataX = $query->first();
        $data = [];

        if (!empty($dataX)){
            $data = $dataX->toArray();
        }

        return $data;
    }

    public function deleteAllStoreAccountByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $data = $query->delete();

        return $data;
    }

    public function getOneArrayStoreAccountWithBranchByFilter($filter)
    {
        $query = $this->withBranch();
        $this->filter($filter, $query);
        $arrFields = [
            "id",
            "email",
            "branch_id",
            "phone_number",
            "username",
            "profile_collection_id",
            "group_store_account_id",
            "store_id",
            "branch_id",
            "representative",
        ];

        $dataX = $query->select($arrFields)->first();
        $data = [];

        if (!empty($dataX)){
            $data = $dataX->toArray();
        }

        return $data;
    }

    public function checkingFieldByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);
        $dataX = $query->first();
        $data = [];
        if (!empty($dataX)){
            $data = $dataX->toArray();
        }
        return $data;
    }

    public function getAllStoreAccountByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);
        $data = $query->get()->toArray();

        return $data;
    }

    public function updateApiTokenAllStoreAccountByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);
        $data = $query->update(['api_access_token' => '']);

        return $data;
    }

    public function getAllStoreAccountWithOnlyBranchIdByFilter($filter)
    {
        $query = $this->getBlankModel()->select(['id', 'branch_id']);
        $this->filter($filter, $query);
        $data = $query->get()->toArray();

        return $data;
    }

    private function withBranch(){
        $query = $this->getBlankModel()
            ->with(['image' => function($query){
                $query->select('collections.id', 'collections.source', 'collections.mimes');
            }])
            ->with(['store' => function($query){
                $query->select('stores.id', 'stores.name', 'stores.address');
            }])
            ->with(['branch' => function($query){
                $query->select('branches.id', 'branches.name', 'branches.make_ads');
            }])
            ->with(['group' => function($query){
                $query->select('group_store_accounts.id', 'group_store_accounts.name');
            }]);
        return $query;
    }

    private function withStore(){
        $query = $this->getBlankModel()
            ->with(['store' => function($query){
                $query->select('stores.id', 'stores.name', 'stores.total_point', 'stores.current_point', 'stores.slug');
            }])
            ->with(['branch' => function($query){
                $query->select('branches.id', 'branches.name', 'branches.total_point', 'branches.current_point', 'branches.slug', 'branches.store_account_id', 'branches.make_ads');
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
                $query = $query->whereIn('store_accounts.id', $filter['id']);
            } else {
                $query = $query->where('store_accounts.id', $filter['id']);
            }
        }

        if (isset($filter['id_not_in'])) {
            if (is_array($filter['id_not_in'])) {
                $query = $query->whereNotIn('store_accounts.id', $filter['id_not_in']);
            } else {
                $query = $query->where('store_accounts.id', '!=', $filter['id_not_in']);
            }
        }

        if (isset($filter['not_in_store_id'])) {
            if (is_array($filter['not_in_store_id'])) {
                $query = $query->whereNotIn('store_accounts.store_id', $filter['not_in_store_id']);
            } else {
                $query = $query->where('store_accounts.store_id', '!=', $filter['not_in_store_id']);
            }
        }

        if (isset($filter['store_id'])) {
            if (is_array($filter['store_id'])) {
                $query = $query->whereIn('store_accounts.store_id', $filter['store_id']);
            } else {
                $query = $query->where('store_accounts.store_id', $filter['store_id']);
            }
        }

        if (isset($filter['branch_id'])) {
            if (is_array($filter['branch_id'])) {
                $query = $query->whereIn('store_accounts.branch_id', $filter['branch_id']);
            } else {
                $query = $query->where('store_accounts.branch_id', $filter['branch_id']);
            }
        }

        if (isset($filter['api_access_token'])) {
            $query = $query->where('store_accounts.api_access_token', $filter['api_access_token']);
        }

        if (isset($filter['deleted_at'])) {
            $query = $query->where('store_accounts.deleted_at', null);
        }

        if (isset($filter['is_active'])) {
            $query = $query->where('store_accounts.is_active', $filter['is_active']);
        }

        if (isset($filter['group_store_account_id'])) {
            $query = $query->where('store_accounts.group_store_account_id', $filter['group_store_account_id']);
        }

        if (isset($filter['role'])) {
            $query = $query->where('store_accounts.role', $filter['role']);
        }

        if (isset($filter['email'])) {
            $query = $query->where('store_accounts.email', $filter['email']);
        }

        if (isset($filter['phone_number'])) {
            $query = $query->where('store_accounts.phone_number', $filter['phone_number']);
        }

        if (isset($filter['username'])) {
            $query = $query->where('store_accounts.username', $filter['username']);
        }

        if (isset($filter['account_id'])) {
            $query = $query->where('store_accounts.account_id', $filter['account_id']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('store_accounts.project_id', $filter['project_id']);
        }

    }

}
