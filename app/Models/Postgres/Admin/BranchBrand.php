<?php namespace App\Models\Postgres\Admin;



use App\Models\Base;

class BranchBrand extends Base
{



    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'branch_brands';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'account_id',
        'brand_id',
        'branch_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\Postgres\Admin\BranchBrandPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Admin\BranchBrandObserver);
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
            'brand_id' => $this->brand_id,
            'branch_id' => $this->branch_id,
        ];
    }

}
