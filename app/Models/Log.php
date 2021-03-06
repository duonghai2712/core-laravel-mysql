<?php namespace App\Models;



class Log extends Base
{



    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_name',
        'email',
        'action',
        'table',
        'record_id',
        'query',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = ['deleted_at'];

    protected $presenter = \App\Presenters\LogPresenter::class;

    // Relations


    // Utility Functions

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id'        => $this->id,
            'user_name' => $this->user_name,
            'email'     => $this->email,
            'action'    => $this->action,
            'table'     => $this->table,
            'record_id' => $this->record_id,
            'query'     => $this->query,
        ];
    }

}
