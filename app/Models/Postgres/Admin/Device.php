<?php namespace App\Models\Postgres\Admin;
use App\Models\AuthenticationBase;
use App\Models\Postgres\Admin\Branch;
use App\Models\Postgres\Admin\Store;
use App\Models\Postgres\Store\Collection;
use App\Models\Postgres\Store\StoreDeviceCollection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Nicolaslopezj\Searchable\SearchableTrait;

class Device extends AuthenticationBase
{

    use SoftDeletes;
    use Notifiable;
    use SearchableTrait;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'devices';

    protected $searchable = [
        'columns' => [
            'devices.name' => 1,
            'devices.device_code' => 2,
        ]
    ];

    const DEVICE_LOCK = 'Thiết bị đã bị khóa';
    const PLAY = 'Vẫn phát bình thường.';
    const STOP_PLAY = 'Đã bị dừng phát.';
    const DEVICE_UNLOCK = 'Thiết bị được mở khóa';

    const HAS_DEVICE = 1;
    const NO_DEVICE = 0;

    const IS_DISABLE = 0;
    const IS_ACTIVE = 1;

    const CONNECT = 1;
    const DISCONNECT = 0;

    const VIEW_CONNECT = 1;
    const VIEW_DISCONNECT = 2;
    const VIEW_NOT_USE = 3;

    const BLOCK_TIME = 180;

    const BLOCK_ADS_TIME = 60;
    const TOTAL_TIME_EMPTY = 600;

    const ARRAY_OWN = [1, 2];
    const OWNER = 1;
    const STORE_OWNER = 2;

    const  TOTAL_TIME_ADMIN = 'total_time_admin';
    const  TOTAL_TIME_STORE = 'total_time_store';
    const  BLOCK_ADS = 'block_ads';

    const MAX_TIME_ADMIN = 420;
    const MAX_TIME_STORE = 180;

    const OWN_DOWNLOAD_ANT = 1;
    const OWN_DOWNLOAD_STORE = 2;
    const OWN_DOWNLOAD_STORE_CROSS = 3;

    const NONE = 0;

    const EVENT_CHANGE_MEDIA = 1;
    const EVENT_DELETE_DEVICE = 2;
    const EVENT_BLOCK_DEVICE = 3;

    const TYPE_ADD = 1;
    const TYPE_UPDATE = 2;

    const POINT_TEN = 10;
    const POINT_FIFTEEN = 15;
    const POINT_THIRTY = 30;

    const LIMIT_MESSAGE_QUEUE = 1;

    const BLOCK_DEVICE = 1;
    const OPEN_DEVICE = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'own',
        'is_active',
        'status',
        'device_code',
        'active_code',
        'device_token',
        'account_id',
        'project_id',
        'map',
        'model',
        'width',
        'height',
        'size',
        'os',
        'store_id',
        'block_ads',
        'total_time_admin',
        'total_time_store',
        'total_time_empty',
        'branch_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = ['deleted_at'];

    protected $presenter = \App\Presenters\Postgres\Admin\DevicePresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Admin\DeviceObserver);
    }

    // Relations
    public function store(){
        return $this->hasOne(Store::class,'id', 'store_id');
    }

    public function branch(){
        return $this->hasOne(Branch::class,'id', 'branch_id');
    }

    public function storeWithTrashed(){
        return $this->hasOne(Store::class,'id', 'store_id')->withTrashed();
    }

    public function branchWithTrashed(){
        return $this->hasOne(Branch::class,'id', 'branch_id')->withTrashed();
    }

    public function storeCollection(){
        return $this->hasManyThrough(Collection::class, StoreDeviceCollection::class,'device_id','id','id','collection_id');
    }

    public function adminImage(){
        return $this->hasManyThrough(Image::class, AdminDeviceImage::class,'device_id','id','id','image_id');
    }

    public function collections(){
        return $this->hasMany(StoreDeviceCollection::class,'device_id');
    }

    // Utility Functions

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'own' => $this->own,
            'status' => $this->status,
            'device_code' => $this->device_code,
            'device_token' => $this->device_token,
            'account_id' => $this->account_id,
            'project_id' => $this->project_id,
            'store_id' => $this->store_id,
            'branch_id' => $this->branch_id,
            'is_active' => $this->is_active,
            'map' => $this->map,
            'model' => $this->model,
            'width' => $this->width,
            'height' => $this->height,
            'size' => $this->size,
            'os' => $this->os,
            'active_code' => $this->active_code,
            'total_time_admin' => $this->total_time_admin,
            'total_time_store' => $this->total_time_store,
            'total_time_empty' => $this->total_time_empty,
            'block_ads' => $this->block_ads,
        ];
    }

}
