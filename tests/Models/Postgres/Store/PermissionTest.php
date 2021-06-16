<?php namespace tests\models\Postgres\Store;

use App\Models\Postgres\Store\Permission;
use Tests\TestCase;

class PermissionTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Store\Permission $permission */
        $permission = new Permission();
        $this->assertNotNull($permission);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Store\Permission $permission */
        $permissionModel = new Permission();

        $permissionData = factory(Permission::class)->make();
        foreach( $permissionData->toFillableArray() as $key => $value ) {
            $permissionModel->$key = $value;
        }
        $permissionModel->save();

        $this->assertNotNull(Permission::find($permissionModel->id));
    }

}
