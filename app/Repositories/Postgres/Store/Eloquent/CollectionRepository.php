<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Store\CollectionRepositoryInterface;
use \App\Models\Postgres\Store\Collection;
use  DB;

class CollectionRepository extends SingleKeyModelRepository implements CollectionRepositoryInterface
{

    public function getBlankModel()
    {
        return new Collection();
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

    public function getListCollectionByFilter($limit, $filter)
    {
        $query = $this->withStoreAccount();
        $this->filter($filter, $query);

        $data = $query->paginate($limit)->toArray();

        return $data;
    }

    public function getAllCollectionByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);
        $data = $query->get()->toArray();

        return $data;
    }

    public function countAllCollectionByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);
        $data = $query->count();

        return $data;
    }

    public function deleteAllCollectionByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);
        $data = $query->delete();

        return $data;
    }

    public function getDataForStatisticMedia($filter)
    {
        $query = $this->getBlankModel()->select([
            "collections.store_id",
            DB::raw('COUNT(case when collections.type = ' . Collection::IMAGE . ' then 1 ELSE NULL END) AS total_image'),
            DB::raw('COUNT(case when collections.type = ' . Collection::VIDEO . ' then 1 ELSE NULL end) AS total_video'),
        ])->groupBy("collections.store_id");

        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    private function withStoreAccount()
    {
        $query = $this->getBlankModel()->select([
                "id",
                "name",
                "source",
                "source_thumb",
                "file_size",
                "width",
                "type",
                "height",
                "store_account_id",
                "mimes",
                "duration",
                "created_at",
                "dimension"
        ])
            ->with(['createdBy' => function($query){
                $query->select('store_accounts.id', 'store_accounts.representative', 'store_accounts.username', 'store_accounts.email');
            }])
            ->with(['devices' => function($query){
                $query->select('store_device_collections.id', 'store_device_collections.device_id', 'store_device_collections.collection_id', 'devices.id');
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
                $query = $query->whereIn('collections.id', $filter['id']);
            } else {
                $query = $query->where('collections.id', $filter['id']);
            }
        }

        if (isset($filter['store_id'])) {
            if (is_array($filter['store_id'])) {
                $query = $query->whereIn('collections.store_id', $filter['store_id']);
            } else {
                $query = $query->where('collections.store_id', $filter['store_id']);
            }
        }

        if (isset($filter['type'])) {
            $query = $query->where('collections.type', $filter['type']);
        }

        if (isset($filter['deleted_at'])) {
            $query = $query->where('collections.deleted_at', null);
        }

        if (isset($filter['level'])) {
            $query = $query->where('collections.level', $filter['level']);
        }

        if (isset($filter['store_account_id'])) {
            $query = $query->where('collections.store_account_id', $filter['store_account_id']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('collections.project_id', $filter['project_id']);
        }

    }

}
