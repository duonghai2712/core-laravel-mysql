<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Models\Postgres\Store\OrderDevice;
use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Store\OrderBranchRepositoryInterface;
use \App\Models\Postgres\Store\OrderBranch;

class OrderBranchRepository extends SingleKeyModelRepository implements OrderBranchRepositoryInterface
{

    public function getBlankModel()
    {
        return new OrderBranch();
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

    public function insertMulti($params)
    {
        if(!empty($params) && is_array($params)){
            $insertUsers = $this->getBlankModel()->insert($params);
            if($insertUsers){
                return true;
            }
        }
        return false;
    }

    public function getAllOrderBranchByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function deleteAllOrderBranchByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $data = $query->update(['status' => OrderBranch::STATUS_DELETE, 'point' => 0]);

        return $data;
    }

    public function getAllOrderBranchWithBranchByFilter($filter)
    {
        $query = $this->withBranches();
        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function getAllOrderBranchWithBranchAndStoreAccountByFilter($filter)
    {
        $query = $this->withBranchAndStoreAccount();
        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function getAllOrderBranchWithOrderAndBranchByFilter($filter)
    {
        $query = $this->withOrderAndBranch();
        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function getListOrderBranchByFilter($limit, $filter)
    {
        $query = $this->withListBranches();
        $this->filter($filter, $query);

        $data = $query->paginate($limit)->toArray();

        return $data;
    }

    public function getListOrderBranchCrossByFilter($limit, $filter)
    {
        $query = $this->withListCrossBranches($filter);
        $this->filter($filter, $query);

        $data = $query->paginate($limit)->toArray();

        return $data;
    }

    public function getOneArrayOrderBranchByFilter($filter)
    {
        $query = $this->withBranches();
        $this->filter($filter, $query);

        $dataX = $query->first();
        $data = [];
        if (!empty($dataX)){
            $data = $dataX->toArray();
        }

        return $data;
    }

    private function withOrderAndBranch()
    {
        $query = $this->getBlankModel()->select([
            "order_branches.*",
        ])
            ->with(['branch' => function($query){
                $query->select('branches.id', 'branches.store_id')
                    ->with(['store' => function($query){
                        $query->select('stores.id', 'stores.current_point', 'stores.total_point');
                    }]);
            }])
            ->with(['order' => function($query){
                $query->select('orders.id', 'orders.store_id', 'orders.code', 'orders.payment', 'orders.is_added_point');
            }]);

        return $query;
    }

    private function withBranchAndStoreAccount()
    {
        $query = $this->getBlankModel()->select([
            "order_branches.*",
        ])
            ->with(['branch' => function($query){
                $query->select('branches.id', 'branches.store_id')
                    ->with(['store' => function($query){
                        $query->select('stores.id', 'stores.current_point', 'stores.total_point');
                    }]);
            }]);

        return $query;
    }

    private function withListBranches()
    {
        $query = $this->getBlankModel()->select([
            "order_branches.*",
        ])
            ->with(['branch' => function($query){
                $query->select('branches.id', 'branches.name', 'branches.province_id', 'branches.district_id', 'branches.address')
                    ->with(['province' => function($query){
                        $query->select('provinces.id', 'provinces.name');
                    }])
                    ->with(['district' => function($query){
                        $query->select('districts.id', 'districts.name');
                    }]);
            }])
            ->withCount(['devices' => function ($query) {
                $query->where('order_devices.status', OrderDevice::STATUS_USING);
            }]);

        return $query;
    }

    private function withListCrossBranches($filter)
    {
        $query = $this->getBlankModel()->select([
            "order_branches.*",
        ])
            ->with(['branch' => function($query){
                $query->select('branches.id', 'branches.name', 'branches.province_id', 'branches.district_id', 'branches.address')
                    ->with(['province' => function($query){
                        $query->select('provinces.id', 'provinces.name');
                    }])
                    ->with(['district' => function($query){
                        $query->select('districts.id', 'districts.name');
                    }]);
            }])
            ->withCount(['devices' => function ($query) {
                $query->where('order_devices.status', OrderDevice::STATUS_USING);
            }]);

        if (isset($filter['store_cross_id']) && isset($filter['order_id'])){
            $storeId = $filter['store_cross_id'];
            $orderId = $filter['order_id'];
            $query = $query->whereHas('orderStore', function($query) use ($storeId, $orderId){
                $query->select('order_stores.id', 'order_stores.order_id', 'order_stores.store_id')->where('order_stores.store_id', $storeId)->where('order_stores.order_id', $orderId);
            });
        }

        return $query;
    }

    private function withBranches()
    {
        $query = $this->getBlankModel()->select([
            "order_branches.*",
        ])
            ->with(['branch' => function($query){
                $query->select('branches.id', 'branches.name', 'branches.address', 'branches.make_ads', 'branches.store_account_id');
            }])

            ->with(['rank' => function($query){
                $query->select('ranks.id', 'ranks.name', 'ranks.coefficient');
            }])
            ->withCount(['devices' => function ($query) {
                $query->where('order_devices.status', OrderDevice::STATUS_USING);
            }]);

        return $query;
    }

    private function filter($filter, &$query)
    {

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('order_branches.id', $filter['id']);
            } else {
                $query = $query->where('order_branches.id', $filter['id']);
            }
        }

        if (isset($filter['branch_id'])) {
            if (is_array($filter['branch_id'])) {
                $query = $query->whereIn('order_branches.branch_id', $filter['branch_id']);
            } else {
                $query = $query->where('order_branches.branch_id', $filter['branch_id']);
            }
        }

        if (isset($filter['order_store_id'])) {
            if (is_array($filter['order_store_id'])) {
                $query = $query->whereIn('order_branches.order_store_id', $filter['order_store_id']);
            } else {
                $query = $query->where('order_branches.order_store_id', $filter['order_store_id']);
            }
        }

        if (isset($filter['order_id'])) {
            if (is_array($filter['order_id'])) {
                $query = $query->whereIn('order_branches.order_id', $filter['order_id']);
            } else {
                $query = $query->where('order_branches.order_id', $filter['order_id']);
            }
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('order_branches.project_id', $filter['project_id']);
        }

        if (isset($filter['status'])) {
            if (is_array($filter['status'])) {
                $query = $query->whereIn('order_branches.status', $filter['status']);
            } else {
                $query = $query->where('order_branches.status', $filter['status']);
            }
        }

    }
}
