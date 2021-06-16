<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Store\CollectionCrossLoadingStatusRepositoryInterface;
use \App\Models\Postgres\Store\CollectionCrossLoadingStatus;

class CollectionCrossLoadingStatusRepository extends SingleKeyModelRepository implements CollectionCrossLoadingStatusRepositoryInterface
{

    public function getBlankModel()
    {
        return new CollectionCrossLoadingStatus();
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
