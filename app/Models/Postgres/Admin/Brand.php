<?php namespace App\Models\Postgres\Admin;



use App\Models\Base;
use App\Models\Postgres\Admin\SubBrand;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Nicolaslopezj\Searchable\SearchableTrait;

class Brand extends Base
{

    use SoftDeletes;
    use Notifiable;
    use SearchableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'brands';

    protected $searchable = [
        'columns' => [
            'brands.name' => 10,
        ]
    ];
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
        'description',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['laravel_through_key'];

    protected $dates  = ['deleted_at'];

    protected $presenter = \App\Presenters\Postgres\Admin\BrandPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Admin\BrandObserver);
    }

    // Relations
    public function subBrands(){
        return $this->hasMany(SubBrand::class,'brand_id');
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
            'account_id' => $this->account_id,
            'project_id' => $this->project_id,
            'description' => $this->description,
        ];
    }

}
