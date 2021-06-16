<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use App\Models\Postgres\Admin\DeviceLoadingStatus;
use Tests\TestCase;

class DeviceLoadingStatusRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Admin\DeviceLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\DeviceLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $deviceLoadingStatuses = factory(DeviceLoadingStatus::class, 3)->create();
        $deviceLoadingStatusIds = $deviceLoadingStatuses->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\DeviceLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\DeviceLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);

        $deviceLoadingStatusesCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(DeviceLoadingStatus::class, $deviceLoadingStatusesCheck[0]);

        $deviceLoadingStatusesCheck = $repository->getByIds($deviceLoadingStatusIds);
        $this->assertEquals(3, count($deviceLoadingStatusesCheck));
    }

    public function testFind()
    {
        $deviceLoadingStatuses = factory(DeviceLoadingStatus::class, 3)->create();
        $deviceLoadingStatusIds = $deviceLoadingStatuses->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\DeviceLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\DeviceLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);

        $deviceLoadingStatusCheck = $repository->find($deviceLoadingStatusIds[0]);
        $this->assertEquals($deviceLoadingStatusIds[0], $deviceLoadingStatusCheck->id);
    }

    public function testCreate()
    {
        $deviceLoadingStatusData = factory(DeviceLoadingStatus::class)->make();

        /** @var  \App\Repositories\Postgres\Admin\DeviceLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\DeviceLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);

        $deviceLoadingStatusCheck = $repository->create($deviceLoadingStatusData->toFillableArray());
        $this->assertNotNull($deviceLoadingStatusCheck);
    }

    public function testUpdate()
    {
        $deviceLoadingStatusData = factory(DeviceLoadingStatus::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\DeviceLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\DeviceLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);

        $deviceLoadingStatusCheck = $repository->update($deviceLoadingStatusData, $deviceLoadingStatusData->toFillableArray());
        $this->assertNotNull($deviceLoadingStatusCheck);
    }

    public function testDelete()
    {
        $deviceLoadingStatusData = factory(DeviceLoadingStatus::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\DeviceLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\DeviceLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($deviceLoadingStatusData);

        $deviceLoadingStatusCheck = $repository->find($deviceLoadingStatusData->id);
        $this->assertNull($deviceLoadingStatusCheck);
    }

}
