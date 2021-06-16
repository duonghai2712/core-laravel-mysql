<?php namespace App\Models\Postgres\Admin;
use App\Models\Base;


class AdminDeviceStatistic extends Base
{



    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin_device_statistics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'device_id',
        'project_id',
        'type',
        'device_statistic_id',
        'image_id',
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

    protected $presenter = \App\Presenters\Postgres\Admin\AdminDeviceStatisticPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Admin\AdminDeviceStatisticObserver);
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
            'device_id' => $this->device_id,
            'type' => $this->type,
            'project_id' => $this->project_id,
            'device_statistic_id' => $this->device_statistic_id,
            'image_id' => $this->image_id,
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
