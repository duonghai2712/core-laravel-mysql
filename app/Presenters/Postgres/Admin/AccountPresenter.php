<?php

namespace App\Presenters\Postgres\Admin;

use App\Models\Postgres\Admin\Image;
use App\Presenters\BasePresenter;
use Illuminate\Support\Facades\Redis;

class AccountPresenter extends BasePresenter
{
    /**
     * @return \App\Models\Postgres\Admin\Image
     * */
    public function profileImage()
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel('ImageModel');
            $cached = Redis::hget($cacheKey, $this->entity->profile_image_id);

            if( $cached ) {
                $image = new Image(json_decode($cached, true));
                $image['id'] = json_decode($cached, true)['id'];
                return $image;
            } else {
                $image = $this->entity->profileImage;
                Redis::hsetnx($cacheKey, $this->entity->profile_image_id, $image);
                return $image;
            }
        }

        $image = $this->entity->profileImage;
        return $image;
    }

}
