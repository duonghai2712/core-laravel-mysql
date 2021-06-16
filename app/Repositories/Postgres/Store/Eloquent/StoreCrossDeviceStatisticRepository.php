<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Store\StoreCrossDeviceStatisticRepositoryInterface;
use \App\Models\Postgres\Store\StoreCrossDeviceStatistic;
use  DB;

class StoreCrossDeviceStatisticRepository extends SingleKeyModelRepository implements StoreCrossDeviceStatisticRepositoryInterface
{

    public function getBlankModel()
    {
        return new StoreCrossDeviceStatistic();
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

    public function getListStoreCrossDeviceStatisticByFilter($limit, $filter)
    {
        $query = $this->withStatistic();
        $this->filter($filter, $query);

        $data = $query->paginate($limit)->toArray();

        return $data;
    }

    public function getAllStoreCrossDeviceStatisticByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function countTotalTimePlayCrossUsedByFilter($filter)
    {
        $query = $this->getBlankModel()->select([
            "store_cross_device_statistics.id",
            "store_cross_device_statistics.store_id",
            "store_cross_device_statistics.project_id",
            "store_cross_device_statistics.order_id",
            "store_cross_device_statistics.total_time"
        ]);
        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    private function withStatistic()
    {
        $query = $this->getBlankModel()->select([
            "store_cross_device_statistics.collection_id",
            "store_cross_device_statistics.branch_id",
            "store_cross_device_statistics.order_id",
            DB::raw('COUNT(DISTINCT store_cross_device_statistics.device_id) AS total_device'),
            DB::raw('SUM(store_cross_device_statistics.total_time) as total_time'),
            DB::raw('SUM(store_cross_device_statistics.number_time) as number_time')
        ])
            ->with(['collection' => function($query){
                $query->select('collections.id', 'collections.name', 'collections.source', 'collections.source_thumb', 'collections.mimes', 'collections.type');
            }])
            ->with(['branch' => function($query){
                $query->select('branches.id', 'branches.name');
            }])
            ->with(['order' => function($query){
                $query->select('orders.id')
                    ->with(['timeFrames' => function($query){
                        $query->select('time_frames.id', 'time_frames.start_date', 'time_frames.end_date', 'time_frames.start_time', 'time_frames.end_time', 'time_frames.order_id');
                    }]);
            }])
        ->groupBy('store_cross_device_statistics.collection_id', 'store_cross_device_statistics.branch_id', 'store_cross_device_statistics.order_id');
        return $query;
    }


    private function filter($filter, &$query)
    {
        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('store_cross_device_statistics.id', $filter['id']);
            } else {
                $query = $query->where('store_cross_device_statistics.id', $filter['id']);
            }
        }

        if (isset($filter['store_id'])) {
            if (is_array($filter['store_id'])) {
                $query = $query->whereIn('store_cross_device_statistics.store_id', $filter['store_id']);
            } else {
                $query = $query->where('store_cross_device_statistics.store_id', $filter['store_id']);
            }
        }

        if (isset($filter['device_id'])) {
            if (is_array($filter['device_id'])) {
                $query = $query->whereIn('store_cross_device_statistics.device_id', $filter['device_id']);
            } else {
                $query = $query->where('store_cross_device_statistics.device_id', $filter['device_id']);
            }
        }

        if (isset($filter['device_statistic_id'])) {
            if (is_array($filter['device_statistic_id'])) {
                $query = $query->whereIn('store_cross_device_statistics.device_statistic_id', $filter['device_statistic_id']);
            } else {
                $query = $query->where('store_cross_device_statistics.device_statistic_id', $filter['device_statistic_id']);
            }
        }

        if (isset($filter['collection_id'])) {
            if (is_array($filter['collection_id'])) {
                $query = $query->whereIn('store_cross_device_statistics.collection_id', $filter['collection_id']);
            } else {
                $query = $query->where('store_cross_device_statistics.collection_id', $filter['collection_id']);
            }
        }

        if (isset($filter['branch_id'])) {
            if (is_array($filter['branch_id'])) {
                $query = $query->whereIn('store_cross_device_statistics.branch_id', $filter['branch_id']);
            } else {
                $query = $query->where('store_cross_device_statistics.branch_id', $filter['branch_id']);
            }
        }

        if (isset($filter['rank_id'])) {
            if (is_array($filter['rank_id'])) {
                $query = $query->whereIn('store_cross_device_statistics.rank_id', $filter['rank_id']);
            } else {
                $query = $query->where('store_cross_device_statistics.rank_id', $filter['rank_id']);
            }
        }

        if (isset($filter['order_id'])) {
            if (is_array($filter['order_id'])) {
                $query = $query->whereIn('store_cross_device_statistics.order_id', $filter['order_id']);
            } else {
                $query = $query->where('store_cross_device_statistics.order_id', $filter['order_id']);
            }
        }

        if (isset($filter['date_at'])) {
            $query = $query->whereDate('store_cross_device_statistics.date_at', $filter['date_at']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('store_cross_device_statistics.project_id', $filter['project_id']);
        }

    }
}
