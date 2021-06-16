<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use App\Models\Postgres\Admin\ImageLoadingStatus;
use Tests\TestCase;

class ImageLoadingStatusRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Admin\ImageLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\ImageLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $imageLoadingStatuses = factory(ImageLoadingStatus::class, 3)->create();
        $imageLoadingStatusIds = $imageLoadingStatuses->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\ImageLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\ImageLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);

        $imageLoadingStatusesCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(ImageLoadingStatus::class, $imageLoadingStatusesCheck[0]);

        $imageLoadingStatusesCheck = $repository->getByIds($imageLoadingStatusIds);
        $this->assertEquals(3, count($imageLoadingStatusesCheck));
    }

    public function testFind()
    {
        $imageLoadingStatuses = factory(ImageLoadingStatus::class, 3)->create();
        $imageLoadingStatusIds = $imageLoadingStatuses->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\ImageLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\ImageLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);

        $imageLoadingStatusCheck = $repository->find($imageLoadingStatusIds[0]);
        $this->assertEquals($imageLoadingStatusIds[0], $imageLoadingStatusCheck->id);
    }

    public function testCreate()
    {
        $imageLoadingStatusData = factory(ImageLoadingStatus::class)->make();

        /** @var  \App\Repositories\Postgres\Admin\ImageLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\ImageLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);

        $imageLoadingStatusCheck = $repository->create($imageLoadingStatusData->toFillableArray());
        $this->assertNotNull($imageLoadingStatusCheck);
    }

    public function testUpdate()
    {
        $imageLoadingStatusData = factory(ImageLoadingStatus::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\ImageLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\ImageLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);

        $imageLoadingStatusCheck = $repository->update($imageLoadingStatusData, $imageLoadingStatusData->toFillableArray());
        $this->assertNotNull($imageLoadingStatusCheck);
    }

    public function testDelete()
    {
        $imageLoadingStatusData = factory(ImageLoadingStatus::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\ImageLoadingStatusRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\ImageLoadingStatusRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($imageLoadingStatusData);

        $imageLoadingStatusCheck = $repository->find($imageLoadingStatusData->id);
        $this->assertNull($imageLoadingStatusCheck);
    }

}
