<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Models\Postgres\Store\Collection;
use Tests\TestCase;

class CollectionRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Store\CollectionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\CollectionRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $collections = factory(Collection::class, 3)->create();
        $collectionIds = $collections->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\CollectionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\CollectionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $collectionsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(Collection::class, $collectionsCheck[0]);

        $collectionsCheck = $repository->getByIds($collectionIds);
        $this->assertEquals(3, count($collectionsCheck));
    }

    public function testFind()
    {
        $collections = factory(Collection::class, 3)->create();
        $collectionIds = $collections->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\CollectionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\CollectionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $collectionCheck = $repository->find($collectionIds[0]);
        $this->assertEquals($collectionIds[0], $collectionCheck->id);
    }

    public function testCreate()
    {
        $collectionData = factory(Collection::class)->make();

        /** @var  \App\Repositories\Postgres\Store\CollectionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\CollectionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $collectionCheck = $repository->create($collectionData->toFillableArray());
        $this->assertNotNull($collectionCheck);
    }

    public function testUpdate()
    {
        $collectionData = factory(Collection::class)->create();

        /** @var  \App\Repositories\Postgres\Store\CollectionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\CollectionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $collectionCheck = $repository->update($collectionData, $collectionData->toFillableArray());
        $this->assertNotNull($collectionCheck);
    }

    public function testDelete()
    {
        $collectionData = factory(Collection::class)->create();

        /** @var  \App\Repositories\Postgres\Store\CollectionRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\CollectionRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($collectionData);

        $collectionCheck = $repository->find($collectionData->id);
        $this->assertNull($collectionCheck);
    }

}
