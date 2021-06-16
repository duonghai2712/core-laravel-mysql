<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Admin\AdminDeviceStatisticRepositoryInterface;
use \App\Models\Postgres\Admin\AdminDeviceStatistic;
use  DB;

class AdminDeviceStatisticRepository extends SingleKeyModelRepository implements AdminDeviceStatisticRepositoryInterface
{

    public function getBlankModel()
    {
        return new AdminDeviceStatistic();
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

}
