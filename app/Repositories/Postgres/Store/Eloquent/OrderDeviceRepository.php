<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Store\OrderDeviceRepositoryInterface;
use \App\Models\Postgres\Store\OrderDevice;
use DB;

class OrderDeviceRepository extends SingleKeyModelRepository implements OrderDeviceRepositoryInterface
{

    public function getBlankModel()
    {
        return new OrderDevice();
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

    public function getAllOrderDeviceByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);
        $data = $query->orderBy('created_at', 'asc')->get()->toArray();

        return $data;
    }

    public function getAllOrderDeviceWithAllByFilter($filter)
    {
        $query = $this->withAllElement();
        $this->filter($filter, $query);
        $data = $query->get()->toArray();

        return $data;
    }

    public function getAllOrderDeviceForQueueByFilter($filter)
    {
        $query = $this->withDevice();
        $this->filter($filter, $query);
        $data = $query->get()->toArray();

        return $data;
    }

    private function withAllElement()
    {
        $query = $this->getBlankModel()->select([
            "order_devices.*",
        ])
            ->with(['orderBranch' => function($query){
                $query->select('order_branches.*');
            }])
            ->with(['orderStore' => function($query){
                $query->select('order_stores.*');
            }])
            ->with(['order' => function($query){
                $query->select('orders.*')
                    ->with(['timeFrames' => function($query){
                        $query->select('time_frames.*');
                    }])
                    ->with(['storeCrossDeviceCollections' => function($query){
                        $query->select('store_cross_device_collections.*');
                    }]);
            }]);

        return $query;
    }

    private function withDevice()
    {
        $query = $this->getBlankModel()->select([
            "order_devices.*",
        ])
            ->with(['device' => function($query){
                $query->select('devices.id', 'devices.store_id', 'devices.branch_id', 'devices.status', 'devices.is_active', 'devices.device_code', 'devices.block_ads', 'devices.total_time_store', 'devices.total_time_admin')
                    ->with(['store' => function($query){
                        $query->select('stores.id', 'stores.current_point', 'stores.total_point');
                    }]);
            }])
            ->with(['orderBranch' => function($query){
                $query->select('order_branches.*');
            }])
            ->with(['order' => function($query){
                $query->select('orders.id', 'orders.code');
            }]);
        return $query;
    }

    public function getAllOrderDeviceWithOrderByFilter($filter)
    {
        $query = $this->withOrders();
        $this->filter($filter, $query);
        $data = $query->orderBy('order_devices.created_at', 'desc')->get()->toArray();

        return $data;
    }

    public function deleteAllOrderDeviceByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);
        $data = $query->update(['status' => OrderDevice::STATUS_DELETE, 'point' => 0]);

        return $data;
    }

    public function deleteAllOrderDeviceFromAdminByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);
        $data = $query->update(['status' => OrderDevice::STATUS_DELETE]);

        return $data;
    }

    public function createMulti($params)
    {
        if(!empty($params) && is_array($params)){
            $insertUsers = $this->getBlankModel()->insert($params);
            if($insertUsers){
                return true;
            }
        }
        return false;
    }

    public function getAllOrderDeviceForAppByFilter($filter)
    {
        $query = $this->forApps();
        $this->filter($filter, $query);
        $data = $query->get()->toArray();

        return $data;
    }

    public function getListOrderDeviceWithDeviceByFilter($limit, $filter)
    {
        $query = $this->withDevices();
        $this->filter($filter, $query);

        $data = $query->paginate($limit)->toArray();

        return $data;
    }

    private function withOrders()
    {
        $query = $this->getBlankModel()->select([
            "order_devices.*",

        ])->with(['order' => function($query){
                $query->select('orders.*')
                    ->with(['timeFrames' => function($query){
                        $query->select('time_frames.id', 'time_frames.start_date', 'time_frames.end_date', 'time_frames.start_time', 'time_frames.end_time', 'time_frames.order_id');
                    }]);
            }])
            ->with(['device' => function($query){
                $query->select('devices.*');
            }]);

        return $query;
    }


    private function withPlaytime()
    {
        $query = $this->getBlankModel()->select([
            "order_devices.*",
        ])
            ->with(['device' => function($query){
                $query->select('devices.id', 'devices.store_id', 'devices.branch_id')
                    ->with(['branch' => function($query){
                        $query->select('branches.id', 'branches.rank_id')
                            ->with(['rank' => function($query){
                                $query->select('ranks.id', 'ranks.coefficient');
                            }]);
                    }]);
            }])
            ->with(['order' => function($query){
                $query->select('orders.id', 'orders.time_booked')
                    ->with(['timeFrames' => function($query){
                        $query->select('time_frames.id', 'time_frames.start_date', 'time_frames.end_date', 'time_frames.start_time', 'time_frames.end_time', 'time_frames.order_id');
                    }])
                    ->with(['storeCrossDeviceCollections' => function($query){
                        $query->select('store_cross_device_collections.id', 'store_cross_device_collections.order_id', 'store_cross_device_collections.second', 'store_cross_device_collections.type');
                    }]);
            }]);
        return $query;
    }

    private function withDevices()
    {
        $query = $this->getBlankModel()->select([
            "order_devices.*",
        ])
            ->with(['device' => function($query){
                $query->select('devices.id', 'devices.name', 'devices.active_code', 'devices.device_code', 'devices.own');
            }]);
        return $query;
    }

    private function forApps()
    {
        $query = $this->getBlankModel()->select([
            "order_devices.*",
        ])
            ->with(['device' => function($query){
                $query->select('devices.id', 'devices.store_id', 'devices.branch_id', 'devices.block_ads')
                    ->with(['branch' => function($query){
                        $query->select('branches.id', 'branches.rank_id');
                    }]);
            }])
            ->with(['orderBranch' => function($query){
                $query->select('order_branches.id', 'order_branches.branch_id');
            }])
            ->with(['orderStore' => function($query){
                $query->select('order_stores.id', 'order_stores.store_id');
            }])
            ->with(['order' => function($query){
                $query->select('orders.id')
                    ->with(['timeFrames' => function($query){
                        $query->select('time_frames.id', 'time_frames.start_date', 'time_frames.end_date', 'time_frames.start_time', 'time_frames.end_time', 'time_frames.order_id');
                    }])
                    ->with(['storeCrossDeviceCollections' => function($query){
                        $query->select('store_cross_device_collections.id', 'store_cross_device_collections.collection_id', 'store_cross_device_collections.order_id', 'store_cross_device_collections.second', 'store_cross_device_collections.type')
                            ->with(['collection' => function($query){
                                $query->select('collections.id', 'collections.source', 'collections.source_thumb', 'collections.mimes');
                            }]);
                    }]);
            }]);
        return $query;
    }

    private function filter($filter, &$query)
    {

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('order_devices.id', $filter['id']);
            } else {
                $query = $query->where('order_devices.id', $filter['id']);
            }
        }

        if (isset($filter['order_id'])) {
            if (is_array($filter['order_id'])) {
                $query = $query->whereIn('order_devices.order_id', $filter['order_id']);
            } else {
                $query = $query->where('order_devices.order_id', $filter['order_id']);
            }
        }

        if (isset($filter['device_id'])) {
            if (is_array($filter['device_id'])) {
                $query = $query->whereIn('order_devices.device_id', $filter['device_id']);
            } else {
                $query = $query->where('order_devices.device_id', $filter['device_id']);
            }
        }

        if (isset($filter['order_branch_id'])) {
            if (is_array($filter['order_branch_id'])) {
                $query = $query->whereIn('order_devices.order_branch_id', $filter['order_branch_id']);
            } else {
                $query = $query->where('order_devices.order_branch_id', $filter['order_branch_id']);
            }
        }

        if (isset($filter['order_store_id'])) {
            if (is_array($filter['order_store_id'])) {
                $query = $query->whereIn('order_devices.order_store_id', $filter['order_store_id']);
            } else {
                $query = $query->where('order_devices.order_store_id', $filter['order_store_id']);
            }
        }

        if (isset($filter['store_account_id'])) {
            if (is_array($filter['store_account_id'])) {
                $query = $query->whereIn('order_devices.store_account_id', $filter['store_account_id']);
            } else {
                $query = $query->where('order_devices.store_account_id', $filter['store_account_id']);
            }
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('order_devices.project_id', $filter['project_id']);
        }

        if (isset($filter['status'])) {
            if (is_array($filter['status'])) {
                $query = $query->whereIn('order_devices.status', $filter['status']);
            } else {
                $query = $query->where('order_devices.status', $filter['status']);
            }
        }

    }
}
