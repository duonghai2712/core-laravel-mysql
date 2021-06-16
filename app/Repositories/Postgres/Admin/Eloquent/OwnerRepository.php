<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Admin\OwnerRepositoryInterface;
use \App\Models\Postgres\Admin\Owner;

class OwnerRepository extends SingleKeyModelRepository implements OwnerRepositoryInterface
{

    public function getBlankModel()
    {
        return new Owner();
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

    public function getListOwnerByFilter($limit, $filter)
    {
        $query = $this->withCustomer();
        $this->filter($filter, $query);

        $data = $query->paginate($limit)->toArray();

        return $data;
    }

    public function getOneArrayOwnerByFilter($filter)
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

    public function deleteAllCollectionByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $data = $query->delete();

        return $data;
    }

    private function withCustomer()
    {
        $query = $this->getBlankModel()->select([
            "id",
            "name",
            'level',
            'created_at',
            'customer_account_id',
        ])
            ->with(['customerAccount' => function($query){
                $query->select('customer_accounts.id');
            }])
            ->withCount(['images']);
        return $query;
    }

    private function filter($filter, &$query)
    {
        if (isset($filter['key_word'])) {
            $query = $query->search($filter['key_word']);
        }

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('owners.id', $filter['id']);
            } else {
                $query = $query->where('owners.id', $filter['id']);
            }
        }


        if (isset($filter['deleted_at'])) {
            $query = $query->where('owners.deleted_at', null);
        }

        if (isset($filter['customer_account_id'])) {
            if (is_array($filter['customer_account_id'])) {
                $query = $query->whereIn('owners.customer_account_id', $filter['customer_account_id']);
            } else {
                $query = $query->where('owners.customer_account_id', $filter['customer_account_id']);
            }
        }

        if (isset($filter['account_id'])) {
            $query = $query->where('owners.account_id', $filter['account_id']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('owners.project_id', $filter['project_id']);
        }

    }

}
