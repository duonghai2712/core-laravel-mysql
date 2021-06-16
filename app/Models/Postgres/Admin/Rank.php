<?php namespace App\Models\Postgres\Admin;

use App\Models\Base;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Nicolaslopezj\Searchable\SearchableTrait;

class Rank extends Base
{

    use SoftDeletes;
    use Notifiable;
    use SearchableTrait;

    const ARRAY_COEFFICIENT = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ranks';

    protected $searchable = [
        'columns' => [
            'ranks.name' => 10,
        ]
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'account_id',
        'project_id',
        'description',
        'coefficient',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = ['deleted_at'];

    protected $presenter = \App\Presenters\Postgres\Admin\RankPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Admin\RankObserver);
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
            'name' => $this->name,
            'slug' => $this->slug,
            'account_id' => $this->account_id,
            'project_id' => $this->project_id,
            'description' => $this->description,
            'coefficient' => $this->coefficient,
        ];
    }

}
