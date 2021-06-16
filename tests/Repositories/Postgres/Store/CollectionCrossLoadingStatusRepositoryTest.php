<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Models\Postgres\Store\CollectionCrossLoadingStatus;
use Tests\TestCase;

class CollectionCrossLoadingStatusRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Store\CollectionCrossLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\CollectionCrossLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $collectionCrossLoadingStatuses = factory(CollectionCrossLoadingStatus::class, 3)->create();
        $collectionCrossLoadingStatusIds = $collectionCrossLoadingStatuses->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\CollectionCrossLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\CollectionCrossLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);

        $collectionCrossLoadingStatusesCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(CollectionCrossLoadingStatus::class, $collectionCrossLoadingStatusesCheck[0]);

        $collectionCrossLoadingStatusesCheck = $repository->getByIds($collectionCrossLoadingStatusIds);
        $this->assertEquals(3, count($collectionCrossLoadingStatusesCheck));
    }

    public function testFind()
    {
        $collectionCrossLoadingStatuses = factory(CollectionCrossLoadingStatus::class, 3)->create();
        $collectionCrossLoadingStatusIds = $collectionCrossLoadingStatuses->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\CollectionCrossLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\CollectionCrossLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);

        $collectionCrossLoadingStatusCheck = $repository->find($collectionCrossLoadingStatusIds[0]);
        $this->assertEquals($collectionCrossLoadingStatusIds[0], $collectionCrossLoadingStatusCheck->id);
    }

    public function testCreate()
    {
        $collectionCrossLoadingStatusData = factory(CollectionCrossLoadingStatus::class)->make();

        /** @var  \App\Repositories\Postgres\Store\CollectionCrossLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\CollectionCrossLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);

        $collectionCrossLoadingStatusCheck = $repository->create($collectionCrossLoadingStatusData->toFillableArray());
        $this->assertNotNull($collectionCrossLoadingStatusCheck);
    }

    public function testUpdate()
    {
        $collectionCrossLoadingStatusData = factory(CollectionCrossLoadingStatus::class)->create();

        /** @var  \App\Repositories\Postgres\Store\CollectionCrossLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\CollectionCrossLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);

        $collectionCrossLoadingStatusCheck = $repository->update($collectionCrossLoadingStatusData, $collectionCrossLoadingStatusData->toFillableArray());
        $this->assertNotNull($collectionCrossLoadingStatusCheck);
    }

    public function testDelete()
    {
        $collectionCrossLoadingStatusData = factory(CollectionCrossLoadingStatus::class)->create();

        /** @var  \App\Repositories\Postgres\Store\CollectionCrossLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\CollectionCrossLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($collectionCrossLoadingStatusData);

        $collectionCrossLoadingStatusCheck = $repository->find($collectionCrossLoadingStatusData->id);
        $this->assertNull($collectionCrossLoadingStatusCheck);
    }

}
