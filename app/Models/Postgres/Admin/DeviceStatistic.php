<?php namespace App\Models\Postgres\Admin;
use App\Models\Base;
use App\Models\Postgres\Store\StoreCrossDeviceStatistic;
use App\Models\Postgres\Store\StoreDeviceStatistic;


class DeviceStatistic extends Base
{



    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'device_statistics';

    const FILENAME = 'thong_ke_bao_cao_thiet_bi_';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'device_id',
        'branch_id',
        'store_id',
        'project_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];


    protected $presenter = \App\Presenters\Postgres\Admin\DeviceStatisticPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Admin\DeviceStatisticObserver);
    }

    // Relations
    public function adminDeviceStatistics(){
        return $this->hasMany(AdminDeviceStatistic::class,'device_statistic_id');
    }

    public function storeDeviceStatistics(){
        return $this->hasMany(StoreDeviceStatistic::class,'device_statistic_id');
    }

    public function storeCrossDeviceStatistics(){
        return $this->hasMany(StoreCrossDeviceStatistic::class,'device_statistic_id');
    }

    public function device(){
        return $this->hasOne(Device::class,'id', 'device_id')->withTrashed();
    }

    // Utility Functions

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'branch_id' => $this->branch_id,
            'store_id' => $this->store_id,
            'device_id' => $this->device_id,
            'project_id' => $this->project_id,
        ];
    }

}
