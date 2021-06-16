<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use \App\Repositories\Postgres\Admin\StoreSubBrandRepositoryInterface;
use \App\Models\Postgres\Admin\StoreSubBrand;
use \App\Repositories\Eloquent\SingleKeyModelRepository;

class StoreSubBrandRepository extends SingleKeyModelRepository implements StoreSubBrandRepositoryInterface
{

    public function getBlankModel()
    {
        return new StoreSubBrand();
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

    public function getAllStoreSubBrandByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function countAllStoreSubBrandByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->count();

        return $data;
    }

    public function deleteAllStoreSubBrandByFilter($filter)
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
                $query = $query->whereIn('store_sub_brands.id', $filter['id']);
            } else {
                $query = $query->where('store_sub_brands.id', $filter['id']);
            }
        }

        if (isset($filter['store_id'])) {
            if (is_array($filter['store_id'])) {
                $query = $query->whereIn('store_sub_brands.store_id', $filter['store_id']);
            } else {
                $query = $query->where('store_sub_brands.store_id', $filter['store_id']);
            }
        }

        if (isset($filter['sub_brand_id'])) {
            if (is_array($filter['sub_brand_id'])) {
                $query = $query->whereIn('store_sub_brands.sub_brand_id', $filter['sub_brand_id']);
            } else {
                $query = $query->where('store_sub_brands.sub_brand_id', $filter['sub_brand_id']);
            }
        }

        if (isset($filter['account_id'])) {
            $query = $query->where('store_sub_brands.account_id', $filter['account_id']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('store_sub_brands.project_id', $filter['project_id']);
        }

    }

}
