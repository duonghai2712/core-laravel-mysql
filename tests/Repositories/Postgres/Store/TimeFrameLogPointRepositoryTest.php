<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Models\Postgres\Store\TimeFrameLogPoint;
use Tests\TestCase;

class TimeFrameLogPointRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Store\TimeFrameLogPointRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\TimeFrameLogPointRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $timeFrameLogPoints = factory(TimeFrameLogPoint::class, 3)->create();
        $timeFrameLogPointIds = $timeFrameLogPoints->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\TimeFrameLogPointRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\TimeFrameLogPointRepositoryInterface::class);
        $this->assertNotNull($repository);

        $timeFrameLogPointsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(TimeFrameLogPoint::class, $timeFrameLogPointsCheck[0]);

        $timeFrameLogPointsCheck = $repository->getByIds($timeFrameLogPointIds);
        $this->assertEquals(3, count($timeFrameLogPointsCheck));
    }

    public function testFind()
    {
        $timeFrameLogPoints = factory(TimeFrameLogPoint::class, 3)->create();
        $timeFrameLogPointIds = $timeFrameLogPoints->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\TimeFrameLogPointRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\TimeFrameLogPointRepositoryInterface::class);
        $this->assertNotNull($repository);

        $timeFrameLogPointCheck = $repository->find($timeFrameLogPointIds[0]);
        $this->assertEquals($timeFrameLogPointIds[0], $timeFrameLogPointCheck->id);
    }

    public function testCreate()
    {
        $timeFrameLogPointData = factory(TimeFrameLogPoint::class)->make();

        /** @var  \App\Repositories\Postgres\Store\TimeFrameLogPointRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\TimeFrameLogPointRepositoryInterface::class);
        $this->assertNotNull($repository);

        $timeFrameLogPointCheck = $repository->create($timeFrameLogPointData->toFillableArray());
        $this->assertNotNull($timeFrameLogPointCheck);
    }

    public function testUpdate()
    {
        $timeFrameLogPointData = factory(TimeFrameLogPoint::class)->create();

        /** @var  \App\Repositories\Postgres\Store\TimeFrameLogPointRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\TimeFrameLogPointRepositoryInterface::class);
        $this->assertNotNull($repository);

        $timeFrameLogPointCheck = $repository->update($timeFrameLogPointData, $timeFrameLogPointData->toFillableArray());
        $this->assertNotNull($timeFrameLogPointCheck);
    }

    public function testDelete()
    {
        $timeFrameLogPointData = factory(TimeFrameLogPoint::class)->create();

        /** @var  \App\Repositories\Postgres\Store\TimeFrameLogPointRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\TimeFrameLogPointRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($timeFrameLogPointData);

        $timeFrameLogPointCheck = $repository->find($timeFrameLogPointData->id);
        $this->assertNull($timeFrameLogPointCheck);
    }

}
