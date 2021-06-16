<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Store\CollectionLoadingStatusRepositoryInterface;
use \App\Models\Postgres\Store\CollectionLoadingStatus;

class CollectionLoadingStatusRepository extends SingleKeyModelRepository implements CollectionLoadingStatusRepositoryInterface
{

    public function getBlankModel()
    {
        return new CollectionLoadingStatus();
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
