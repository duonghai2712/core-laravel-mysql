<?php namespace App\Models\Postgres\Admin;

use App\Models\AuthenticationBase;
use App\Models\Postgres\Admin\Account;
use App\Models\Postgres\Admin\Brand;
use App\Models\Postgres\District;
use App\Models\Postgres\Province;
use App\Models\Postgres\Store\StoreAccount;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Nicolaslopezj\Searchable\SearchableTrait;

class Store extends AuthenticationBase
{
    use SoftDeletes;
    use Notifiable;
    use SearchableTrait;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stores';

    protected $searchable = [
        'columns' => [
            'stores.name' => 10,
        ]
    ];

    const IS_ACTIVE = 1;

    const ROLE = [
      1 => [
          'key' => 1,
          'value' => 'Admin'
      ]
    ];

    const ARR_IS_ACTIVE = [0, 1];

    const CREATE = 'create';
    const UPDATE = 'update';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'address',
        'is_active',
        'district_id',
        'total_point',
        'debt_point',
        'current_point',
        'province_id',
        'account_id',
        'project_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = ['deleted_at'];

    protected $presenter = \App\Presenters\Postgres\Admin\StorePresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Admin\StoreObserver);
    }

    // Relations

    public function province(){
        return $this->hasOne(Province::class,'id', 'province_id');
    }

    public function district(){
        return $this->hasOne(District::class,'id', 'district_id');
    }

    public function account(){
        return $this->hasOne(StoreAccount::class,'store_id');
    }

    public function Branches(){
        return $this->hasMany(Branch::class,'store_id');
    }

    public function createdBy(){
        return $this->hasOne(Account::class,'id', 'account_id');
    }

    public function brands(){
        return $this->hasManyThrough(Brand::class, StoreBrand::class,'store_id','id','id','brand_id');
    }

    public function subBrands(){
        return $this->hasManyThrough(SubBrand::class, StoreSubBrand::class,'store_id','id','id','sub_brand_id');
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
            'debt_point' => $this->debt_point,
            'total_point' => $this->total_point,
            'current_point' => $this->current_point,
            "is_active" => $this->is_active,
            "address" => $this->address,
            "project_id" => $this->project_id,
            'province' => !empty($this->present()->province()) ? $this->present()->province()->name : '',
            'district' => !empty($this->present()->distrcit()) ? $this->present()->distrcit()->name : '',
        ];
    }

}
