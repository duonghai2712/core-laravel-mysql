<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Models\Postgres\Store\StoreCrossDeviceCollection;
use Tests\TestCase;

class StoreCrossDeviceCollectionRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Store\StoreCrossDeviceCollectionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreCrossDeviceCollectionRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $storeCrossDeviceCollections = factory(StoreCrossDeviceCollection::class, 3)->create();
        $storeCrossDeviceCollectionIds = $storeCrossDeviceCollections->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\StoreCrossDeviceCollectionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreCrossDeviceCollectionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeCrossDeviceCollectionsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(StoreCrossDeviceCollection::class, $storeCrossDeviceCollectionsCheck[0]);

        $storeCrossDeviceCollectionsCheck = $repository->getByIds($storeCrossDeviceCollectionIds);
        $this->assertEquals(3, count($storeCrossDeviceCollectionsCheck));
    }

    public function testFind()
    {
        $storeCrossDeviceCollections = factory(StoreCrossDeviceCollection::class, 3)->create();
        $storeCrossDeviceCollectionIds = $storeCrossDeviceCollections->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\StoreCrossDeviceCollectionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreCrossDeviceCollectionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeCrossDeviceCollectionCheck = $repository->find($storeCrossDeviceCollectionIds[0]);
        $this->assertEquals($storeCrossDeviceCollectionIds[0], $storeCrossDeviceCollectionCheck->id);
    }

    public function testCreate()
    {
        $storeCrossDeviceCollectionData = factory(StoreCrossDeviceCollection::class)->make();

        /** @var  \App\Repositories\Postgres\Store\StoreCrossDeviceCollectionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreCrossDeviceCollectionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeCrossDeviceCollectionCheck = $repository->create($storeCrossDeviceCollectionData->toFillableArray());
        $this->assertNotNull($storeCrossDeviceCollectionCheck);
    }

    public function testUpdate()
    {
        $storeCrossDeviceCollectionData = factory(StoreCrossDeviceCollection::class)->create();

        /** @var  \App\Repositories\Postgres\Store\StoreCrossDeviceCollectionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreCrossDeviceCollectionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeCrossDeviceCollectionCheck = $repository->update($storeCrossDeviceCollectionData, $storeCrossDeviceCollectionData->toFillableArray());
        $this->assertNotNull($storeCrossDeviceCollectionCheck);
    }

    public function testDelete()
    {
        $storeCrossDeviceCollectionData = factory(StoreCrossDeviceCollection::class)->create();

        /** @var  \App\Repositories\Postgres\Store\StoreCrossDeviceCollectionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreCrossDeviceCollectionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($storeCrossDeviceCollectionData);

        $storeCrossDeviceCollectionCheck = $repository->find($storeCrossDeviceCollectionData->id);
        $this->assertNull($storeCrossDeviceCollectionCheck);
    }

}
