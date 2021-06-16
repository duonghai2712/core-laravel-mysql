<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Store\StoreCrossDeviceCollectionRepositoryInterface;
use \App\Models\Postgres\Store\StoreCrossDeviceCollection;
use  DB;

class StoreCrossDeviceCollectionRepository extends SingleKeyModelRepository implements StoreCrossDeviceCollectionRepositoryInterface
{

    public function getBlankModel()
    {
        return new StoreCrossDeviceCollection();
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

    public function updateAllStoreCrossDeviceCollectionByFilter($filter, $params)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $data = $query->update($params);

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

    public function getListStoreCrossDeviceCollectionByFilter($limit, $filter)
    {
        $query = $this->withCollection();
        $this->filter($filter, $query);

        $data = $query->paginate($limit)->toArray();

        return $data;
    }

    public function getListStoreCrossDeviceCollectionForDashboardByFilter($limit, $filter)
    {
        $query = $this->withCollectionStatistic();
        $this->filter($filter, $query);

        $data = $query->paginate($limit)->toArray();

        return $data;
    }

    public function getAllStoreCrossDeviceCollectionByFilter($filter)
    {
        $query = $this->withCollection();
        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function getAllStoreCrossDeviceCollectionForDashboardByFilter($filter)
    {
        $query = $this->withCollectionGroupBy();
        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function countAllStoreCrossDeviceCollectionByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $data = $query->count();

        return $data;
    }

    public function deleteAllStoreCrossDeviceCollectionByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $data = $query->update(['status' => StoreCrossDeviceCollection::COLLECTION_STATUS_DELETED]);

        return $data;
    }

    private function withCollectionStatistic()
    {
        $query = $this->getBlankModel()->select([
            "store_cross_device_collections.id",
            "store_cross_device_collections.second",
            "store_cross_device_collections.type",
            "store_cross_device_collections.collection_id",
            "store_cross_device_collections.volume",
            "store_cross_device_collections.status",
            "store_cross_device_collections.order_id",
        ])
            ->with(['collection' => function($query){
                $query->select('collections.id', 'collections.name', 'collections.source', 'collections.source_thumb', 'collections.mimes');
            }]);
        return $query;
    }

    private function withCollectionGroupBy()
    {
        $query = $this->getBlankModel()->select([
            "store_cross_device_collections.collection_id",
        ])->groupBy("store_cross_device_collections.collection_id");
        return $query;
    }

    private function withCollection()
    {
        $query = $this->getBlankModel()->select([
            "store_cross_device_collections.id",
            "store_cross_device_collections.second",
            "store_cross_device_collections.type",
            "store_cross_device_collections.collection_id",
            "store_cross_device_collections.volume",
            "store_cross_device_collections.status",
            "store_cross_device_collections.order_id",
        ])
            ->with(['collection' => function($query){
                $query->select('collections.id', 'collections.name', 'collections.source', 'collections.source_thumb', 'collections.mimes');
            }]);
        return $query;
    }

    private function filter($filter, &$query)
    {

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('store_cross_device_collections.id', $filter['id']);
            } else {
                $query = $query->where('store_cross_device_collections.id', $filter['id']);
            }
        }

        if (isset($filter['store_id'])) {
            if (is_array($filter['store_id'])) {
                $query = $query->whereIn('store_cross_device_collections.store_id', $filter['store_id']);
            } else {
                $query = $query->where('store_cross_device_collections.store_id', $filter['store_id']);
            }
        }

        if (isset($filter['device_id'])) {
            if (is_array($filter['device_id'])) {
                $query = $query->whereIn('store_cross_device_collections.device_id', $filter['device_id']);
            } else {
                $query = $query->where('store_cross_device_collections.device_id', $filter['device_id']);
            }
        }

        if (isset($filter['collection_id'])) {
            if (is_array($filter['collection_id'])) {
                $query = $query->whereIn('store_cross_device_collections.collection_id', $filter['collection_id']);
            } else {
                $query = $query->where('store_cross_device_collections.collection_id', $filter['collection_id']);
            }
        }

        if (isset($filter['order_id'])) {
            if (is_array($filter['order_id'])) {
                $query = $query->whereIn('store_cross_device_collections.order_id', $filter['order_id']);
            } else {
                $query = $query->where('store_cross_device_collections.order_id', $filter['order_id']);
            }
        }

        if (isset($filter['status'])) {
            if (is_array($filter['status'])) {
                $query = $query->whereIn('store_cross_device_collections.status', $filter['status']);
            } else {
                $query = $query->where('store_cross_device_collections.status', $filter['status']);
            }
        }

        if (isset($filter['type'])) {
            $query = $query->where('store_cross_device_collections.type', $filter['type']);
        }

        if (isset($filter['store_account_id'])) {
            $query = $query->where('store_cross_device_collections.account_id', $filter['store_account_id']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('store_cross_device_collections.project_id', $filter['project_id']);
        }

        if (isset($filter['owner'])) {
            $query = $query->where('store_cross_device_collections.owner', $filter['owner']);
        }

    }

}
