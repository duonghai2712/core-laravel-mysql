<?php namespace App\Models\Postgres\Admin;
use App\Models\Base;


class DeviceLoadingStatus extends Base
{



    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'device_loading_statuses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'device_id',
        'branch_id',
        'store_id',
        'project_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\Postgres\Admin\DeviceLoadingStatusPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Admin\DeviceLoadingStatusObserver);
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
            'branch_id' => $this->branch_id,
            'store_id' => $this->store_id,
            'project_id' => $this->project_id,
        ];
    }

}
