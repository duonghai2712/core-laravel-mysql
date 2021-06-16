<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Models\Postgres\Store\Permission;
use Tests\TestCase;

class PermissionRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Store\PermissionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\PermissionRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $permissions = factory(Permission::class, 3)->create();
        $permissionIds = $permissions->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\PermissionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\PermissionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $permissionsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(Permission::class, $permissionsCheck[0]);

        $permissionsCheck = $repository->getByIds($permissionIds);
        $this->assertEquals(3, count($permissionsCheck));
    }

    public function testFind()
    {
        $permissions = factory(Permission::class, 3)->create();
        $permissionIds = $permissions->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\PermissionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\PermissionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $permissionCheck = $repository->find($permissionIds[0]);
        $this->assertEquals($permissionIds[0], $permissionCheck->id);
    }

    public function testCreate()
    {
        $permissionData = factory(Permission::class)->make();

        /** @var  \App\Repositories\Postgres\Store\PermissionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\PermissionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $permissionCheck = $repository->create($permissionData->toFillableArray());
        $this->assertNotNull($permissionCheck);
    }

    public function testUpdate()
    {
        $permissionData = factory(Permission::class)->create();

        /** @var  \App\Repositories\Postgres\Store\PermissionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\PermissionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $permissionCheck = $repository->update($permissionData, $permissionData->toFillableArray());
        $this->assertNotNull($permissionCheck);
    }

    public function testDelete()
    {
        $permissionData = factory(Permission::class)->create();

        /** @var  \App\Repositories\Postgres\Store\PermissionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\PermissionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($permissionData);

        $permissionCheck = $repository->find($permissionData->id);
        $this->assertNull($permissionCheck);
    }

}
