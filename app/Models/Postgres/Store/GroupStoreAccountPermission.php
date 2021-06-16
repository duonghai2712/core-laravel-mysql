<?php namespace App\Models\Postgres\Store;
use App\Models\Base;


class GroupStoreAccountPermission extends Base
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'group_store_account_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'action',
        'project_id',
        'store_id',
        'permission_id',
        'group_store_account_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\Postgres\Store\GroupStoreAccountPermissionPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Store\GroupStoreAccountPermissionObserver);
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
            'action' => $this->action,
            'project_id' => $this->project_id,
            'store_id' => $this->store_id,
            'permission_id' => $this->permission_id,
            'group_store_account_id' => $this->group_store_account_id,
        ];
    }

}
