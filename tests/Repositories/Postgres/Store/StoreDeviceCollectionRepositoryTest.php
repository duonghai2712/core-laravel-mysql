<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Models\Postgres\Store\StoreDeviceCollection;
use Tests\TestCase;

class StoreDeviceCollectionRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Store\StoreDeviceCollectionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreDeviceCollectionRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $storeDeviceCollections = factory(StoreDeviceCollection::class, 3)->create();
        $storeDeviceCollectionIds = $storeDeviceCollections->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\StoreDeviceCollectionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreDeviceCollectionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeDeviceCollectionsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(StoreDeviceCollection::class, $storeDeviceCollectionsCheck[0]);

        $storeDeviceCollectionsCheck = $repository->getByIds($storeDeviceCollectionIds);
        $this->assertEquals(3, count($storeDeviceCollectionsCheck));
    }

    public function testFind()
    {
        $storeDeviceCollections = factory(StoreDeviceCollection::class, 3)->create();
        $storeDeviceCollectionIds = $storeDeviceCollections->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\StoreDeviceCollectionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreDeviceCollectionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeDeviceCollectionCheck = $repository->find($storeDeviceCollectionIds[0]);
        $this->assertEquals($storeDeviceCollectionIds[0], $storeDeviceCollectionCheck->id);
    }

    public function testCreate()
    {
        $storeDeviceCollectionData = factory(StoreDeviceCollection::class)->make();

        /** @var  \App\Repositories\Postgres\Store\StoreDeviceCollectionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreDeviceCollectionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeDeviceCollectionCheck = $repository->create($storeDeviceCollectionData->toFillableArray());
        $this->assertNotNull($storeDeviceCollectionCheck);
    }

    public function testUpdate()
    {
        $storeDeviceCollectionData = factory(StoreDeviceCollection::class)->create();

        /** @var  \App\Repositories\Postgres\Store\StoreDeviceCollectionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreDeviceCollectionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeDeviceCollectionCheck = $repository->update($storeDeviceCollectionData, $storeDeviceCollectionData->toFillableArray());
        $this->assertNotNull($storeDeviceCollectionCheck);
    }

    public function testDelete()
    {
        $storeDeviceCollectionData = factory(StoreDeviceCollection::class)->create();

        /** @var  \App\Repositories\Postgres\Store\StoreDeviceCollectionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreDeviceCollectionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($storeDeviceCollectionData);

        $storeDeviceCollectionCheck = $repository->find($storeDeviceCollectionData->id);
        $this->assertNull($storeDeviceCollectionCheck);
    }

}
