<?php namespace App\Models\Postgres\Admin;
use App\Models\Base;


class AdminDeviceImage extends Base
{



    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin_device_images';

    const ENABLE_VOLUME = 1;
    const DISABLE_VOLUME = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'device_id',
        'project_id',
        'image_id',
        'volume',// 1 là bật âm thanh, 2 là tắt âm thanh
        'account_id',
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

    protected $presenter = \App\Presenters\Postgres\Admin\AdminDeviceImagePresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Admin\AdminDeviceImageObserver);
    }

    // Relations
    public function image(){
        return $this->hasOne(Image::class,'id', 'image_id');
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
            'project_id' => $this->project_id,
            'image_id' => $this->image_id,
            'volume' => $this->volume,
            'account_id' => $this->account_id,
            'position' => $this->position,
            'second' => $this->second,
            'type' => $this->type,
        ];
    }

}
