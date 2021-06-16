<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Models\Postgres\Store\GroupStoreAccountPermission;
use Tests\TestCase;

class GroupStoreAccountPermissionRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Store\GroupStoreAccountPermissionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\GroupStoreAccountPermissionRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $groupStoreAccountPermissions = factory(GroupStoreAccountPermission::class, 3)->create();
        $groupStoreAccountPermissionIds = $groupStoreAccountPermissions->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\GroupStoreAccountPermissionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\GroupStoreAccountPermissionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $groupStoreAccountPermissionsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(GroupStoreAccountPermission::class, $groupStoreAccountPermissionsCheck[0]);

        $groupStoreAccountPermissionsCheck = $repository->getByIds($groupStoreAccountPermissionIds);
        $this->assertEquals(3, count($groupStoreAccountPermissionsCheck));
    }

    public function testFind()
    {
        $groupStoreAccountPermissions = factory(GroupStoreAccountPermission::class, 3)->create();
        $groupStoreAccountPermissionIds = $groupStoreAccountPermissions->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\GroupStoreAccountPermissionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\GroupStoreAccountPermissionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $groupStoreAccountPermissionCheck = $repository->find($groupStoreAccountPermissionIds[0]);
        $this->assertEquals($groupStoreAccountPermissionIds[0], $groupStoreAccountPermissionCheck->id);
    }

    public function testCreate()
    {
        $groupStoreAccountPermissionData = factory(GroupStoreAccountPermission::class)->make();

        /** @var  \App\Repositories\Postgres\Store\GroupStoreAccountPermissionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\GroupStoreAccountPermissionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $groupStoreAccountPermissionCheck = $repository->create($groupStoreAccountPermissionData->toFillableArray());
        $this->assertNotNull($groupStoreAccountPermissionCheck);
    }

    public function testUpdate()
    {
        $groupStoreAccountPermissionData = factory(GroupStoreAccountPermission::class)->create();

        /** @var  \App\Repositories\Postgres\Store\GroupStoreAccountPermissionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\GroupStoreAccountPermissionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $groupStoreAccountPermissionCheck = $repository->update($groupStoreAccountPermissionData, $groupStoreAccountPermissionData->toFillableArray());
        $this->assertNotNull($groupStoreAccountPermissionCheck);
    }

    public function testDelete()
    {
        $groupStoreAccountPermissionData = factory(GroupStoreAccountPermission::class)->create();

        /** @var  \App\Repositories\Postgres\Store\GroupStoreAccountPermissionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\GroupStoreAccountPermissionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($groupStoreAccountPermissionData);

        $groupStoreAccountPermissionCheck = $repository->find($groupStoreAccountPermissionData->id);
        $this->assertNull($groupStoreAccountPermissionCheck);
    }

}
