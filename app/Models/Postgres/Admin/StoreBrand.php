<?php namespace App\Models\Postgres\Admin;

use App\Models\Base;
use App\Models\Postgres\Admin\Account;
use App\Models\Postgres\Admin\Brand;
use App\Models\Postgres\Admin\Project;

class StoreBrand extends Base
{



    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'store_brands';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'account_id',
        'brand_id',
        'store_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\Postgres\Admin\StoreBrandPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Admin\StoreBrandObserver);
    }

    // Relations
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }



    // Utility Functions

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'account_id' => $this->account_id,
            'brand_id' => $this->brand_id,
            'store_id' => $this->store_id
        ];
    }

}
