<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Models\Postgres\Store\OrderDevice;
use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Store\OrderRepositoryInterface;
use \App\Models\Postgres\Store\Order;

class OrderRepository extends SingleKeyModelRepository implements OrderRepositoryInterface
{

    public function getBlankModel()
    {
        return new Order();
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

    public function getAllOrderByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);
        $data = $query->orderBy('created_at', 'asc')->get()->toArray();

        return $data;
    }

    public function getAllOrderWithOrderByByFilter($filter)
    {
        $query = $this->withTimeFrames();
        $this->filter($filter, $query);
        $data = $query->orderBy('created_at', 'desc')->get()->toArray();

        return $data;
    }

    public function getAllOrderWithOrderDeviceByFilter($filter)
    {
        $query = $this->withOrderDevices();
        $this->filter($filter, $query);
        $data = $query->get()->toArray();

        return $data;
    }

    public function getAllOrderWithTimeFrameByFilter($filter)
    {
        $query = $this->withTimeFrames();
        $this->filter($filter, $query);
        $data = $query->get()->toArray();

        return $data;
    }

    public function updateAllOrderByFilter($filter, $params)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);
        $data = $query->update($params);

        return $data;
    }

    public function getListOrderByFilter($limit, $filter)
    {
        $query = $this->withUsers();
        $this->filter($filter, $query);

        $data = $query->orderBy('orders.created_at', 'desc')->paginate($limit)->toArray();

        return $data;
    }

    public function getListOrderCrossByFilter($limit, $filter)
    {
        $query = $this->withCrossData($filter);
        $this->filter($filter, $query);

        $data = $query->orderBy('orders.created_at', 'desc')->paginate($limit)->toArray();

        return $data;
    }

    public function getOneArrayOrderByFilter($filter)
    {
        $query = $this->detailOrder();
        $this->filter($filter, $query);

        $dataX = $query->first();
        $data = [];
        if (!empty($dataX)){
            $data = $dataX->toArray();
        }

        return $data;
    }

    public function getOneObjectOrderByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $data = $query->first();

        return $data;
    }

    private function withTimeFrames()
    {
        $query = $this->getBlankModel()->select([
            "orders.id",
            "orders.payment",
            "orders.status",
            "orders.code",
            "orders.note",
            "orders.reason",
            "orders.store_id",
            "orders.created_at",
            "orders.store_account_id",
            "orders.type_booking",
            "orders.total_slot",
            "orders.time_booked",
            "orders.approval_time",
        ])
            ->with(['timeFrames' => function($query){
                $query->select([
                    "time_frames.id",
                    "time_frames.start_date",
                    "time_frames.end_date",
                    "time_frames.start_time",
                    "time_frames.end_time",
                    "time_frames.order_id",
                ]);
            }]);
        return $query;
    }

    private function withOrderDevices()
    {
        $query = $this->getBlankModel()->select([
            "orders.id",
            "orders.payment",
            "orders.status",
            "orders.code",
            "orders.note",
            "orders.reason",
            "orders.store_id",
            "orders.created_at",
            "orders.store_account_id",
            "orders.type_booking",
            "orders.total_slot",
            "orders.time_booked",
            "orders.approval_time",
        ])
            ->with(['devices' => function($query){
                $query->select([
                    "devices.id",
                    "devices.block_ads",
                    "devices.total_time_empty",
                    "devices.total_time_store",
                    "devices.total_time_admin",
                    "devices.store_id",
                    "devices.branch_id",
                ]);
            }]);
        return $query;
    }

    private function detailOrder()
    {
        $query = $this->getBlankModel()->select([
            "orders.id",
            "orders.payment",
            "orders.status",
            "orders.code",
            "orders.note",
            "orders.reason",
            "orders.store_id",
            "orders.created_at",
            "orders.project_id",
            "orders.store_account_id",
            "orders.type_booking",
            "orders.total_slot",
            "orders.time_booked",
            "orders.approval_time",
            "orders.created_at",
            "orders.updated_at",
        ])
            ->with(['storeAccount' => function($query){
                $query->select([
                    "store_accounts.id",
                    "store_accounts.username",
                    "store_accounts.representative",
                    "store_accounts.store_id",
                    "store_accounts.branch_id",
                ])
                    ->with(['branch' => function($query){
                        $query->select('branches.id', 'branches.name');
                    }])
                    ->with(['store' => function($query){
                        $query->select('stores.id', 'stores.name');
                    }]);
            }])
            ->with(['devices' => function($query){
                $query->select([
                    "devices.id",
                    "devices.store_id",
                    "devices.branch_id",
                    "order_devices.status",
                ])->where('order_devices.status' , OrderDevice::STATUS_USING)
                    ->with(['branch' => function($query){
                        $query->select('branches.id', 'branches.name');
                    }])
                    ->with(['store' => function($query){
                        $query->select('stores.id', 'stores.name');
                    }]);
            }])
            ->with(['timeFrames' => function($query){
                $query->select([
                    "time_frames.id",
                    "time_frames.start_date",
                    "time_frames.end_date",
                    "time_frames.start_time",
                    "time_frames.end_time",
                    "time_frames.order_id",
                ]);
            }])
            ->withCount(['storeCrossDeviceCollections']);
        return $query;
    }

    private function withUsers()
    {
        $query = $this->getBlankModel()->select([
            "orders.id",
            "orders.payment",
            "orders.status",
            "orders.code",
            "orders.note",
            "orders.store_id",
            "orders.created_at",
            "orders.store_account_id",
            "orders.type_booking",
            "orders.time_booked",
            "orders.created_at",
        ])
        ->with(['storeAccount' => function($query){
            $query->select([
                "store_accounts.id",
                "store_accounts.username",
                "store_accounts.representative",
                "store_accounts.role",
                "store_accounts.store_id",
                "store_accounts.branch_id",
            ])
            ->with(['branch' => function($query){
                $query->select('branches.id', 'branches.name');
            }])
            ->with(['store' => function($query){
                $query->select('stores.id', 'stores.name');
            }]);
        }])
        ->with(['devices' => function($query){
                $query->select([
                    "devices.id",
                    "devices.store_id",
                    "devices.branch_id",
                    "order_devices.status",
                ])->where('order_devices.status' , OrderDevice::STATUS_USING)
                ->with(['branch' => function($query){
                    $query->select('branches.id', 'branches.name');
                }])
                ->with(['store' => function($query){
                    $query->select('stores.id', 'stores.name');
                }]);
            }])
            ->with(['branches' => function($query){
                $query->select('branches.id', 'branches.name');
            }]);
        return $query;
    }

    private function withCrossData($filter)
    {
        $query = $this->getBlankModel()->select([
            "orders.id",
            "orders.payment",
            "orders.status",
            "orders.code",
            "orders.note",
            "orders.store_id",
            "orders.created_at",
            "orders.store_account_id",
            "orders.type_booking",
            "orders.time_booked",
            "orders.created_at",
        ])
            ->with(['storeAccount' => function($query){
                $query->select([
                    "store_accounts.id",
                    "store_accounts.username",
                    "store_accounts.representative",
                    "store_accounts.role",
                    "store_accounts.store_id",
                    "store_accounts.branch_id",
                ])
                    ->with(['branch' => function($query){
                        $query->select('branches.id', 'branches.name');
                    }])
                    ->with(['store' => function($query){
                        $query->select('stores.id', 'stores.name');
                    }]);
            }])
            ->with(['devices' => function($query){
                $query->select([
                    "devices.id",
                    "devices.store_id",
                    "devices.branch_id",
                    "order_devices.status",
                ])->where('order_devices.status' , OrderDevice::STATUS_USING)
                    ->with(['branch' => function($query){
                        $query->select('branches.id', 'branches.name');
                    }])
                    ->with(['store' => function($query){
                        $query->select('stores.id', 'stores.name');
                    }]);
            }])
            ->with(['branches' => function($query){
                $query->select('branches.id', 'branches.name');
            }]);

        if (isset($filter['store_cross_id'])){
            $storeId = $filter['store_cross_id'];
            $query = $query->whereHas('orderStore', function($query) use ($storeId){
                $query->select('order_stores.id', 'order_stores.order_id', 'order_stores.store_id')->where('order_stores.store_id', $storeId);
            });
        }

        return $query;
    }


    private function filter($filter, &$query)
    {

        if (isset($filter['key_word'])) {
            $query = $query->search($filter['key_word']);
        }

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('orders.id', $filter['id']);
            } else {
                $query = $query->where('orders.id', $filter['id']);
            }
        }

        if (isset($filter['store_id'])) {
            if (is_array($filter['store_id'])) {
                $query = $query->whereIn('orders.store_id', $filter['store_id']);
            } else {
                $query = $query->where('orders.store_id', $filter['store_id']);
            }
        }

        if (isset($filter['store_account_id'])) {
            if (is_array($filter['store_account_id'])) {
                $query = $query->whereIn('orders.store_account_id', $filter['store_account_id']);
            } else {
                $query = $query->where('orders.store_account_id', $filter['store_account_id']);
            }
        }

        if (isset($filter['status'])) {
            if (is_array($filter['status'])) {
                $query = $query->whereIn('orders.status', $filter['status']);
            } else {
                $query = $query->where('orders.status', $filter['status']);
            }
        }

        if (isset($filter['isDelete'])) {
            $query = $query->where(function ($query){
                $query->orWhere('orders.deleted_at', null);
                $query->orWhere('orders.deleted_at', '!=', null);
            });
        }

        if (isset($filter['side_store_id'])) {
            if (is_array($filter['side_store_id'])) {
                $query = $query->whereIn('orders.side_store_id', $filter['side_store_id']);
            } else {
                $query = $query->where('orders.side_store_id', $filter['side_store_id']);
            }
        }

        if (isset($filter['side_branch_id'])) {
            if (is_array($filter['side_branch_id'])) {
                $query = $query->whereIn('orders.side_branch_id', $filter['side_branch_id']);
            } else {
                $query = $query->where('orders.side_branch_id', $filter['side_branch_id']);
            }
        }

        if (isset($filter['deleted_at'])) {
            $query = $query->where('orders.deleted_at', null);
        }

        if (isset($filter['code'])) {
            $query = $query->where('orders.code', $filter['code']);
        }

        if (isset($filter['start_date']) && isset($filter['end_date'])) {
            $query = $query->whereDate('orders.created_at', '>=', $filter['start_date'])->whereDate('orders.created_at', '<=', $filter['end_date']);
        }

        if (isset($filter['store_account_id'])) {
            $query = $query->where('orders.store_account_id', $filter['store_account_id']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('orders.project_id', $filter['project_id']);
        }

    }
}
