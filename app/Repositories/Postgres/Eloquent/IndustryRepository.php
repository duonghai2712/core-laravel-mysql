<?php namespace App\Repositories\Postgres\Eloquent;

use \App\Repositories\Postgres\IndustryRepositoryInterface;
use \App\Models\Postgres\Industry;
use \App\Repositories\Eloquent\SingleKeyModelRepository;

class IndustryRepository extends SingleKeyModelRepository implements IndustryRepositoryInterface
{

    public function getBlankModel()
    {
        return new Industry();
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

    public function getAllIndustryByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function deleteAllIndustryByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->delete();

        return $data;
    }

    private function filter($filter, &$query)
    {
        if (isset($filter['province_id'])) {
            if (is_array($filter['province_id'])) {
                $query = $query->whereIn('industries.province_id', $filter['province_id']);
            } else {
                $query = $query->where('industries.province_id', $filter['province_id']);
            }
        }

        if (isset($filter['district_id'])) {
            if (is_array($filter['district_id'])) {
                $query = $query->whereIn('industries.district_id', $filter['district_id']);
            } else {
                $query = $query->where('industries.district_id', $filter['district_id']);
            }
        }


        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('industries.id', $filter['id']);
            } else {
                $query = $query->where('industries.id', $filter['id']);
            }
        }

        if (isset($filter['deleted_at'])) {
            $query = $query->where('industries.deleted_at', null);
        }

    }

}
