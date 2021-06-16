<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use \App\Repositories\Postgres\Admin\BranchSubBrandRepositoryInterface;
use \App\Models\Postgres\Admin\BranchSubBrand;
use \App\Repositories\Eloquent\SingleKeyModelRepository;

class BranchSubBrandRepository extends SingleKeyModelRepository implements BranchSubBrandRepositoryInterface
{

    public function getBlankModel()
    {
        return new BranchSubBrand();
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

    public function createMultiRecord($params)
    {
        if(!empty($params) && is_array($params)){
            $insertUsers = $this->getBlankModel()->insert($params);
            if($insertUsers){
                return true;
            }
        }
        return false;
    }

    public function getAllBranchSubBrandByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function getAllBranchSubBrandWithSubBrandByFilter($filter)
    {
        $query = $this->getBlankModel()
            ->leftJoin('sub_brands', 'branch_sub_brands.sub_brand_id', '=', 'sub_brands.id');

        $this->filter($filter, $query);

        $data = $query->select([
            'sub_brands.id',
            'sub_brands.name',
            'sub_brands.slug',
            'branch_sub_brands.account_id',
        ])->get()->toArray();

        return $data;
    }

    public function getAllBranchSubBrandByFilterOrder($filter)
    {
        $query = $this->getBlankModel();

        $this->filterOrder($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function getAllBranchSubBrandWithOnlyBranchIdByFilter($filter)
    {
        $query = $this->getBlankModel()->select(['id', 'branch_id']);

        $this->filterOrder($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function deleteAllBranchSubBrandByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->delete();

        return $data;
    }

    private function filterOrder($filter, &$query)
    {

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('branch_sub_brands.id', $filter['id']);
            } else {
                $query = $query->where('branch_sub_brands.id', $filter['id']);
            }
        }

        if (isset($filter['branch_id'])) {
            if (is_array($filter['branch_id'])) {
                $query = $query->whereIn('branch_sub_brands.branch_id', $filter['branch_id']);
            } else {
                $query = $query->where('branch_sub_brands.branch_id', $filter['branch_id']);
            }
        }

        if (isset($filter['sub_brand_id'])) {
            if (is_array($filter['sub_brand_id'])) {
                $sub_brand = $filter['sub_brand_id'];
                $query = $query->where(function ($query) use ($sub_brand) {
                    if (is_array($sub_brand)){
                        foreach ($sub_brand as $id){
                            $query->orWhere('branch_sub_brands.sub_brand_id', $id);
                        }
                    }
                });
            } else {
                $query = $query->where('branch_sub_brands.sub_brand_id', $filter['sub_brand_id']);
            }
        }


        if (isset($filter['account_id'])) {
            $query = $query->where('branch_sub_brands.account_id', $filter['account_id']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('branch_sub_brands.project_id', $filter['project_id']);
        }

    }

    private function filter($filter, &$query)
    {

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('branch_sub_brands.id', $filter['id']);
            } else {
                $query = $query->where('branch_sub_brands.id', $filter['id']);
            }
        }

        if (isset($filter['branch_id'])) {
            if (is_array($filter['branch_id'])) {
                $query = $query->whereIn('branch_sub_brands.branch_id', $filter['branch_id']);
            } else {
                $query = $query->where('branch_sub_brands.branch_id', $filter['branch_id']);
            }
        }

        if (isset($filter['sub_brand_id'])) {
            if (is_array($filter['sub_brand_id'])) {
                $query = $query->whereIn('branch_sub_brands.sub_brand_id', $filter['sub_brand_id']);
            } else {
                $query = $query->where('branch_sub_brands.sub_brand_id', $filter['sub_brand_id']);
            }
        }


        if (isset($filter['account_id'])) {
            $query = $query->where('branch_sub_brands.account_id', $filter['account_id']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('branch_sub_brands.project_id', $filter['project_id']);
        }

    }

}
