<?php namespace App\Repositories\Postgres\Eloquent;

use \App\Repositories\Postgres\ProvinceRepositoryInterface;
use \App\Models\Postgres\Province;
use \App\Repositories\Eloquent\SingleKeyModelRepository;

class ProvinceRepository extends SingleKeyModelRepository implements ProvinceRepositoryInterface
{

    public function getBlankModel()
    {
        return new Province();
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

    public function getAllProvinceByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function deleteAllProvinceByFilter($filter)
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
                $query = $query->whereIn('provinces.id', $filter['id']);
            } else {
                $query = $query->where('provinces.id', $filter['id']);
            }
        }

        if (isset($filter['key_word'])) {
            $query = $query->search($filter['key_word']);
        }

        if (isset($filter['deleted_at'])) {
            $query = $query->where('provinces.deleted_at', null);
        }

    }

}
