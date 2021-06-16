<?php

namespace App\Presenters\Postgres\Store;
use App\Models\Postgres\Store\Collection;
use App\Presenters\BasePresenter;

use Illuminate\Support\Facades\Redis;

class StoreAccountPresenter extends BasePresenter
{
    /**
     * @return \App\Models\Postgres\Store\Collection
     * */
    public function profileStoreImage()
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel('CollectionModel');
            $cached = Redis::hget($cacheKey, $this->entity->profile_collection_id);

            if( $cached ) {
                $image = new Collection(json_decode($cached, true));
                $image['id'] = json_decode($cached, true)['id'];
                return $image;
            } else {
                $image = $this->entity->profileStoreImage;
                Redis::hsetnx($cacheKey, $this->entity->profile_collection_id, $image);
                return $image;
            }
        }

        $image = $this->entity->profileStoreImage;
        return $image;
    }
}
