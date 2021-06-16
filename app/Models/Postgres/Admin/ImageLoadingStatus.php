<?php namespace App\Models\Postgres\Admin;
use App\Models\Base;


class ImageLoadingStatus extends Base
{



    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'image_loading_statuses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'device_id',
        'type',
        'image_id',
        'status',
        'device_loading_status_id',
        'time_at',
        'date_at',
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

    protected $presenter = \App\Presenters\Postgres\Admin\ImageLoadingStatusPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Admin\ImageLoadingStatusObserver);
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
            'image_id' => $this->image_id,
            'status' => $this->status,
            'device_loading_status_id' => $this->device_loading_status_id,
            'time_at' => $this->time_at,
            'date_at' => $this->date_at,
            'branch_id' => $this->branch_id,
            'store_id' => $this->store_id,
            'project_id' => $this->project_id,
        ];
    }

}
