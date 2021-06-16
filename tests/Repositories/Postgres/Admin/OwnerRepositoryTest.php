<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use App\Models\Postgres\Admin\Owner;
use Tests\TestCase;

class OwnerRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Admin\OwnerRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\OwnerRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $owners = factory(Owner::class, 3)->create();
        $ownerIds = $owners->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\OwnerRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\OwnerRepositoryInterface::class);
        $this->assertNotNull($repository);

        $ownersCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(Owner::class, $ownersCheck[0]);

        $ownersCheck = $repository->getByIds($ownerIds);
        $this->assertEquals(3, count($ownersCheck));
    }

    public function testFind()
    {
        $owners = factory(Owner::class, 3)->create();
        $ownerIds = $owners->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\OwnerRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\OwnerRepositoryInterface::class);
        $this->assertNotNull($repository);

        $ownerCheck = $repository->find($ownerIds[0]);
        $this->assertEquals($ownerIds[0], $ownerCheck->id);
    }

    public function testCreate()
    {
        $ownerData = factory(Owner::class)->make();

        /** @var  \App\Repositories\Postgres\Admin\OwnerRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\OwnerRepositoryInterface::class);
        $this->assertNotNull($repository);

        $ownerCheck = $repository->create($ownerData->toFillableArray());
        $this->assertNotNull($ownerCheck);
    }

    public function testUpdate()
    {
        $ownerData = factory(Owner::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\OwnerRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\OwnerRepositoryInterface::class);
        $this->assertNotNull($repository);

        $ownerCheck = $repository->update($ownerData, $ownerData->toFillableArray());
        $this->assertNotNull($ownerCheck);
    }

    public function testDelete()
    {
        $ownerData = factory(Owner::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\OwnerRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\OwnerRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($ownerData);

        $ownerCheck = $repository->find($ownerData->id);
        $this->assertNull($ownerCheck);
    }

}
