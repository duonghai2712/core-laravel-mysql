<?php namespace App\Models\Postgres\Store;
use App\Models\Base;


class ResetPasswordAccountStore extends Base
{

    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reset_password_account_stores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'token',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\Postgres\Store\ResetPasswordAccountStorePresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Store\ResetPasswordAccountStoreObserver);
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
            'email' => $this->email,
            'token' => $this->token,
        ];
    }

}
