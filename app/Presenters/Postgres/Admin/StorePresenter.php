<?php

namespace App\Presenters\Postgres\Admin;

use App\Models\Postgres\District;
use App\Models\Postgres\Admin\Image;
use App\Models\Postgres\Province;
use App\Presenters\BasePresenter;
use Illuminate\Support\Facades\Redis;

class StorePresenter extends BasePresenter
{

    /**
     * @return \App\Models\Postgres\Province
     * */
    public function Province()
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel('ProvinceModel');
            $cached = Redis::hget($cacheKey, $this->entity->province_id);

            if( $cached ) {
                $province = new Province(json_decode($cached, true));
                $province['id'] = json_decode($cached, true)['id'];
                return $province;
            } else {
                $province = $this->entity->province;
                Redis::hsetnx($cacheKey, $this->entity->province_id, $province);
                return $province;
            }
        }

        $province = $this->entity->province;
        return $province;
    }

    /**
     * @return \App\Models\Postgres\District
     * */
    public function District()
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel('DistrictModel');
            $cached = Redis::hget($cacheKey, $this->entity->district_id);

            if ($cached) {
                $district = new District(json_decode($cached, true));
                $district['id'] = json_decode($cached, true)['id'];
                return $district;
            } else {
                $district = $this->entity->district;
                Redis::hsetnx($cacheKey, $this->entity->district_id, $district);
                return $district;
            }
        }

        $district = $this->entity->district;
        return $district;
    }


}
