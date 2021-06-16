<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use App\Models\Postgres\Store\StoreAccount;
use \App\Repositories\Postgres\Admin\StoreRepositoryInterface;
use \App\Models\Postgres\Admin\Store;
use \App\Repositories\Eloquent\AuthenticationRepository;

class StoreRepository extends AuthenticationRepository implements StoreRepositoryInterface
{

    public function getBlankModel()
    {
        return new Store();
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

    private function withAccount()
    {
        $query = $this->getBlankModel()
            ->with(['account' => function($query){
                $query->Where('store_accounts.role', StoreAccount::ADMIN)->Where('store_accounts.group_store_account_id', null)
                    ->leftJoin('collections', 'collections.id', '=', 'store_accounts.profile_collection_id')
                    ->select('store_accounts.id', 'store_accounts.store_id', 'store_accounts.representative', 'store_accounts.role', 'store_accounts.username', 'store_accounts.phone_number', 'store_accounts.email', 'store_accounts.profile_collection_id', 'collections.source', 'collections.source_thumb');
            }])
            ->with(['createdBy' => function($query){
                $query->leftJoin('images', 'images.id', '=', 'accounts.profile_image_id')
                    ->select('accounts.id', 'accounts.name', 'accounts.username', 'accounts.email', 'images.source', 'images.source_thumb');
            }])
            ->with(['province' => function($query){
                $query->select('provinces.id', 'provinces.name');
            }])
            ->with(['district' => function($query){
                $query->select('districts.id', 'districts.name');
            }])
            ->with(['brands' => function($query){
                $query->select('brands.id', 'brands.name', 'brands.description');
            }])
            ->with(['subBrands' => function($query){
                $query->select('sub_brands.id', 'sub_brands.name', 'sub_brands.brand_id');
            }]);
        return $query;
    }

    public function getOneArrayStoreByFilter($filter)
    {
        $query = $this->withAccount();
        $this->filter($filter, $query);

        $dataX = $query->first();
        $data = [];

        if (!empty($dataX)){
            $data = $dataX->toArray();
        }

        return $data;
    }

    public function getOneArrayStoreWithBrandAndSubBrandByFilter($filter)
    {
        $query = $this->withAccount();
        $this->filter($filter, $query);

        $dataX = $query->first();
        $data = [];

        if (!empty($dataX)){
            $data = $dataX->toArray();
        }

        return $data;
    }

    public function getOneArrayOnlyStoreByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $dataX = $query->first();
        $data = [];

        if (!empty($dataX)){
            $data = $dataX->toArray();
        }

        return $data;
    }

    public function getOneArrayOnlyStoreWithAdminAccountByFilter($filter)
    {
        $query = $this->withAdminAccount();
        $this->filter($filter, $query);

        $dataX = $query->first();
        $data = [];

        if (!empty($dataX)){
            $data = $dataX->toArray();
        }

        return $data;
    }

    public function deleteAllStoreByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $data = $query->delete();

        return $data;
    }

    public function getAllStoreByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function getAllStoreWithBrandByFilter($filter)
    {
        $query = $this->getBlankModel()->select('id')
            ->with(['brands' => function($query) use ($filter) {
                if (isset($filter['brand']['key_word'])) {
                    $query->select('brands.id', 'brands.name', 'brands.description')->Where('brands.name', 'like', '%' . $filter['brand']['key_word'] . '%');
                }else{
                    $query->select('brands.id', 'brands.name', 'brands.description');
                }
            }])
            ->with(['subBrands' => function($query){
                $query->select('sub_brands.id', 'sub_brands.name', 'sub_brands.brand_id');
            }]);

        $this->filter($filter, $query);
        $dataX = $query->first();
        $data = [];

        if (!empty($dataX)){
            $data = $dataX->toArray();
        }

        return $data;
    }

    public function getOneObjectStoreByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $data = $query->first();

        return $data;
    }

    public function getListStoreByFilter($limit, $filter)
    {
        $query = $this->withAllRelationship($filter);
        $this->filter($filter, $query);
        $data = $query->paginate($limit)->toArray();

        return $data;
    }

    private function withAdminAccount()
    {
        $query = $this->getBlankModel()->select([
            "id",
            "name",
            "address",
            "is_active",
            "current_point",
            "total_point",
            "project_id",
            "account_id",
            "district_id",
            "province_id",
        ])
            ->with(['account' => function($query){
                $query->Where('store_accounts.role', StoreAccount::ADMIN)->Where('store_accounts.group_store_account_id', null)
                    ->select('store_accounts.id', 'store_accounts.store_id', 'store_accounts.representative', 'store_accounts.role', 'store_accounts.phone_number', 'store_accounts.email');
            }]);
        return $query;
    }

    private function withAllRelationship($filter)
    {
        $query = $this->getBlankModel()->select([
            "id",
            "name",
            "address",
            "is_active",
            "current_point",
            "total_point",
            "project_id",
            "account_id",
            "district_id",
            "province_id",
        ])
            ->with(['account' => function($query){
                $query->Where('store_accounts.role', StoreAccount::ADMIN)->Where('store_accounts.group_store_account_id', null)
                    ->leftJoin('collections', 'collections.id', '=', 'store_accounts.profile_collection_id')
                    ->select('store_accounts.id', 'store_accounts.store_id', 'store_accounts.representative', 'store_accounts.role', 'store_accounts.username', 'store_accounts.phone_number', 'store_accounts.email', 'store_accounts.profile_collection_id', 'collections.source', 'collections.source_thumb');
            }])
            ->with(['createdBy' => function($query){
                $query->leftJoin('images', 'images.id', '=', 'accounts.profile_image_id')
                    ->select('accounts.id', 'accounts.name', 'accounts.username', 'accounts.email', 'images.source', 'images.source_thumb');
            }])
            ->with(['province' => function($query){
                $query->select('provinces.id', 'provinces.name');
            }])
            ->with(['district' => function($query){
                $query->select('districts.id', 'districts.name');
            }])
            ->with(['brands' => function($query){
                $query->select('brands.id', 'brands.name', 'brands.description');
            }])
            ->with(['subBrands' => function($query){
                $query->select('sub_brands.id', 'sub_brands.name', 'sub_brands.brand_id');
            }]);

        if (isset($filter['brand_id'])){
            $brandId = $filter['brand_id'];
            $query = $query->whereHas('brands', function($query) use ($brandId){
                $query->select('brands.id', 'brands.name', 'brands.description')->where('brands.id', $brandId);
            });
        }

        if (isset($filter['sub_brand_id'])){
            $subBrandIds = $filter['sub_brand_id'];
            $query = $query->whereHas('subBrands', function($query) use($subBrandIds){
                $query->select('sub_brands.id', 'sub_brands.name', 'sub_brands.brand_id')->whereIn('sub_brands.id', $subBrandIds);
            });
        }


        return $query;
    }

    public function changePointStoreByFilter($filter, $params)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $this->changePoint($params,$query);

        return true;
    }

    public function getOneArrayStoreWithAccountByFilter($filter)
    {
        $query = $this->withAccount();
        $this->filter($filter, $query);
        $arrFields = [
            "id",
            "name",
            "address",
            "is_active",
            "project_id",
            "account_id",
            "district_id",
            "province_id",
        ];

        $dataX = $query->select($arrFields)->first();
        $data = [];

        if (!empty($dataX)){
            $data = $dataX->toArray();
        }

        return $data;
    }


    private function changePoint($params, &$query)
    {
        if (isset($params['current_point'])){
            if (isset($params['current_point']['increment'])){
                $query->increment('current_point', $params['current_point']['increment']);
            }

            if (isset($params['current_point']['decrement'])){
                $query->decrement('current_point', $params['current_point']['decrement']);
            }
        }

        if (isset($params['total_point'])){
            if (isset($params['total_point']['increment'])){
                $query->increment('total_point', $params['total_point']['increment']);
            }

            if (isset($params['total_point']['decrement'])){
                $query->decrement('total_point', $params['total_point']['decrement']);
            }
        }
    }

    private function filter($filter, &$query)
    {

        if (isset($filter['key_word'])) {
            $query = $query->search($filter['key_word']);
        }

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('stores.id', $filter['id']);
            } else {
                $query = $query->where('stores.id', $filter['id']);
            }
        }

                if (isset($filter['id_not_in'])) {
                    if (is_array($filter['id_not_in'])) {
                        $query = $query->whereNotIn('stores.id', $filter['id_not_in']);
                    } else {
                        $query = $query->where('stores.id', '!=', $filter['id_not_in']);
                    }
                }

        if (isset($filter['province_id'])) {
            if (is_array($filter['province_id'])) {
                $query = $query->whereIn('stores.province_id', $filter['province_id']);
            } else {
                $query = $query->where('stores.province_id', $filter['province_id']);
            }
        }

        if (isset($filter['isDelete'])) {
            $query = $query->where(function ($query){
                $query->orWhere('stores.deleted_at', null);
                $query->orWhere('stores.deleted_at', '!=', null);
            });
        }

        if (isset($filter['district_id'])) {
            if (is_array($filter['district_id'])) {
                $query = $query->whereIn('stores.district_id', $filter['district_id']);
            } else {
                $query = $query->where('stores.district_id', $filter['district_id']);
            }
        }

        if (isset($filter['deleted_at'])) {
            $query = $query->where('stores.deleted_at', null);
        }

        if (isset($filter['direction']) && isset($filter['order'])) {
            $query = $query->orderBy('stores.' . $filter['order'], $filter['direction']);
        }


        if (isset($filter['account_id'])) {
            $query = $query->where('stores.account_id', $filter['account_id']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('stores.project_id', $filter['project_id']);
        }

    }

}
