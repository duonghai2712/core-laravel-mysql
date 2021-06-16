<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use \App\Repositories\Postgres\Admin\BranchRepositoryInterface;
use \App\Models\Postgres\Admin\Branch;
use \App\Repositories\Eloquent\SingleKeyModelRepository;

class BranchRepository extends SingleKeyModelRepository implements BranchRepositoryInterface
{

    public function getBlankModel()
    {
        return new Branch();
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

    public function getOneArrayBranchWithBrandByFilter($filter)
    {
        $query = $this->withBrand();

        $this->filter($filter, $query);

        $dataX = $query->first();
        $data = [];

        if (!empty($dataX)){
            $data = $dataX->toArray();
        }

        return $data;
    }

    public function getOneArrayBranchByFilter($filter)
    {
        $query = $this->withRank();

        $this->filter($filter, $query);

        $dataX = $query->first();
        $data = [];

        if (!empty($dataX)){
            $data = $dataX->toArray();
        }

        return $data;
    }

    private function withRank()
    {
        $query = $this->getBlankModel()
            ->with(['rank' => function($query){
                $query->select('ranks.id', 'ranks.name', 'ranks.coefficient');
            }]);
        return $query;
    }

    public function getAllBranchForStoreByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->select(['id', 'name', 'store_id'])->get()->toArray();


        return $data;
    }

    public function getAllBranchByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->get()->toArray();


        return $data;
    }

    public function getOneObjectBranchByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->first();

        return $data;
    }

    public function changePointBranchByFilter($filter, $params)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $this->changePoint($params,$query);

        return true;
    }

    public function deleteAllBranchByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->delete();

        return $data;
    }

    public function getListBranchByFilter($limit, $filter)
    {
        $query = $this->withBrandFilter($filter);
        $this->filter($filter, $query);

        $data = $query->paginate($limit)->toArray();

        return $data;
    }

    private function withBrandFilter($filter)
    {
        $query = $this->getBlankModel()
            ->select([
                "id",
                "name",
                "contact",
                "phone_number",
                "address",
                "store_id",
                "rank_id",
                "account_id",
                "district_id",
                "province_id",
                "total_point",
                "current_point",
                "project_id",
                "created_at",
            ])
            ->with(['account' => function($query){
                $query->leftJoin('images', 'images.id', '=', 'accounts.profile_image_id')
                    ->select('accounts.id', 'accounts.name', 'accounts.username', 'accounts.email', 'images.source', 'images.source_thumb');
            }])
            ->with(['province' => function($query){
                $query->select('provinces.id', 'provinces.name');
            }])
            ->with(['district' => function($query){
                $query->select('districts.id', 'districts.name');
            }])
            ->with(['rank' => function($query){
                $query->select('ranks.id', 'ranks.name', 'ranks.coefficient');
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

    private function withBrand()
    {
        $query = $this->getBlankModel()
            ->select([
                "id",
                "name",
                "contact",
                "phone_number",
                "address",
                "store_id",
                "rank_id",
                "account_id",
                "district_id",
                "province_id",
                "total_point",
                "current_point",
                "project_id",
                "created_at",
            ])
            ->with(['account' => function($query){
                $query->leftJoin('images', 'images.id', '=', 'accounts.profile_image_id')
                    ->select('accounts.id', 'accounts.name', 'accounts.username', 'accounts.email', 'images.source', 'images.source_thumb');
            }])
            ->with(['province' => function($query){
                $query->select('provinces.id', 'provinces.name');
            }])
            ->with(['district' => function($query){
                $query->select('districts.id', 'districts.name');
            }])
            ->with(['rank' => function($query){
                $query->select('ranks.id', 'ranks.name', 'ranks.coefficient');
            }])
            ->with(['brands' => function($query){
                $query->select('brands.id', 'brands.name', 'brands.description');
            }])
            ->with(['subBrands' => function($query){
                $query->select('sub_brands.id', 'sub_brands.name', 'sub_brands.brand_id');
            }]);
        return $query;
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
                $query = $query->whereIn('branches.id', $filter['id']);
            } else {
                $query = $query->where('branches.id', $filter['id']);
            }
        }

        if (isset($filter['isDelete'])) {
            $query = $query->where(function ($query){
                $query->orWhere('branches.deleted_at', null);
                $query->orWhere('branches.deleted_at', '!=', null);
            });
        }

        if (isset($filter['id_not_in'])) {
            if (is_array($filter['id_not_in'])) {
                $query = $query->whereNotIn('branches.id', $filter['id_not_in']);
            } else {
                $query = $query->where('branches.id', '!=', $filter['id_not_in']);
            }
        }

        if (isset($filter['store_account_id'])) {
            if (is_array($filter['store_account_id'])) {
                $query = $query->whereIn('branches.store_account_id', $filter['store_account_id']);
            } else {
                $query = $query->where('branches.store_account_id', $filter['store_account_id']);
            }
        }

        if (isset($filter['store_id'])) {
            if (is_array($filter['store_id'])) {
                $query = $query->whereIn('branches.store_id', $filter['store_id']);
            } else {
                $query = $query->where('branches.store_id', $filter['store_id']);
            }
        }

        if (isset($filter['store_id_not_in'])) {
            if (is_array($filter['store_id_not_in'])) {
                $query = $query->whereNotIn('branches.store_id', $filter['store_id_not_in']);
            } else {
                $query = $query->where('branches.store_id', '!=', $filter['store_id_not_in']);
            }
        }

        if (isset($filter['rank_id'])) {
            if (is_array($filter['rank_id'])) {
                $query = $query->whereIn('branches.rank_id', $filter['rank_id']);
            } else {
                $query = $query->where('branches.rank_id', $filter['rank_id']);
            }
        }

        if (isset($filter['deleted_at'])) {
            $query = $query->where('branches.deleted_at', null);
        }

        if (isset($filter['direction']) && isset($filter['order'])) {
            $query = $query->orderBy('branches.' . $filter['order'], $filter['direction']);
        }

        if (isset($filter['province_id'])) {
            if (is_array($filter['province_id'])) {
                $query = $query->whereIn('branches.province_id', $filter['province_id']);
            } else {
                $query = $query->where('branches.province_id', $filter['province_id']);
            }
        }

        if (isset($filter['district_id'])) {
            if (is_array($filter['district_id'])) {
                $query = $query->whereIn('branches.district_id', $filter['district_id']);
            } else {
                $query = $query->where('branches.district_id', $filter['district_id']);
            }
        }

        if (isset($filter['account_id'])) {
            $query = $query->where('branches.account_id', $filter['account_id']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('branches.project_id', $filter['project_id']);
        }

    }
}
