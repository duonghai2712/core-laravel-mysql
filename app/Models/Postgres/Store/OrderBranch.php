<?php namespace App\Models\Postgres\Store;
use App\Models\Base;
use App\Models\Postgres\Admin\Branch;
use App\Models\Postgres\Admin\Rank;


class OrderBranch extends Base
{



    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_branches';

    const STATUS_USING = 1;
    const STATUS_DELETE = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rank',
        'point',
        'status',
        'order_id',
        'real_point',
        'rank_id',
        'branch_id',
        'order_store_id',
        'project_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\Postgres\Store\OrderBranchPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Store\OrderBranchObserver);
    }

    // Relations


    // Utility Functions
    public function branch(){
        return $this->hasOne(Branch::class,'id', 'branch_id');
    }

    public function rank(){
        return $this->hasOne(Rank::class,'id', 'rank_id');
    }

    public function order(){
        return $this->hasOne(Order::class,'id', 'order_id');
    }

    public function orderStore(){
        return $this->hasOne(OrderStore::class,'id', 'order_store_id');
    }

    public function devices(){
        return $this->hasMany(OrderDevice::class,'order_branch_id');
    }
    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'rank' => $this->rank,
            'point' => $this->point,
            'status' => $this->status,
            'real_point' => $this->real_point,
            'rank_id' => $this->rank_id,
            'order_id' => $this->order_id,
            'branch_id' => $this->branch_id,
            'order_store_id' => $this->order_store_id,
            'project_id' => $this->project_id,
        ];
    }

}
