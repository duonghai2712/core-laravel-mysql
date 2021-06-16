<?php namespace App\Models\Postgres\Store;
use App\Models\Base;
use Illuminate\Database\Eloquent\SoftDeletes;


class Permission extends Base
{

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    const ACTION = [1, 2, 3, 4];//1 là xem, 2 là thêm, 3 là sửa, 4 là xóa
    const VIEW = 1;
    const ADD = 2;
    const UPDATE = 3;
    const DELETE = 4;

    const IS_ENABLE = 1;
    const DISABLE = 0;

    const LIST_PERMISSION = [
        'App\Http\Controllers\Api\Postgres\Store\ApiDashboardStoreController' => 'dashboard',
//        'App\Http\Controllers\Api\Postgres\Store\ApiGroupStoreAccountController' => 'group-store-account',
//        'App\Http\Controllers\Api\Postgres\Store\ApiStoreAccountController' => 'store-account',
//        'App\Http\Controllers\Api\Postgres\Store\ApiProfileStoreAccountController' => 'store-account',
        'App\Http\Controllers\Api\Postgres\Store\ApiCollectionController' => 'collection-management',
        'App\Http\Controllers\Api\Postgres\Store\ApiDeviceController' => 'device-management',
        'App\Http\Controllers\Api\Postgres\Store\ApiInfoStoreController' => 'store-info',
        'App\Http\Controllers\Api\Postgres\Store\ApiOrderController' => 'cross-order',
        'App\Http\Controllers\Api\Postgres\Store\ApiOrderCrossController' => 'customer-cross-order'
    ];


    const LIST_VIEW = ['index', 'mediaPlaybackStatistics', 'getListStorePlayAds', 'getListBranchPlayAds', 'allBranchesOfStore', 'detail', 'allGroupStoreAccounts', 'allPermissions', 'statistic', 'detailStore', 'sidebarInfo', 'detailBranch', 'logOperation', 'logPoint', 'listRank', 'listBrand', 'listStore', 'listDevice', 'listStoreInDetail', 'infoStoreAndBranchInOrder', 'listBranchInDetail', 'listCollectionInDetail', 'listDeviceInDetail', 'listCollectionOrder', 'infoStoreAndBranchInCrossOrder','listBranchInDetailCross', 'listCollectionInDetailCross', 'listDeviceInDetailCross', 'infoBranchOrStore'];
    const LIST_ADD = ['create', 'addCollection', 'saveOrder'];
    const LIST_UPDATE = ['update', 'changeMakeAds', 'blockAds', 'updateCollection', 'updateStore', 'updateBranch', 'updateCollectionOrder'];
    const LIST_DELETE = ['delete', 'deleteCollection'];

    const LIST_WITHOUT = ['signOut', 'resetPassword', 'createNewPassword', 'updateNewPassword'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $dates  = ['deleted_at'];

    protected $presenter = \App\Presenters\Postgres\Store\PermissionPresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Store\PermissionObserver);
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
        ];
    }

}
