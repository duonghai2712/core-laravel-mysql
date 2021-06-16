<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Models\Postgres\Store\Collection;
use App\Models\Postgres\Store\StoreDeviceCollection;
use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Store\StoreDeviceStatisticRepositoryInterface;
use \App\Models\Postgres\Store\StoreDeviceStatistic;
use  DB;

class StoreDeviceStatisticRepository extends SingleKeyModelRepository implements StoreDeviceStatisticRepositoryInterface
{

    public function getBlankModel()
    {
        return new StoreDeviceStatistic();
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

    public function getDataForChart($filter)
    {
        $query = $this->forChart();
        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    private function forChart()
    {
        $query = $this->getBlankModel()->select([
            "store_device_statistics.date_at",
            DB::raw('COUNT(DISTINCT store_device_statistics.device_id) AS total_device'),
            DB::raw('SUM(store_device_statistics.total_time) AS total_time')
        ])->groupBy("store_device_statistics.date_at");
        return $query;
    }

    public function countTotalTimeStoreUsedByFilter($filter)
    {
        $query = $this->getBlankModel()->select([
            "store_device_statistics.id",
            "store_device_statistics.store_id",
            "store_device_statistics.total_time",
            "store_device_statistics.project_id",
        ]);

        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function getDataForStatisticMedia($filter)
    {
        $query = $this->getBlankModel()->select([
            "store_device_statistics.store_id",
            DB::raw('COUNT(case when store_device_statistics.type = ' . Collection::IMAGE . ' then 1 ELSE NULL END) AS total_image'),
            DB::raw('COUNT(case when store_device_statistics.type = ' . Collection::VIDEO . ' then 1 ELSE NULL end) AS total_video'),
        ])->groupBy("store_device_statistics.store_id");

        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }


    private function filter($filter, &$query)
    {
        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('store_device_statistics.id', $filter['id']);
            } else {
                $query = $query->where('store_device_statistics.id', $filter['id']);
            }
        }

        if (isset($filter['store_id'])) {
            if (is_array($filter['store_id'])) {
                $query = $query->whereIn('store_device_statistics.store_id', $filter['store_id']);
            } else {
                $query = $query->where('store_device_statistics.store_id', $filter['store_id']);
            }
        }

        if (isset($filter['device_id'])) {
            if (is_array($filter['device_id'])) {
                $query = $query->whereIn('store_device_statistics.device_id', $filter['device_id']);
            } else {
                $query = $query->where('store_device_statistics.device_id', $filter['device_id']);
            }
        }

        if (isset($filter['device_statistic_id'])) {
            if (is_array($filter['device_statistic_id'])) {
                $query = $query->whereIn('store_device_statistics.device_statistic_id', $filter['device_statistic_id']);
            } else {
                $query = $query->where('store_device_statistics.device_statistic_id', $filter['device_statistic_id']);
            }
        }

        if (isset($filter['collection_id'])) {
            if (is_array($filter['collection_id'])) {
                $query = $query->whereIn('store_device_statistics.collection_id', $filter['collection_id']);
            } else {
                $query = $query->where('store_device_statistics.collection_id', $filter['collection_id']);
            }
        }

        if (isset($filter['branch_id'])) {
            if (is_array($filter['branch_id'])) {
                $query = $query->whereIn('store_device_statistics.branch_id', $filter['branch_id']);
            } else {
                $query = $query->where('store_device_statistics.branch_id', $filter['branch_id']);
            }
        }

        if (isset($filter['rank_id'])) {
            if (is_array($filter['rank_id'])) {
                $query = $query->whereIn('store_device_statistics.rank_id', $filter['rank_id']);
            } else {
                $query = $query->where('store_device_statistics.rank_id', $filter['rank_id']);
            }
        }

        if (isset($filter['date_at'])) {
            $query = $query->whereDate('store_device_statistics.date_at', $filter['date_at']);
        }

        if (isset($filter['start_date']) && isset($filter['end_date'])) {
            $query = $query->whereDate('store_device_statistics.date_at', '>=', $filter['start_date'])->whereDate('store_device_statistics.date_at', '<=', $filter['end_date']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('store_device_statistics.project_id', $filter['project_id']);
        }

    }
}
