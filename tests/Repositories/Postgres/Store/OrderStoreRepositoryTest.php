<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Models\Postgres\Store\OrderStore;
use Tests\TestCase;

class OrderStoreRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Store\OrderStoreRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderStoreRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $orderStores = factory(OrderStore::class, 3)->create();
        $orderStoreIds = $orderStores->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\OrderStoreRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderStoreRepositoryInterface::class);
        $this->assertNotNull($repository);

        $orderStoresCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(OrderStore::class, $orderStoresCheck[0]);

        $orderStoresCheck = $repository->getByIds($orderStoreIds);
        $this->assertEquals(3, count($orderStoresCheck));
    }

    public function testFind()
    {
        $orderStores = factory(OrderStore::class, 3)->create();
        $orderStoreIds = $orderStores->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\OrderStoreRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderStoreRepositoryInterface::class);
        $this->assertNotNull($repository);

        $orderStoreCheck = $repository->find($orderStoreIds[0]);
        $this->assertEquals($orderStoreIds[0], $orderStoreCheck->id);
    }

    public function testCreate()
    {
        $orderStoreData = factory(OrderStore::class)->make();

        /** @var  \App\Repositories\Postgres\Store\OrderStoreRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderStoreRepositoryInterface::class);
        $this->assertNotNull($repository);

        $orderStoreCheck = $repository->create($orderStoreData->toFillableArray());
        $this->assertNotNull($orderStoreCheck);
    }

    public function testUpdate()
    {
        $orderStoreData = factory(OrderStore::class)->create();

        /** @var  \App\Repositories\Postgres\Store\OrderStoreRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderStoreRepositoryInterface::class);
        $this->assertNotNull($repository);

        $orderStoreCheck = $repository->update($orderStoreData, $orderStoreData->toFillableArray());
        $this->assertNotNull($orderStoreCheck);
    }

    public function testDelete()
    {
        $orderStoreData = factory(OrderStore::class)->create();

        /** @var  \App\Repositories\Postgres\Store\OrderStoreRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderStoreRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($orderStoreData);

        $orderStoreCheck = $repository->find($orderStoreData->id);
        $this->assertNull($orderStoreCheck);
    }

}
