<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Models\Postgres\Store\TimeFrame;
use Tests\TestCase;

class TimeFrameRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Store\TimeFrameRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\TimeFrameRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $timeFrames = factory(TimeFrame::class, 3)->create();
        $timeFrameIds = $timeFrames->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\TimeFrameRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\TimeFrameRepositoryInterface::class);
        $this->assertNotNull($repository);

        $timeFramesCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(TimeFrame::class, $timeFramesCheck[0]);

        $timeFramesCheck = $repository->getByIds($timeFrameIds);
        $this->assertEquals(3, count($timeFramesCheck));
    }

    public function testFind()
    {
        $timeFrames = factory(TimeFrame::class, 3)->create();
        $timeFrameIds = $timeFrames->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\TimeFrameRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\TimeFrameRepositoryInterface::class);
        $this->assertNotNull($repository);

        $timeFrameCheck = $repository->find($timeFrameIds[0]);
        $this->assertEquals($timeFrameIds[0], $timeFrameCheck->id);
    }

    public function testCreate()
    {
        $timeFrameData = factory(TimeFrame::class)->make();

        /** @var  \App\Repositories\Postgres\Store\TimeFrameRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\TimeFrameRepositoryInterface::class);
        $this->assertNotNull($repository);

        $timeFrameCheck = $repository->create($timeFrameData->toFillableArray());
        $this->assertNotNull($timeFrameCheck);
    }

    public function testUpdate()
    {
        $timeFrameData = factory(TimeFrame::class)->create();

        /** @var  \App\Repositories\Postgres\Store\TimeFrameRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\TimeFrameRepositoryInterface::class);
        $this->assertNotNull($repository);

        $timeFrameCheck = $repository->update($timeFrameData, $timeFrameData->toFillableArray());
        $this->assertNotNull($timeFrameCheck);
    }

    public function testDelete()
    {
        $timeFrameData = factory(TimeFrame::class)->create();

        /** @var  \App\Repositories\Postgres\Store\TimeFrameRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\TimeFrameRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($timeFrameData);

        $timeFrameCheck = $repository->find($timeFrameData->id);
        $this->assertNull($timeFrameCheck);
    }

}
