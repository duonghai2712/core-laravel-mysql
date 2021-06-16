<?php namespace App\Models\Postgres\Store;
use App\Models\AuthenticationBase;
use App\Models\Base;
use App\Models\Postgres\Admin\Branch;
use App\Models\Postgres\Admin\Store;
use App\Models\Postgres\Admin\Image;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Nicolaslopezj\Searchable\SearchableTrait;


class StoreAccount extends AuthenticationBase
{
    use SoftDeletes;
    use Notifiable;
    use SearchableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'store_accounts';

    protected $searchable = [
        'columns' => [
            'store_accounts.username' => 10,
        ]
    ];

    const ADMIN = 1;
    const SUB = 2;
    const STORE = 'store';

    const KEY_CACHE = '|';

    const IS_ACTIVE = 1;

    const MAKE_ADS_TRUE = 2;
    const MAKE_ADS_FALSE = 1;


    const STRING_REDIS = '__thany';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'representative',
        'email',
        'role',
        'phone_number',
        'is_active',
        'username',
        'password',
        'language',
        'is_send_email',
        'group_store_account_id',
        'store_id',
        'branch_id',
        'profile_collection_id',
        'remember_token',
        'api_access_token',
        'account_id',
        'project_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'facebook_token'];

    protected $dates  = ['deleted_at'];

    protected $presenter = \App\Presenters\Postgres\Store\StoreAccountPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Store\StoreAccountObserver);
    }

    // Relations
    public function profileStoreImage(){
        return $this->hasOne(Collection::class,'id', 'profile_collection_id');
    }

    public function image(){
        return $this->hasOne(Collection::class,'id', 'profile_collection_id');
    }

    public function group(){
        return $this->hasOne(GroupStoreAccount::class,'id', 'group_store_account_id');
    }

    public function store(){
        return $this->hasOne(Store::class,'id', 'store_id');
    }

    public function branch(){
        return $this->hasOne(Branch::class,'id', 'branch_id');
    }

    // Utility Functions

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'representative' => $this->representative,
            'email' => $this->email,
            "is_active" => $this->is_active,
            "username" => $this->username,
            "role" => $this->role,
            "phone_number" => $this->phone_number,
            'language' => $this->language,
            'token' => $this->api_access_token,
            'avatar' => !empty($this->present()->profileStoreImage()->source) ? asset($this->present()->profileStoreImage()->source) : '',

        ];
    }

}
