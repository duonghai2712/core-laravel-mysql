<?php namespace App\Models\Postgres\Store;
use App\Models\Base;
use App\Models\Postgres\Admin\Branch;
use App\Models\Postgres\Admin\Device;
use App\Models\Postgres\Admin\Store;


class OrderDevice extends Base
{



    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_devices';

    const STATUS_USING = 1;
    const STATUS_DELETE = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'point',
        'real_point',
        'order_id',
        'device_id',
        'status',
        'block_time',
        'total_time_store',
        'total_time_admin',
        'order_branch_id',
        'order_store_id',
        'project_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\Postgres\Store\OrderDevicePresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Store\OrderDeviceObserver);
    }

    // Relations

    // Utility Functions
    public function device(){
        return $this->hasOne(Device::class,'id', 'device_id');
    }

    public function orderStore(){
        return $this->hasOne(OrderStore::class,'id', 'order_store_id');
    }

    public function orderBranch(){
        return $this->hasOne(OrderBranch::class,'id', 'order_branch_id');
    }

    public function order(){
        return $this->hasOne(Order::class,'id', 'order_id');
    }
    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'point' => $this->point,
            'status' => $this->status,
            'real_point' => $this->real_point,
            'block_time' => $this->block_time,
            'total_time_store' => $this->total_time_store,
            'total_time_admin' => $this->total_time_admin,
            'order_id' => $this->order_id,
            'device_id' => $this->device_id,
            'order_branch_id' => $this->order_branch_id,
            'order_store_id' => $this->order_store_id,
            'project_id' => $this->project_id,
        ];
    }

}
