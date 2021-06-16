<?php namespace Tests\Repositories\Postgres\Admin;

use App\Models\Postgres\Admin\Store;
use Tests\TestCase;

class StoreRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Admin\StoreRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\StoreRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $stores = factory(Store::class, 3)->create();
        $storeIds = $stores->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\StoreRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\StoreRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storesCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(Store::class, $storesCheck[0]);

        $storesCheck = $repository->getByIds($storeIds);
        $this->assertEquals(3, count($storesCheck));
    }

    public function testFind()
    {
        $stores = factory(Store::class, 3)->create();
        $storeIds = $stores->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\StoreRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\StoreRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeCheck = $repository->find($storeIds[0]);
        $this->assertEquals($storeIds[0], $storeCheck->id);
    }

    public function testCreate()
    {
        $storeData = factory(Store::class)->make();

        /** @var  \App\Repositories\Postgres\Admin\StoreRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\StoreRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeCheck = $repository->create($storeData->toFillableArray());
        $this->assertNotNull($storeCheck);
    }

    public function testUpdate()
    {
        $storeData = factory(Store::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\StoreRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\StoreRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeCheck = $repository->update($storeData, $storeData->toFillableArray());
        $this->assertNotNull($storeCheck);
    }

    public function testDelete()
    {
        $storeData = factory(Store::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\StoreRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\StoreRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($storeData);

        $storeCheck = $repository->find($storeData->id);
        $this->assertNull($storeCheck);
    }

}
