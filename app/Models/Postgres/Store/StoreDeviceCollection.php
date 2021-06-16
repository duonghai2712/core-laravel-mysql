<?php namespace App\Models\Postgres\Store;
use App\Models\Base;
use App\Models\Postgres\Admin\Device;


class StoreDeviceCollection extends Base
{



    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'store_device_collections';

    const ENABLE_VOLUME = 1;
    const DISABLE_VOLUME = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'device_id',
        'collection_id',
        'project_id',
        'store_id',
        'volume',// 1 là bật âm thanh, 2 là tắt âm thanh
        'store_account_id',
        'position',
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

    protected $presenter = \App\Presenters\Postgres\Store\StoreDeviceCollectionPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Store\StoreDeviceCollectionObserver);
    }

    // Relations
    public function collection(){
        return $this->hasOne(Collection::class,'id', 'collection_id');
    }

    public function device(){
        return $this->hasOne(Device::class,'id', 'device_id');
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
            'position' => $this->position,
            'second' => $this->second,
            'type' => $this->type,
        ];
    }

}
