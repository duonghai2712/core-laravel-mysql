<?php namespace App\Models\Postgres\Store;
use App\Models\Base;
use App\Models\Postgres\Admin\Branch;
use App\Models\Postgres\Admin\Device;
use App\Models\Postgres\Admin\Store;


class StoreCrossDeviceStatistic extends Base
{



    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'store_cross_device_statistics';

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
        'collection_id',
        'store_id',
        'branch_id',
        'rank_id',
        'order_id',
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

    protected $presenter = \App\Presenters\Postgres\Store\StoreCrossDeviceStatisticPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Store\StoreCrossDeviceStatisticObserver);
    }

    // Relations


    // Utility Functions
    public function collection(){
        return $this->hasOne(Collection::class,'id', 'collection_id')->withTrashed();
    }

    public function device(){
        return $this->hasOne(Device::class,'id', 'device_id')->withTrashed();
    }

    public function store(){
        return $this->hasOne(Store::class,'id', 'store_id')->withTrashed();
    }

    public function branch(){
        return $this->hasOne(Branch::class,'id', 'branch_id')->withTrashed();
    }

    public function order(){
        return $this->hasOne(Order::class,'id', 'order_id')->withTrashed();
    }
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
            'order_id' => $this->order_id,
            'date_at' => $this->date_at,
            'second' => $this->second,
            'total_time' => $this->total_time,
            'number_time' => $this->number_time,

        ];
    }

}
