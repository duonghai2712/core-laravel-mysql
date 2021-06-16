<?php

namespace App\Console\Commands;

use App\Elibs\eFunction;
use App\Repositories\Postgres\Store\PermissionRepositoryInterface;
use Illuminate\Console\Command;

class AddPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Permission';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        parent::__construct();
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->permissionRepository->deleteAllPermissionsByFilter(['deleted_at' => true]);
        $permissions = [
            [
                'text' => 'Dashboard',
                'key' => 'dashboard'
            ],
            [
                'text' => 'Nhóm tài khoản',
                'key' => 'group-store-account'
            ],
            [
                'text' => 'Tài khoản',
                'key' => 'store-account'
            ],
            [
                'text' => 'Quản lý bộ sưu tập',
                'key' => 'collection-management'
            ],
            [
                'text' => 'Quản lý thiết bị',
                'key' => 'device-management'
            ],
            [
                'text' => 'Thông tin cửa hàng',
                'key' => 'store-info'
            ],
            [
                'text' => 'Đơn hàng quảng cáo chéo',
                'key' => 'cross-order'
            ],
            [
                'text' => 'Khách hàng quảng cáo chéo',
                'key' => 'customer-cross-order'
            ]
        ];

        $arrPers = [];
        foreach ($permissions as $permission){
            $arrPers[] = [
                'name' => $permission['text'],
                'key' => $permission['key'],
                'slug' => eFunction::generateSlug($permission['text'], '-'),
                'created_at' => eFunction::getDateTimeNow(),
                'updated_at' => eFunction::getDateTimeNow()
            ];
        }

        $this->permissionRepository->createMulti($arrPers);
    }
}
