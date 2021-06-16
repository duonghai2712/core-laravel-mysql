<?php namespace App\Models\Postgres\Store;
use App\Models\Base;
use Illuminate\Database\Eloquent\SoftDeletes;


class GroupStoreAccount extends Base
{

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'group_store_accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'store_id',
        'store_account_id',
        'project_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = ['deleted_at'];

    protected $presenter = \App\Presenters\Postgres\Store\GroupStoreAccountPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Store\GroupStoreAccountObserver);
    }

    // Relations

    public function permissions(){
        return $this->hasMany(GroupStoreAccountPermission::class,'group_store_account_id');
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
            'store_account_id' => $this->store_account_id,
            'store_id' => $this->store_id,
            'project_id' => $this->project_id,
        ];
    }

}
