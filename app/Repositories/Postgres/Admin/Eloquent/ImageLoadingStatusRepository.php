<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Admin\ImageLoadingStatusRepositoryInterface;
use \App\Models\Postgres\Admin\ImageLoadingStatus;

class ImageLoadingStatusRepository extends SingleKeyModelRepository implements ImageLoadingStatusRepositoryInterface
{

    public function getBlankModel()
    {
        return new ImageLoadingStatus();
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
