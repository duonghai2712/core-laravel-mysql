<?php namespace App\Repositories\Postgres\Eloquent;

use \App\Repositories\Postgres\DistrictRepositoryInterface;
use \App\Models\Postgres\District;
use \App\Repositories\Eloquent\SingleKeyModelRepository;

class DistrictRepository extends SingleKeyModelRepository implements DistrictRepositoryInterface
{

    public function getBlankModel()
    {
        return new District();
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

    public function getAllDistrictByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function deleteAllDistrictByFilter($filter)
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
                $query = $query->whereIn('districts.province_id', $filter['province_id']);
            } else {
                $query = $query->where('districts.province_id', $filter['province_id']);
            }
        }

        if (isset($filter['key_word'])) {
            $query = $query->search($filter['key_word']);
        }

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('districts.id', $filter['id']);
            } else {
                $query = $query->where('districts.id', $filter['id']);
            }
        }

        if (isset($filter['deleted_at'])) {
            $query = $query->where('districts.deleted_at', null);
        }


    }

}
