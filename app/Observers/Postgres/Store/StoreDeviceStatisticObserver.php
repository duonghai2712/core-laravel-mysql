<?php namespace App\Observers\Postgres\Store;

use Illuminate\Support\Facades\Redis;
use App\Observers\BaseObserver;

class StoreDeviceStatisticObserver extends BaseObserver
{
    protected $cachePrefix = 'StoreDeviceStatisticModel';

    public function created($model)
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel($this->cachePrefix);
            Redis::hsetnx($cacheKey, $model->id, $model);
        }
    }

    public function updated($model)
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel($this->cachePrefix);
            Redis::hset($cacheKey, $model->id, $model);
        }
    }

    public function deleted($model)
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel($this->cachePrefix);
            Redis::hdel($cacheKey, $model->id);
        }
    }
}
