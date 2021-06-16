<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Admin\DeviceStatisticRepositoryInterface;
use \App\Models\Postgres\Admin\DeviceStatistic;

class DeviceStatisticRepository extends SingleKeyModelRepository implements DeviceStatisticRepositoryInterface
{

    public function getBlankModel()
    {
        return new DeviceStatistic();
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

    public function getOneObjectDeviceStatisticByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->first();

        return $data;
    }

    public function getListDeviceStatisticByFilter($limit, $filter)
    {
        $query = $this->withDeviceAndStatistic($filter);

        $this->filter($filter, $query);

        $data = $query->paginate($limit)->toArray();

        return $data;
    }

    public function getAllDeviceStatisticForExportByFilter($filter)
    {
        $query = $this->withDeviceAndStatistic($filter);

        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return ['data' => $data];
    }

    private function withDeviceAndStatistic($filter)
    {
        $query = $this->getBlankModel()->select([
            "device_statistics.id",
            "device_statistics.device_id",
            "device_statistics.project_id",
        ])
            ->with(['device' => function($query){
                $query->select('devices.id', 'devices.name', 'devices.device_code', 'devices.store_id', 'devices.branch_id', 'devices.deleted_at')
                    ->with(['storeWithTrashed' => function($query){
                        $query->select('stores.id', 'stores.name');
                    }])
                    ->with(['branchWithTrashed' => function($query){
                        $query->select('branches.id', 'branches.name', 'branches.address', 'branches.province_id', 'branches.district_id')
                            ->with(['province' => function($query){
                                $query->select('provinces.id', 'provinces.name');
                            }])
                            ->with(['district' => function($query){
                                $query->select('districts.id', 'districts.name');
                            }]);
                    }]);
            }])
            ->with(['adminDeviceStatistics' => function($query) use ($filter){
                $query->select('admin_device_statistics.*')->whereDate('admin_device_statistics.date_at', '>=', $filter['start_date'])->whereDate('admin_device_statistics.date_at', '<=', $filter['end_date'])->orderBy('admin_device_statistics.date_at', 'asc');
            }])
            ->with(['storeDeviceStatistics' => function($query) use ($filter){
                $query->select('store_device_statistics.*')->whereDate('store_device_statistics.date_at', '>=', $filter['start_date'])->whereDate('store_device_statistics.date_at', '<=', $filter['end_date'])->orderBy('store_device_statistics.date_at', 'asc');
            }])
            ->with(['storeCrossDeviceStatistics' => function($query) use ($filter){
                $query->select('store_cross_device_statistics.*')->whereDate('store_cross_device_statistics.date_at', '>=', $filter['start_date'])->whereDate('store_cross_device_statistics.date_at', '<=', $filter['end_date'])->orderBy('store_cross_device_statistics.date_at', 'asc');
            }]);

        return $query;
    }

    private function filter($filter, &$query)
    {

        if (isset($filter['key_word'])) {
            $query = $query->search($filter['key_word']);
        }

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('device_statistics.id', $filter['id']);
            } else {
                $query = $query->where('device_statistics.id', $filter['id']);
            }
        }

        if (isset($filter['device_id'])) {
            if (is_array($filter['device_id'])) {
                $query = $query->whereIn('device_statistics.device_id', $filter['device_id']);
            } else {
                $query = $query->where('device_statistics.device_id', $filter['device_id']);
            }
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('device_statistics.project_id', $filter['project_id']);
        }
    }

}
