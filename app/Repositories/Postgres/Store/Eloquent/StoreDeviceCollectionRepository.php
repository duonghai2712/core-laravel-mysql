<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Store\StoreDeviceCollectionRepositoryInterface;
use \App\Models\Postgres\Store\StoreDeviceCollection;
use  DB;

class StoreDeviceCollectionRepository extends SingleKeyModelRepository implements StoreDeviceCollectionRepositoryInterface
{

    public function getBlankModel()
    {
        return new StoreDeviceCollection();
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

    public function getAllStoreDeviceCollectionByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $data = $query->orderBy('position', 'asc')->get()->toArray();

        return $data;
    }

    public function getAllStoreDeviceCollectionWithOnlySecondByFilter($filter)
    {
        $query = $this->getBlankModel()->select('id', 'second');
        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function delAllStoreDeviceCollectionByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $data = $query->delete();

        return $data;
    }

    public function getAllStoreDeviceCollectionWithCollectionsByFilter($filter)
    {
        $query = $this->withCollections();
        $this->filter($filter, $query);

        $data = $query->orderBy('position', 'asc')->get()->toArray();

        return $data;
    }

    public function countAllStoreDeviceCollectionsByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $data = $query->count();

        return $data;
    }

    private function withCollections()
    {
        $query = $this->getBlankModel()
            ->with(['device' => function($query){
                $query->select('devices.id', 'devices.store_id', 'devices.branch_id', 'devices.block_ads')
                    ->with(['branch' => function($query){
                        $query->select('branches.id', 'branches.rank_id');
                    }]);
            }])
            ->with(['collection' => function($query){
                $query->select('collections.id', 'collections.source_thumb', 'collections.source', 'collections.mimes');
            }]);
        return $query;
    }

    private function filter($filter, &$query)
    {

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('store_device_collections.id', $filter['id']);
            } else {
                $query = $query->where('store_device_collections.id', $filter['id']);
            }
        }

        if (isset($filter['store_id'])) {
            if (is_array($filter['store_id'])) {
                $query = $query->whereIn('store_device_collections.store_id', $filter['store_id']);
            } else {
                $query = $query->where('store_device_collections.store_id', $filter['store_id']);
            }
        }

        if (isset($filter['device_id'])) {
            if (is_array($filter['device_id'])) {
                $query = $query->whereIn('store_device_collections.device_id', $filter['device_id']);
            } else {
                $query = $query->where('store_device_collections.device_id', $filter['device_id']);
            }
        }

        if (isset($filter['collection_id'])) {
            if (is_array($filter['collection_id'])) {
                $query = $query->whereIn('store_device_collections.collection_id', $filter['collection_id']);
            } else {
                $query = $query->where('store_device_collections.collection_id', $filter['collection_id']);
            }
        }

        if (isset($filter['type'])) {
            $query = $query->where('store_device_collections.type', $filter['type']);
        }

        if (isset($filter['store_account_id'])) {
            $query = $query->where('store_device_collections.account_id', $filter['store_account_id']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('store_device_collections.project_id', $filter['project_id']);
        }

        if (isset($filter['owner'])) {
            $query = $query->where('store_device_collections.owner', $filter['owner']);
        }

    }

}
