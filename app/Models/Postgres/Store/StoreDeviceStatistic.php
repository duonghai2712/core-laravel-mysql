<?php namespace App\Models\Postgres\Store;
use App\Models\Base;


class StoreDeviceStatistic extends Base
{



    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'store_device_statistics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'device_id',
        'type',
        'project_id',
        'device_statistic_id',
        'collection_id',
        'store_id',
        'branch_id',
        'rank_id',
        'date_at',
        'second',
        'total_time',
        'number_time',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\Postgres\Store\StoreDeviceStatisticPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Store\StoreDeviceStatisticObserver);
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
            'type' => $this->type,
            'device_id' => $this->device_id,
            'project_id' => $this->project_id,
            'device_statistic_id' => $this->device_statistic_id,
            'collection_id' => $this->collection_id,
            'store_id' => $this->store_id,
            'branch_id' => $this->branch_id,
            'rank_id' => $this->rank_id,
            'date_at' => $this->date_at,
            'second' => $this->second,
            'total_time' => $this->total_time,
            'number_time' => $this->number_time,

        ];
    }

}
