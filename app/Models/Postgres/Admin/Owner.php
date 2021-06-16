<?php namespace App\Models\Postgres\Admin;
use App\Models\Base;
use App\Models\Postgres\Customer\CustomerAccount;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Nicolaslopezj\Searchable\SearchableTrait;


class Owner extends Base
{

    use SoftDeletes;
    use Notifiable;
    use SearchableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'owners';

    protected $searchable = [
        'columns' => [
            'owners.name' => 10,
        ]
    ];

    const VERSION_FIRST = 1;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'level',
        'customer_account_id',
        'account_id',
        'project_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\Postgres\Admin\OwnerPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Admin\OwnerObserver);
    }

    // Relations
    public function customerAccount()
    {
        return $this->hasOne(CustomerAccount::class,'id', 'customer_account_id');
    }

    public function images(){
        return $this->hasMany(Image::class,'owner_id');
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
            'level' => $this->level,
            'customer_account_id' => $this->customer_account_id,
            'account_id' => $this->account_id,
            'project_id' => $this->project_id,
        ];
    }

}
