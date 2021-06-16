<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Store\TimeFrameLogPointRepositoryInterface;
use \App\Models\Postgres\Store\TimeFrameLogPoint;

class TimeFrameLogPointRepository extends SingleKeyModelRepository implements TimeFrameLogPointRepositoryInterface
{

    public function getBlankModel()
    {
        return new TimeFrameLogPoint();
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

    public function getAllTimeFrameLogPointByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);
        $data = $query->get()->toArray();

        return $data;
    }

    private function filter($filter, &$query)
    {

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('time_frame_log_points.id', $filter['id']);
            } else {
                $query = $query->where('time_frame_log_points.id', $filter['id']);
            }
        }

        if (isset($filter['order_id'])) {
            if (is_array($filter['order_id'])) {
                $query = $query->whereIn('time_frame_log_points.order_id', $filter['order_id']);
            } else {
                $query = $query->where('time_frame_log_points.order_id', $filter['order_id']);
            }
        }

        if (isset($filter['log_point_id'])) {
            if (is_array($filter['log_point_id'])) {
                $query = $query->whereIn('time_frame_log_points.log_point_id', $filter['log_point_id']);
            } else {
                $query = $query->where('time_frame_log_points.log_point_id', $filter['log_point_id']);
            }
        }

        if (isset($filter['time_frame_id'])) {
            if (is_array($filter['time_frame_id'])) {
                $query = $query->whereIn('time_frame_log_points.time_frame_id', $filter['time_frame_id']);
            } else {
                $query = $query->where('time_frame_log_points.time_frame_id', $filter['time_frame_id']);
            }
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('time_frame_log_points.project_id', $filter['project_id']);
        }

    }
}
