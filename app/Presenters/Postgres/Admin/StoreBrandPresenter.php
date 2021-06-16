<?php

namespace App\Presenters\Postgres\Admin;

use App\Presenters\BasePresenter;
use Illuminate\Support\Facades\Redis;
use App\Models\Postgres\Admin\Project;
use App\Models\Postgres\Admin\Account;
use App\Models\Postgres\Admin\Brand;
use App\Models\Postgres\Admin\Store;
use App\Models\Postgres\Admin\SubBrand;

class StoreBrandPresenter extends BasePresenter
{
    protected $multilingualFields = [];

    protected $imageFields = [];

    /**
    * @return \App\Models\Postgres\Admin\Project
    * */
    public function project()
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel('ProjectModel');
            $cached = Redis::hget($cacheKey, $this->entity->project_id);

            if( $cached ) {
                $project = new Project(json_decode($cached, true));
                $project['id'] = json_decode($cached, true)['id'];
                return $project;
            } else {
                $project = $this->entity->project;
                Redis::hsetnx($cacheKey, $this->entity->project_id, $project);
                return $project;
            }
        }

        $project = $this->entity->project;
        return $project;
    }

    /**
    * @return \App\Models\Postgres\Admin\Account
    * */
    public function account()
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel('AccountModel');
            $cached = Redis::hget($cacheKey, $this->entity->account_id);

            if( $cached ) {
                $account = new Account(json_decode($cached, true));
                $account['id'] = json_decode($cached, true)['id'];
                return $account;
            } else {
                $account = $this->entity->account;
                Redis::hsetnx($cacheKey, $this->entity->account_id, $account);
                return $account;
            }
        }

        $account = $this->entity->account;
        return $account;
    }

    /**
    * @return \App\Models\Postgres\Admin\Brand
    * */
    public function brand()
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel('BrandModel');
            $cached = Redis::hget($cacheKey, $this->entity->brand_id);

            if( $cached ) {
                $brand = new Brand(json_decode($cached, true));
                $brand['id'] = json_decode($cached, true)['id'];
                return $brand;
            } else {
                $brand = $this->entity->brand;
                Redis::hsetnx($cacheKey, $this->entity->brand_id, $brand);
                return $brand;
            }
        }

        $brand = $this->entity->brand;
        return $brand;
    }

    /**
    * @return \App\Models\Postgres\Admin\Store
    * */
    public function store()
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel('StoreModel');
            $cached = Redis::hget($cacheKey, $this->entity->store_id);

            if( $cached ) {
                $store = new Store(json_decode($cached, true));
                $store['id'] = json_decode($cached, true)['id'];
                return $store;
            } else {
                $store = $this->entity->store;
                Redis::hsetnx($cacheKey, $this->entity->store_id, $store);
                return $store;
            }
        }

        $store = $this->entity->store;
        return $store;
    }

    /**
    * @return \App\Models\Postgres\Admin\SubBrand
    * */
    public function subBrand()
    {
        if( \CacheHelper::cacheRedisEnabled() ) {
            $cacheKey = \CacheHelper::keyForModel('SubBrandModel');
            $cached = Redis::hget($cacheKey, $this->entity->sub_brand_id);

            if( $cached ) {
                $subBrand = new SubBrand(json_decode($cached, true));
                $subBrand['id'] = json_decode($cached, true)['id'];
                return $subBrand;
            } else {
                $subBrand = $this->entity->subBrand;
                Redis::hsetnx($cacheKey, $this->entity->sub_brand_id, $subBrand);
                return $subBrand;
            }
        }

        $subBrand = $this->entity->subBrand;
        return $subBrand;
    }


}
