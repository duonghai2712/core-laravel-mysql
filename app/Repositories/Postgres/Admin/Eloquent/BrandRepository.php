<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use \App\Repositories\Postgres\Admin\BrandRepositoryInterface;
use \App\Models\Postgres\Admin\Brand;
use \App\Repositories\Eloquent\SingleKeyModelRepository;

class BrandRepository extends SingleKeyModelRepository implements BrandRepositoryInterface
{

    public function getBlankModel()
    {
        return new Brand();
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

    public function getOneArrayBrandWithSubBrandByFilter($filter)
    {
        $query = $this->withSubBrand();

        $this->filter($filter, $query);

        $dataX = $query->first();
        $data = [];

        if (!empty($dataX)){
            $data = $dataX->toArray();
        }

        return $data;
    }

    public function getOneObjectBrandByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->first();

        return $data;
    }

    public function getAllBrandByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function countAllBrandByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->count();

        return $data;
    }

    public function getAllBrandWithHasSubBrandByFilter($filter)
    {
        $query = $this->withHasSubBrand();

        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    private function withHasSubBrand()
    {
        $query = $this->getBlankModel()
            ->has('subBrands')
            ->with(['subBrands' => function($query){
                $query->select('sub_brands.*');
            }]);
        return $query;
    }

    private function withSubBrand()
    {
        $query = $this->getBlankModel()
            ->with(['subBrands' => function($query){
                $query->select('sub_brands.*');
            }]);
        return $query;
    }

    public function deleteAllBrandByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->delete();

        return $data;
    }

    public function getListBrandByFilter($limit, $filter)
    {
        $query = $this->withSubBrand();
        $this->filter($filter, $query);

        $data = $query->paginate($limit)->toArray();

        return $data;
    }


    private function filter($filter, &$query)
    {
        if (isset($filter['key_word'])) {
            $query = $query->search($filter['key_word']);
        }

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('brands.id', $filter['id']);
            } else {
                $query = $query->where('brands.id', $filter['id']);
            }
        }

        if (isset($filter['brand_not_in_id'])) {
            if (is_array($filter['brand_not_in_id'])) {
                $query = $query->whereNotIn('brands.id', $filter['brand_not_in_id']);
            } else {
                $query = $query->where('brands.id', '!=', $filter['brand_not_in_id']);
            }
        }

        if (isset($filter['deleted_at'])) {
            $query = $query->where('brands.deleted_at', null);
        }

        if (isset($filter['direction']) && isset($filter['order'])) {
            $query = $query->orderBy('brands.' . $filter['order'], $filter['direction']);
        }


        if (isset($filter['account_id'])) {
            $query = $query->where('brands.account_id', $filter['account_id']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('brands.project_id', $filter['project_id']);
        }

    }
}
