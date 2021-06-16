<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Store\LogOperationRepositoryInterface;
use \App\Models\Postgres\Store\LogOperation;

class LogOperationRepository extends SingleKeyModelRepository implements LogOperationRepositoryInterface
{

    public function getBlankModel()
    {
        return new LogOperation();
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

    public function getListLogOperationByFilter($limit, $filter)
    {
        $query = $this->withAccount();
        $this->filter($filter, $query);

        $data = $query->orderBy('log_operations.created_at', 'desc')->paginate($limit)->toArray();

        return $data;
    }

    private function withAccount()
    {
        $query = $this->getBlankModel()->select([
            "log_operations.*",
        ])
        ->with(['storeAccount' => function($query){
            $query->select([
                "store_accounts.id",
                "store_accounts.username",
                "store_accounts.representative",
                "store_accounts.role",
                "store_accounts.store_id",
                "store_accounts.branch_id",
            ]);
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
                $query = $query->whereIn('log_operations.id', $filter['id']);
            } else {
                $query = $query->where('log_operations.id', $filter['id']);
            }
        }

        if (isset($filter['device_id'])) {
            if (is_array($filter['device_id'])) {
                $query = $query->whereIn('log_operations.device_id', $filter['device_id']);
            } else {
                $query = $query->where('log_operations.device_id', $filter['device_id']);
            }
        }

        if (isset($filter['store_id'])) {
            if (is_array($filter['store_id'])) {
                $query = $query->whereIn('log_operations.store_id', $filter['store_id']);
            } else {
                $query = $query->where('log_operations.store_id', $filter['store_id']);
            }
        }

        if (isset($filter['start_date']) && isset($filter['end_date'])) {
            $query = $query->whereDate('log_operations.created_at', '>=', $filter['start_date'])->whereDate('log_operations.created_at', '<=', $filter['end_date']);
        }

        if (isset($filter['store_account_id'])) {
            $query = $query->where('log_operations.store_account_id', $filter['store_account_id']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('log_operations.project_id', $filter['project_id']);
        }

    }

}
