<?php namespace App\Models\Postgres\Admin;

use App\Models\AuthenticationBase;
use App\Models\Postgres\Admin\Image;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends AuthenticationBase
{

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'accounts';

    const IS_ACTIVE = 1;
    const IS_DISABLE = 0;
    const ADMIN = 'admin';

    const RESET_PASSWORD = 'reset_password';

    const LANGUAGE = [
      'vi' => [
          'key' => 'vi',
          'value' => 'Việt Nam'
      ],
      'en' => [
          'key' => 'en',
          'value' => 'English'
      ]
    ];

    const RULE_ADMIN = 1;
    const
        RULE_STORE_OWNER = 2;
    const RULE_USER = 3;

    const RULE = [
        1 => [//Cho admin
          'ALL'
        ],
        2 => [//Cho chủ cửa hàng
            ''
        ],
        3 => [//Cho user

        ]
    ];

    const STRING_REDIS = '__ynhan';

    const KEY_CACHE = '|';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'language',
        'remember_token',
        'api_access_token',
        'profile_image_id',
        'last_notification_id',
        'project_id',
        'rule',
        'phone_number',
        'is_active',
        'is_send_email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'facebook_token'];

    protected $dates  = ['deleted_at'];

    protected $presenter = \App\Presenters\Postgres\Admin\AccountPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Admin\AccountObserver);
    }

    // Relations
    public function profileImage()
    {
        return $this->belongsTo(Image::class, 'profile_image_id', 'id');
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
            'email' => $this->email,
            "is_active" => $this->is_active,
            "username" => $this->username,
            "project_id" => $this->project_id,
            "phone_number" => $this->phone_number,
            'language' => $this->language,
            'token' => $this->api_access_token,
            'avatar' => !empty($this->present()->profileImage()->source) ? asset($this->present()->profileImage()->source) : '',
        ];
    }

}
