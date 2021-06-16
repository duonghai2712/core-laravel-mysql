<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Models\Postgres\Store\CollectionLoadingStatus;
use Tests\TestCase;

class CollectionLoadingStatusRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Store\CollectionLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\CollectionLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $collectionLoadingStatuses = factory(CollectionLoadingStatus::class, 3)->create();
        $collectionLoadingStatusIds = $collectionLoadingStatuses->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\CollectionLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\CollectionLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);

        $collectionLoadingStatusesCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(CollectionLoadingStatus::class, $collectionLoadingStatusesCheck[0]);

        $collectionLoadingStatusesCheck = $repository->getByIds($collectionLoadingStatusIds);
        $this->assertEquals(3, count($collectionLoadingStatusesCheck));
    }

    public function testFind()
    {
        $collectionLoadingStatuses = factory(CollectionLoadingStatus::class, 3)->create();
        $collectionLoadingStatusIds = $collectionLoadingStatuses->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\CollectionLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\CollectionLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);

        $collectionLoadingStatusCheck = $repository->find($collectionLoadingStatusIds[0]);
        $this->assertEquals($collectionLoadingStatusIds[0], $collectionLoadingStatusCheck->id);
    }

    public function testCreate()
    {
        $collectionLoadingStatusData = factory(CollectionLoadingStatus::class)->make();

        /** @var  \App\Repositories\Postgres\Store\CollectionLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\CollectionLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);

        $collectionLoadingStatusCheck = $repository->create($collectionLoadingStatusData->toFillableArray());
        $this->assertNotNull($collectionLoadingStatusCheck);
    }

    public function testUpdate()
    {
        $collectionLoadingStatusData = factory(CollectionLoadingStatus::class)->create();

        /** @var  \App\Repositories\Postgres\Store\CollectionLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\CollectionLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);

        $collectionLoadingStatusCheck = $repository->update($collectionLoadingStatusData, $collectionLoadingStatusData->toFillableArray());
        $this->assertNotNull($collectionLoadingStatusCheck);
    }

    public function testDelete()
    {
        $collectionLoadingStatusData = factory(CollectionLoadingStatus::class)->create();

        /** @var  \App\Repositories\Postgres\Store\CollectionLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\CollectionLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($collectionLoadingStatusData);

        $collectionLoadingStatusCheck = $repository->find($collectionLoadingStatusData->id);
        $this->assertNull($collectionLoadingStatusCheck);
    }

}
