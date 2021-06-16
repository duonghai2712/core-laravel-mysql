<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Models\Postgres\Store\Order;
use Tests\TestCase;

class OrderRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Store\OrderRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $orders = factory(Order::class, 3)->create();
        $orderIds = $orders->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\OrderRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderRepositoryInterface::class);
        $this->assertNotNull($repository);

        $ordersCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(Order::class, $ordersCheck[0]);

        $ordersCheck = $repository->getByIds($orderIds);
        $this->assertEquals(3, count($ordersCheck));
    }

    public function testFind()
    {
        $orders = factory(Order::class, 3)->create();
        $orderIds = $orders->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\OrderRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderRepositoryInterface::class);
        $this->assertNotNull($repository);

        $orderCheck = $repository->find($orderIds[0]);
        $this->assertEquals($orderIds[0], $orderCheck->id);
    }

    public function testCreate()
    {
        $orderData = factory(Order::class)->make();

        /** @var  \App\Repositories\Postgres\Store\OrderRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderRepositoryInterface::class);
        $this->assertNotNull($repository);

        $orderCheck = $repository->create($orderData->toFillableArray());
        $this->assertNotNull($orderCheck);
    }

    public function testUpdate()
    {
        $orderData = factory(Order::class)->create();

        /** @var  \App\Repositories\Postgres\Store\OrderRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderRepositoryInterface::class);
        $this->assertNotNull($repository);

        $orderCheck = $repository->update($orderData, $orderData->toFillableArray());
        $this->assertNotNull($orderCheck);
    }

    public function testDelete()
    {
        $orderData = factory(Order::class)->create();

        /** @var  \App\Repositories\Postgres\Store\OrderRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($orderData);

        $orderCheck = $repository->find($orderData->id);
        $this->assertNull($orderCheck);
    }

}
