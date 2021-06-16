<?php namespace App\Models\Postgres\Admin;

use App\Models\Base;
use App\Models\Postgres\Admin\Account;
use App\Models\Postgres\District;
use App\Models\Postgres\Province;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Nicolaslopezj\Searchable\SearchableTrait;

class Branch extends Base
{

    use SoftDeletes;
    use Notifiable;
    use SearchableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'branches';

    protected $searchable = [
        'columns' => [
            'branches.name' => 10,
        ]
    ];

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
        'contact',
        'phone_number',
        'rank_id',
        'store_id',
        'debt_point',
        'address',
        'district_id',
        'province_id',
        'total_point',
        'current_point',
        'make_ads',
        'store_account_id',
        'account_id',
        'project_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['laravel_through_key'];

    protected $dates  = ['deleted_at'];

    protected $presenter = \App\Presenters\Postgres\Admin\BranchPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Admin\BranchObserver);
    }

    // Relations
    public function province(){
        return $this->hasOne(Province::class,'id', 'province_id');
    }

    public function district(){
        return $this->hasOne(District::class,'id', 'district_id');
    }

    public function store(){
        return $this->hasOne(Store::class,'id', 'store_id');
    }

    public function account(){
        return $this->hasOne(Account::class,'id', 'account_id');
    }

    public function rank(){
        return $this->hasOne(Rank::class,'id', 'rank_id');
    }

    public function brands(){
        return $this->hasManyThrough(Brand::class, BranchBrand::class,'branch_id','id','id','brand_id');
    }

    public function subBrands(){
        return $this->hasManyThrough(SubBrand::class, BranchSubBrand::class,'branch_id','id','id','sub_brand_id');
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
            'contact' => $this->contact,
            'debt_point' => $this->debt_point,
            'phone_number' => $this->phone_number,
            'rank_id' => $this->rank_id,
            'make_ads' => $this->make_ads,
            'store_account_id' => $this->store_account_id,
            'total_point' => $this->total_point,
            'current_point' => $this->current_point,
            'store_id' => $this->store_id,
            'address' => $this->address,
            'district_id' => $this->district_id,
            'province_id' => $this->province_id,
            'account_id' => $this->account_id,
            'project_id' => $this->project_id,
        ];
    }

}
