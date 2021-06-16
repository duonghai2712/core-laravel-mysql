<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Store\LogPointRepositoryInterface;
use \App\Models\Postgres\Store\LogPoint;

class LogPointRepository extends SingleKeyModelRepository implements LogPointRepositoryInterface
{

    public function getBlankModel()
    {
        return new LogPoint();
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

    public function getAllLogPointByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);
        $data = $query->get()->toArray();

        return $data;
    }

    public function getListLogPointByFilter($limit, $filter)
    {
        $query = $this->withTimeFrame();
        $this->filter($filter, $query);

        $data = $query->orderBy('log_points.created_at', 'desc')->paginate($limit)->toArray();

        return $data;
    }

    private function withTimeFrame()
    {
        $query = $this->getBlankModel()->select([
            "log_points.*",
        ])
            ->with(['branch' => function($query){
                $query->select([
                    "branches.id",
                    "branches.name",
                ]);
            }])
        ->with(['timeFrames' => function($query){
        $query->select([
            "time_frames.id",
            "time_frames.start_time",
            "time_frames.end_time",
            "time_frames.start_date",
            "time_frames.end_date",
            "time_frame_log_points.id as time_frame_log_points_id",
        ]);
    }]);

        return $query;
    }

    private function filter($filter, &$query)
    {

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('log_points.id', $filter['id']);
            } else {
                $query = $query->where('log_points.id', $filter['id']);
            }
        }

        if (isset($filter['store_id'])) {
            if (is_array($filter['store_id'])) {
                $query = $query->whereIn('log_points.store_id', $filter['store_id']);
            } else {
                $query = $query->where('log_points.store_id', $filter['store_id']);
            }
        }

        if (isset($filter['branch_id'])) {
            if (is_array($filter['branch_id'])) {
                $query = $query->whereIn('log_points.branch_id', $filter['branch_id']);
            } else {
                $query = $query->where('log_points.branch_id', $filter['branch_id']);
            }
        }

        if (isset($filter['order_id'])) {
            if (is_array($filter['order_id'])) {
                $query = $query->whereIn('log_points.order_id', $filter['order_id']);
            } else {
                $query = $query->where('log_points.order_id', $filter['order_id']);
            }
        }

        if (isset($filter['start_date']) && isset($filter['end_date'])) {
            $query = $query->whereDate('log_points.created_at', '>=', $filter['start_date'])->whereDate('log_points.created_at', '<=', $filter['end_date']);
        }

        if (isset($filter['type'])) {
            $query = $query->where('log_points.type', $filter['type']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('log_points.project_id', $filter['project_id']);
        }

    }
}
