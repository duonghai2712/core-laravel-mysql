<?php namespace App\Models\Postgres\Store;
use App\Models\Base;
use App\Models\Postgres\Admin\Branch;
use App\Models\Postgres\Admin\Device;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Nicolaslopezj\Searchable\SearchableTrait;


class Order extends Base
{

    use SoftDeletes;
    use Notifiable;
    use SearchableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';

    protected $searchable = [
        'columns' => [
            'orders.code' => 10,
        ]
    ];

    const MIN_TIME_BOOKED = 0;

    const WAIT = 1;
    const CONFIRMED = 2;
    const COMPLETED = 3;
    const DECLINED = 4;
    const CANCELED = 5;

    const TYPE_BOOKING_SELECTED = 1;
    const TYPE_BOOKING_ESTIMATE = 2;

    const RESOLVE = 1;
    const REJECT = 2;

    const NEW_ORDER = 1;
    const CHANGE_ORDER = 2;

    const DATE_TO_CHECK = ' 2021-05-05';//Vì khoản thời gian là trong ngày nên là lấy đại 1 ngày đển so sánh các khoảng thời gian

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payment',
        'status',
        'code',
        'note',
        'is_added_point',
        'type_booking',
        'block_time_order',
        'store_id',
        'branch_id',
        'time_booked',
        'total_slot',
        'reason',
        'current_slot',
        'current_time_booked',
        'approval_time',
        'store_account_id',
        'project_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = ['deleted_at'];

    protected $presenter = \App\Presenters\Postgres\Store\OrderPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Store\OrderObserver);
    }

    // Relations
    public function storeAccount(){
        return $this->hasOne(StoreAccount::class,'id', 'store_account_id');
    }

    public function devices(){
        return $this->hasManyThrough(Device::class, OrderDevice::class,'order_id','id','id','device_id');
    }

    public function branches(){
        return $this->hasManyThrough(Branch::class, OrderBranch::class,'order_id','id','id','branch_id');
    }

    public function orderStore(){
        return $this->hasMany(OrderStore::class,'order_id');
    }

    public function orderBranch(){
        return $this->hasMany(OrderBranch::class,'order_id');
    }

    public function orderDevice(){
        return $this->hasMany(OrderDevice::class,'order_id');
    }

    public function timeFrames(){
        return $this->hasMany(TimeFrame::class,'order_id');
    }

    public function storeCrossDeviceCollections(){
        return $this->hasMany(StoreCrossDeviceCollection::class,'order_id');
    }

    public function storeCrossDeviceStatistic(){
        return $this->hasMany(StoreCrossDeviceStatistic::class,'order_id');
    }


    // Utility Functions

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'payment' => $this->payment,
            'status' => $this->status,
            'code' => $this->code,
            'note' => $this->note,
            'block_time_order' => $this->block_time_order,
            'is_added_point' => $this->is_added_point,
            'reason' => $this->reason,
            'branch_id' => $this->branch_id,
            'total_slot' => $this->total_slot,
            'time_booked' => $this->time_booked,
            'type_booking' => $this->type_booking,
            'store_id' => $this->store_id,
            'current_slot' => $this->current_slot,
            'current_time_booked' => $this->current_time_booked,
            'approval_time' => $this->approval_time,
            'store_account_id' => $this->store_account_id,
            'project_id' => $this->project_id,
        ];
    }

}
