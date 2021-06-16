<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use \App\Repositories\Postgres\Admin\SubBrandRepositoryInterface;
use \App\Models\Postgres\Admin\SubBrand;
use \App\Repositories\Eloquent\SingleKeyModelRepository;

class SubBrandRepository extends SingleKeyModelRepository implements SubBrandRepositoryInterface
{

    public function getBlankModel()
    {
        return new SubBrand();
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

    public function deleteSubBrandByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->delete();

        return $data;
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

    public function getAllSubBrandByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function countAllSubBrandByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->count();

        return $data;
    }

    public function updateSubBrandBuFilter($filter , $params)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->update($params);

        return $data;
    }


    private function filter($filter, &$query)
    {

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('sub_brands.id', $filter['id']);
            } else {
                $query = $query->where('sub_brands.id', $filter['id']);
            }
        }

        if (isset($filter['id_not_in'])) {
            if (is_array($filter['id_not_in'])) {
                $query = $query->whereNotIn('sub_brands.id', $filter['id_not_in']);
            } else {
                $query = $query->where('sub_brands.id', '!=', $filter['id_not_in']);
            }
        }

        if (isset($filter['brand_id'])) {
            if (is_array($filter['brand_id'])) {
                $query = $query->whereIn('sub_brands.brand_id', $filter['brand_id']);
            } else {
                $query = $query->where('sub_brands.brand_id', $filter['brand_id']);
            }
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('sub_brands.project_id', $filter['project_id']);
        }

        if (isset($filter['account_id'])) {
            $query = $query->where('sub_brands.account_id', $filter['account_id']);
        }

        if (isset($filter['deleted_at'])) {
            $query = $query->where('sub_brands.deleted_at', null);
        }

    }
}
