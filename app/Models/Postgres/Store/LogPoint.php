<?php namespace App\Models\Postgres\Store;
use App\Models\Base;
use App\Models\Postgres\Admin\Branch;


class LogPoint extends Base
{



    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'log_points';

    const TYPE_BONUS_POINT = 1;
    const TYPE_MINUS_POINT = 2;

    const TRANSACTION = [
        'minus' => 'Trừ điểm đặt quảng cáo chéo',
        'plus' => 'Cộng điểm đặt quảng cáo chéo',
        'plus_ant' => 'Cộng điểm do bên Ant quảng cáo vào',
        'refund_order' => 'Cộng điểm do bị từ chối quảng cáo chéo'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'point',
        'code',
        'time',
        'device_id',
        'transaction',
        'order_id',
        'store_id',
        'branch_id',
        'project_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\Postgres\Store\LogPointPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Store\LogPointObserver);
    }

    // Relations
    public function storeAccount(){
        return $this->hasOne(StoreAccount::class,'id', 'store_account_id');
    }

    public function branch(){
        return $this->hasOne(Branch::class,'id', 'branch_id');
    }

    public function timeFrames(){
        return $this->hasManyThrough(TimeFrame::class, TimeFrameLogPoint::class,'log_point_id','id','id','time_frame_id');
    }
    // Utility Functions
    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'point' => $this->point,
            'code' => $this->code,
            'time' => $this->time,
            'device_id' => $this->device_id,
            'transaction' => $this->transaction,
            'order_id' => $this->order_id,
            'store_id' => $this->store_id,
            'branch_id' => $this->branch_id,
            'project_id' => $this->project_id,
        ];
    }

}
