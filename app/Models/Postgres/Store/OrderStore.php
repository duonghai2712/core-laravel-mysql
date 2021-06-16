<?php namespace App\Models\Postgres\Store;
use App\Models\Base;
use App\Models\Postgres\Admin\Store;


class OrderStore extends Base
{



    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_stores';

    const STATUS_USING = 1;
    const STATUS_DELETE = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'point',
        'status',
        'real_point',
        'order_id',
        'store_id',
        'project_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\Postgres\Store\OrderStorePresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Store\OrderStoreObserver);
    }

    // Relations
    public function store(){
        return $this->hasOne(Store::class,'id', 'store_id');
    }

    public function order(){
        return $this->hasOne(Store::class,'id', 'order_id');
    }

    public function branches(){
        return $this->hasMany(OrderBranch::class,'order_store_id');
    }

    // Utility Functions

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'point' => $this->point,
            'status' => $this->status,
            'real_point' => $this->real_point,
            'order_id' => $this->order_id,
            'store_id' => $this->store_id,
            'project_id' => $this->project_id,
        ];
    }

}
