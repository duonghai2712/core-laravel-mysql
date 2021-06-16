<?php namespace App\Models\Postgres\Store;
use App\Models\Base;


class LogOperation extends Base
{



    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'log_operations';

    protected $searchable = [
        'columns' => [
            'log_operations.name' => 10,
        ]
    ];

    const ARR_NAME_LOG = [
        'create_group_account' => 'Tạo nhóm tài khoản.',
        'update_group_account' => 'Chỉnh sửa nhóm tài khoản.',
        'create_account' => 'Tạo tài khoản.',
        'update_account' => 'Sửa tài khoản.',
        'create_collection' => 'Thêm mới ảnh/video vào bộ sưu tập.',
        'delete_collection' => 'Xóa ảnh/video khỏi bộ sưu tập',
        'book_order' => 'Đặt quảng cáo chéo.',
        'update_book_order' => 'Sửa quảng cáo chéo.',
        'update_media_store' => 'Chỉnh sửa media',
        'delete_media_store' => 'Xóa media',
        'update_media_store_cross' => 'Chỉnh sửa media quảng cáo chéo'
    ];

    const ARR_DESCRIPTION_LOG = [
        'create_group_account' => ' đã tạo nhóm tài khoản ',
        'update_group_account' => ' đã chỉnh sửa nhóm tài khoản ',
        'create_account' => ' đã tạo tài khoản ',
        'update_account' => ' đã chỉnh sửa tài khoản ',
        'create_collection' => ' thêm mới ảnh/video.',
        'delete_collection' => ' đã xóa ảnh/video.',
        'book_order' => ' đã đặt đơn hàng quảng cáo chéo ',
        'update_book_order' => ' đã sửa đơn hàng quảng cáo chéo ',
        'delete_media_store' => ' đã xóa media trong thiết bị ',
        'update_media_store' => ' đã chỉnh sửa media trong thiết bị ',
        'update_media_store_cross' => ' đã chỉnh sửa media trong quảng cáo chéo của thiết bị '
    ];

    const ARR_DESCRIPTION_TITLE = 'Tài khoản ';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'store_id',
        'branch_id',
        'device_id',
        'description',
        'store_account_id',
        'project_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = [];

    protected $presenter = \App\Presenters\Postgres\Store\LogOperationPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Store\LogOperationObserver);
    }

    // Relations


    // Utility Functions
    public function storeAccount(){
        return $this->hasOne(StoreAccount::class,'id', 'store_account_id');
    }
    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'store_id' => $this->store_id,
            'branch_id' => $this->branch_id,
            'device_id' => $this->device_id,
            'name' => $this->name,
            'description' => $this->description,
            'store_account_id' => $this->store_account_id,
            'project_id' => $this->project_id,
        ];
    }

}
