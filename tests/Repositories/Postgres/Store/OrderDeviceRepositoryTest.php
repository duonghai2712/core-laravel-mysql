<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Models\Postgres\Store\OrderDevice;
use Tests\TestCase;

class OrderDeviceRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Store\OrderDeviceRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderDeviceRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $orderDevices = factory(OrderDevice::class, 3)->create();
        $orderDeviceIds = $orderDevices->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\OrderDeviceRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderDeviceRepositoryInterface::class);
        $this->assertNotNull($repository);

        $orderDevicesCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(OrderDevice::class, $orderDevicesCheck[0]);

        $orderDevicesCheck = $repository->getByIds($orderDeviceIds);
        $this->assertEquals(3, count($orderDevicesCheck));
    }

    public function testFind()
    {
        $orderDevices = factory(OrderDevice::class, 3)->create();
        $orderDeviceIds = $orderDevices->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\OrderDeviceRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderDeviceRepositoryInterface::class);
        $this->assertNotNull($repository);

        $orderDeviceCheck = $repository->find($orderDeviceIds[0]);
        $this->assertEquals($orderDeviceIds[0], $orderDeviceCheck->id);
    }

    public function testCreate()
    {
        $orderDeviceData = factory(OrderDevice::class)->make();

        /** @var  \App\Repositories\Postgres\Store\OrderDeviceRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderDeviceRepositoryInterface::class);
        $this->assertNotNull($repository);

        $orderDeviceCheck = $repository->create($orderDeviceData->toFillableArray());
        $this->assertNotNull($orderDeviceCheck);
    }

    public function testUpdate()
    {
        $orderDeviceData = factory(OrderDevice::class)->create();

        /** @var  \App\Repositories\Postgres\Store\OrderDeviceRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderDeviceRepositoryInterface::class);
        $this->assertNotNull($repository);

        $orderDeviceCheck = $repository->update($orderDeviceData, $orderDeviceData->toFillableArray());
        $this->assertNotNull($orderDeviceCheck);
    }

    public function testDelete()
    {
        $orderDeviceData = factory(OrderDevice::class)->create();

        /** @var  \App\Repositories\Postgres\Store\OrderDeviceRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderDeviceRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($orderDeviceData);

        $orderDeviceCheck = $repository->find($orderDeviceData->id);
        $this->assertNull($orderDeviceCheck);
    }

}
