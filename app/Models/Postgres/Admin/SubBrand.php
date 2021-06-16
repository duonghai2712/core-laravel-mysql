<?php namespace App\Models\Postgres\Admin;


use App\Models\Base;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubBrand extends Base
{

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sub_brands';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'account_id',
        'project_id',
        'brand_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['laravel_through_key'];

    protected $dates  = ['deleted_at'];

    protected $presenter = \App\Presenters\Postgres\Admin\SubBrandPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Admin\SubBrandObserver);
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
            'name' => $this->name,
            'slug' => $this->slug,
            'account_id' => $this->account_id,
            'project_id' => $this->project_id,
            'brand_id' => $this->brand_id,
        ];
    }

}
