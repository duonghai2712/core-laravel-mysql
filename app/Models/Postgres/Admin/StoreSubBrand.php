<?php namespace App\Models\Postgres\Admin;


use App\Models\Base;

class StoreSubBrand extends Base
{



    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'store_sub_brands';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'account_id',
        'store_id',
        'sub_brand_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\Postgres\Admin\StoreSubBrandPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Admin\StoreSubBrandObserver);
    }

    // Relations


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
            'store_id' => $this->store_id,
            'sub_brand_id' => $this->sub_brand_id,
        ];
    }

}
