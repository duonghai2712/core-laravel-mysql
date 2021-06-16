<?php namespace App\Models\Postgres\Store;
use App\Models\Base;


class StoreCrossDeviceCollection extends Base
{



    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'store_cross_device_collections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const COLLECTION_STATUS_WAIT = 1;
    const COLLECTION_STATUS_CONFIRMED = 2;
    const COLLECTION_STATUS_COMPLETED = 3;
    const COLLECTION_STATUS_DECLINED = 4;
    const COLLECTION_STATUS_CANCELED = 5;
    const COLLECTION_STATUS_DELETED = 6;

    protected $fillable = [
        'device_id',
        'collection_id',
        'project_id',
        'store_id',
        'volume',// 1 là bật âm thanh, 2 là tắt âm thanh
        'store_account_id',
        'position',
        'order_id',
        'status',
        'second',
        'type',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\Postgres\Store\StoreCrossDeviceCollectionPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Store\StoreCrossDeviceCollectionObserver);
    }

    // Relations
    public function collection(){
        return $this->hasOne(Collection::class,'id', 'collection_id');
    }

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
            'device_id' => $this->device_id,
            'collection_id' => $this->collection_id,
            'project_id' => $this->project_id,
            'store_id' => $this->store_id,
            'volume' => $this->volume,
            'store_account_id' => $this->store_account_id,
            'order_id' => $this->order_id,
            'status' => $this->status,
            'position' => $this->position,
            'second' => $this->second,
            'type' => $this->type,
        ];
    }

}
