<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Store\OrderStoreRepositoryInterface;
use \App\Models\Postgres\Store\OrderStore;

class OrderStoreRepository extends SingleKeyModelRepository implements OrderStoreRepositoryInterface
{

    public function getBlankModel()
    {
        return new OrderStore();
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

    public function getAllOrderStoreByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function deleteAllOrderStoreByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $data = $query->update(['status' => OrderStore::STATUS_DELETE, 'point' => 0]);

        return $data;
    }

    public function getListOrderStoreByFilter($limit, $filter)
    {
        $query = $this->withStores();
        $this->filter($filter, $query);

        $data = $query->paginate($limit)->toArray();

        return $data;
    }

    public function getOneArrayOrderStoreByFilter($filter)
    {
        $query = $this->withStores();
        $this->filter($filter, $query);

        $dataX = $query->first();
        $data = [];
        if (!empty($dataX)){
            $data = $dataX->toArray();
        }

        return $data;
    }

    private function withStores()
    {
        $query = $this->getBlankModel()->select([
            "order_stores.*",
        ])
        ->with(['store' => function($query){
            $query->select('stores.id', 'stores.name', 'stores.address');
        }])
        ->withCount(['branches']);

        return $query;
    }

    private function filter($filter, &$query)
    {

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('order_stores.id', $filter['id']);
            } else {
                $query = $query->where('order_stores.id', $filter['id']);
            }
        }

        if (isset($filter['store_id'])) {
            if (is_array($filter['store_id'])) {
                $query = $query->whereIn('order_stores.store_id', $filter['store_id']);
            } else {
                $query = $query->where('order_stores.store_id', $filter['store_id']);
            }
        }

        if (isset($filter['store_account_id'])) {
            if (is_array($filter['store_account_id'])) {
                $query = $query->whereIn('order_stores.store_account_id', $filter['store_account_id']);
            } else {
                $query = $query->where('order_stores.store_account_id', $filter['store_account_id']);
            }
        }

        if (isset($filter['order_id'])) {
            if (is_array($filter['order_id'])) {
                $query = $query->whereIn('order_stores.order_id', $filter['order_id']);
            } else {
                $query = $query->where('order_stores.order_id', $filter['order_id']);
            }
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('order_stores.project_id', $filter['project_id']);
        }

        if (isset($filter['status'])) {
            if (is_array($filter['status'])) {
                $query = $query->whereIn('order_stores.status', $filter['status']);
            } else {
                $query = $query->where('order_stores.status', $filter['status']);
            }
        }

    }
}
