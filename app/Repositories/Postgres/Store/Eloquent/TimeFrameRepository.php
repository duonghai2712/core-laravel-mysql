<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Store\TimeFrameRepositoryInterface;
use \App\Models\Postgres\Store\TimeFrame;

class TimeFrameRepository extends SingleKeyModelRepository implements TimeFrameRepositoryInterface
{

    public function getBlankModel()
    {
        return new TimeFrame();
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

    public function getAllTimeFramesByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);
        $data = $query->orderBy('start_date', 'asc')->get()->toArray();

        return $data;
    }

    private function withOrder()
    {
        $query = $this->getBlankModel()->select([
            "time_frames.*",
        ])
            ->with(['order' => function($query){
                $query->select([
                    "orders.id",
                    "orders.time_booked",
                ]);
            }]);
        return $query;
    }

    private function filter($filter, &$query)
    {

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('time_frames.id', $filter['id']);
            } else {
                $query = $query->where('time_frames.id', $filter['id']);
            }
        }

        if (isset($filter['order_id'])) {
            if (is_array($filter['order_id'])) {
                $query = $query->whereIn('time_frames.order_id', $filter['order_id']);
            } else {
                $query = $query->where('time_frames.order_id', $filter['order_id']);
            }
        }

        if (isset($filter['start_date']) && isset($filter['end_date'])) {
            $time_start = $filter['start_date'];
            $time_end = $filter['end_date'];
            $query = $query->where(function ($query) use ($time_start, $time_end) {

                $query->orWhere(function ($query) use ($time_start, $time_end) {
                    $query->whereDate('time_frames.start_date', '>=', $time_start)
                        ->whereDate('time_frames.start_date', '<', $time_end)
                        ->whereDate('time_frames.end_date', '>=', $time_end);
                });

                $query->orWhere(function ($query) use ($time_start) {
                    $query->whereDate('time_frames.end_date', $time_start)
                        ->whereDate('time_frames.end_date', $time_start);
                });

                $query->orWhere(function ($query) use ($time_start) {
                    $query->whereDate('time_frames.start_date', $time_start)
                        ->whereDate('time_frames.start_date', $time_start);
                });

                $query->orWhere(function ($query) use ($time_end) {
                    $query->whereDate('time_frames.end_date', $time_end)
                        ->whereDate('time_frames.end_date', $time_end);
                });

                $query->orWhere(function ($query) use ($time_end) {
                    $query->whereDate('time_frames.start_date', $time_end)
                        ->whereDate('time_frames.start_date', $time_end);
                });

                $query->orWhere(function ($query) use ($time_start, $time_end) {
                    $query->whereDate('time_frames.start_date', '<=', $time_start)
                        ->whereDate('time_frames.end_date', '>=', $time_end);
                });

                $query->orWhere(function ($query) use ($time_start, $time_end) {
                    $query->whereDate('time_frames.start_date', '<=', $time_start)
                        ->whereDate('time_frames.end_date', '>', $time_start)
                        ->whereDate('time_frames.end_date', '<=', $time_end);
                });
            });

            $query->whereRaw('time_frames.start_date <= time_frames.end_date');

        }elseif (isset($filter['start_date'])){
            $query->whereDate('time_frames.start_date', '>=', $filter['start_date']);
        }

        if (isset($filter['start_time']) && isset($filter['end_time'])) {
            $time_start = $filter['start_time'];
            $time_end = $filter['end_time'];
            $query = $query->where(function ($query) use ($time_start, $time_end) {

                $query->orWhere(function ($query) use ($time_start, $time_end) {
                    $query->whereTime('time_frames.start_time', '>=', $time_start)
                        ->whereTime('time_frames.start_time', '<', $time_end)
                        ->whereTime('time_frames.end_time', '>=', $time_end);
                });

                $query->orWhere(function ($query) use ($time_start, $time_end) {
                    $query->whereTime('time_frames.start_time', '>=', $time_start)
                        ->whereTime('time_frames.end_time', '<=', $time_end);
                });

                $query->orWhere(function ($query) use ($time_start, $time_end) {
                    $query->whereTime('time_frames.end_time', $time_start)
                        ->whereTime('time_frames.end_time', $time_end);
                });

                $query->orWhere(function ($query) use ($time_start, $time_end) {
                    $query->whereTime('time_frames.start_time', '=', $time_start)
                        ->whereTime('time_frames.end_time', '>=', $time_end);
                });

                $query->orWhere(function ($query) use ($time_start, $time_end) {
                    $query->whereTime('time_frames.start_time', '<', $time_start)
                        ->whereTime('time_frames.end_time', '>', $time_end);
                });

                $query->orWhere(function ($query) use ($time_start, $time_end) {
                    $query->whereTime('time_frames.start_time', '=', $time_start)
                        ->whereTime('time_frames.end_time', '>', $time_start)
                        ->whereTime('time_frames.end_time', '<=', $time_end);
                });
            });

            $query->whereRaw('time_frames.start_time < time_frames.end_time');

        }else if (isset($filter['start_time'])){
            $query->whereTime('time_frames.start_time', '>=', $filter['start_time']);
        }

        if (isset($filter['frequency'])) {
            $query = $query->where('time_frames.frequency', $filter['frequency']);
        }

        if (isset($filter['total'])) {
            $query = $query->where('time_frames.total', $filter['total']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('time_frames.project_id', $filter['project_id']);
        }

    }

}
