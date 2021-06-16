<?php namespace tests\models\Postgres\Store;

use App\Models\Postgres\Store\GroupStoreAccountPermission;
use Tests\TestCase;

class GroupStoreAccountPermissionTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Store\GroupStoreAccountPermission $groupStoreAccountPermission */
        $groupStoreAccountPermission = new GroupStoreAccountPermission();
        $this->assertNotNull($groupStoreAccountPermission);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Store\GroupStoreAccountPermission $groupStoreAccountPermission */
        $groupStoreAccountPermissionModel = new GroupStoreAccountPermission();

        $groupStoreAccountPermissionData = factory(GroupStoreAccountPermission::class)->make();
        foreach( $groupStoreAccountPermissionData->toFillableArray() as $key => $value ) {
            $groupStoreAccountPermissionModel->$key = $value;
        }
        $groupStoreAccountPermissionModel->save();

        $this->assertNotNull(GroupStoreAccountPermission::find($groupStoreAccountPermissionModel->id));
    }

}
