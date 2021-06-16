<?php namespace App\Models\Postgres\Store;
use App\Models\Base;


class TimeFrameLogPoint extends Base
{



    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'time_frame_log_points';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'log_point_id',
        'order_id',
        'time_frame_id',
        'project_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\Postgres\Store\TimeFrameLogPointPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Store\TimeFrameLogPointObserver);
    }

    // Relations


    // Utility Functions

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'log_point_id' => $this->log_point_id,
            'time_frame_id' => $this->time_frame_id,
            'project_id' => $this->project_id,
        ];
    }

}
