<?php namespace App\Models\Postgres\Store;
use App\Models\Base;


class TimeFrame extends Base
{



    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'time_frames';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'frequency',
        'total',
        'project_id',
        'order_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\Postgres\Store\TimeFramePresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Store\TimeFrameObserver);
    }

    // Relations
    public function order(){
        return $this->hasOne(Order::class,'id', 'order_id');
    }

    // Utility Functions

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'frequency' => $this->frequency,
            'total' => $this->total,
            'project_id' => $this->project_id,
            'order_id' => $this->order_id,
        ];
    }

}
