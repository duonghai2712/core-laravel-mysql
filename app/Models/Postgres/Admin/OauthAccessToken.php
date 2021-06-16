<?php namespace App\Models\Postgres\Admin;


use App\Models\Base;
use App\Models\Postgres\User;

class OauthAccessToken extends Base
{



    /**
     * The database table used by the model.
     *
     * @var string
     */
//    protected $table = 'oauth_access_tokens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'client_id',
        'name',
        'scopes',
        'revoked',
        'expires_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = ['expires_at'];


    // Relations
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function client()
    {
//        return $this->belongsTo(\App\Models\Client::class, 'client_id', 'id');
    }



}
