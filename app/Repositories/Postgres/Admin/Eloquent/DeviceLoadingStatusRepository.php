<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Admin\DeviceLoadingStatusRepositoryInterface;
use \App\Models\Postgres\Admin\DeviceLoadingStatus;

class DeviceLoadingStatusRepository extends SingleKeyModelRepository implements DeviceLoadingStatusRepositoryInterface
{

    public function getBlankModel()
    {
        return new DeviceLoadingStatus();
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

    public function getOneObjectDeviceLoadingStatusByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);
        $data = $query->first();

        return $data;
    }

    private function filter($filter, &$query)
    {

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('device_loading_statuses.id', $filter['id']);
            } else {
                $query = $query->where('device_loading_statuses.id', $filter['id']);
            }
        }

        if (isset($filter['device_id'])) {
            if (is_array($filter['device_id'])) {
                $query = $query->whereIn('device_loading_statuses.device_id', $filter['device_id']);
            } else {
                $query = $query->where('device_loading_statuses.device_id', $filter['device_id']);
            }
        }

        if (isset($filter['store_id'])) {
            if (is_array($filter['store_id'])) {
                $query = $query->whereIn('device_loading_statuses.store_id', $filter['store_id']);
            } else {
                $query = $query->where('device_loading_statuses.store_id', $filter['store_id']);
            }
        }

        if (isset($filter['branch_id'])) {
            if (is_array($filter['branch_id'])) {
                $query = $query->whereIn('device_loading_statuses.branch_id', $filter['branch_id']);
            } else {
                $query = $query->where('device_loading_statuses.branch_id', $filter['branch_id']);
            }
        }


        if (isset($filter['project_id'])) {
            $query = $query->where('device_loading_statuses.project_id', $filter['project_id']);
        }

    }
}
