<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use \App\Repositories\Postgres\Admin\StoreBrandRepositoryInterface;
use \App\Models\Postgres\Admin\StoreBrand;
use \App\Repositories\Eloquent\SingleKeyModelRepository;

class StoreBrandRepository extends SingleKeyModelRepository implements StoreBrandRepositoryInterface
{

    public function getBlankModel()
    {
        return new StoreBrand();
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

    public function createMultiRecord($params)
    {
        if(!empty($params) && is_array($params)){
            $insertUsers = $this->getBlankModel()->insert($params);
            if($insertUsers){
                return $insertUsers;
            }
        }
        return false;
    }

    public function getAllStoreBrandByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function countAllStoreBrandByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->count();

        return $data;
    }

    public function deleteAllStoreBrandByFilter($filter)
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
                $query = $query->whereIn('store_brands.id', $filter['id']);
            } else {
                $query = $query->where('store_brands.id', $filter['id']);
            }
        }

        if (isset($filter['store_id'])) {
            if (is_array($filter['store_id'])) {
                $query = $query->whereIn('store_brands.store_id', $filter['store_id']);
            } else {
                $query = $query->where('store_brands.store_id', $filter['store_id']);
            }
        }

        if (isset($filter['brand_id'])) {
            if (is_array($filter['brand_id'])) {
                $query = $query->whereIn('store_brands.brand_id', $filter['brand_id']);
            } else {
                $query = $query->where('store_brands.brand_id', $filter['brand_id']);
            }
        }

        if (isset($filter['account_id'])) {
            $query = $query->where('store_brands.account_id', $filter['account_id']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('store_brands.project_id', $filter['project_id']);
        }

    }
}
