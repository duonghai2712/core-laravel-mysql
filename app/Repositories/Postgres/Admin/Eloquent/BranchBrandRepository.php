<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use \App\Repositories\Postgres\Admin\BranchBrandRepositoryInterface;
use \App\Models\Postgres\Admin\BranchBrand;
use \App\Repositories\Eloquent\SingleKeyModelRepository;

class BranchBrandRepository extends SingleKeyModelRepository implements BranchBrandRepositoryInterface
{

    public function getBlankModel()
    {
        return new BranchBrand();
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

    public function getAllBranchBrandByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function getAllBranchBrandWithOnlyBranchIdByFilter($filter)
    {
        $query = $this->getBlankModel()->select(['id', 'branch_id']);

        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function deleteAllBranchBrandByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->delete();

        return $data;
    }


    private function filter($filter, &$query)
    {

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('branch_brands.id', $filter['id']);
            } else {
                $query = $query->where('branch_brands.id', $filter['id']);
            }
        }

        if (isset($filter['branch_id'])) {
            if (is_array($filter['branch_id'])) {
                $query = $query->whereIn('branch_brands.branch_id', $filter['branch_id']);
            } else {
                $query = $query->where('branch_brands.branch_id', $filter['branch_id']);
            }
        }

        if (isset($filter['brand_id'])) {
            if (is_array($filter['brand_id'])) {
                $query = $query->whereIn('branch_brands.brand_id', $filter['brand_id']);
            } else {
                $query = $query->where('branch_brands.brand_id', $filter['brand_id']);
            }
        }


        if (isset($filter['account_id'])) {
            $query = $query->where('branch_brands.account_id', $filter['account_id']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('branch_brands.project_id', $filter['project_id']);
        }

    }

}
