<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Models\Postgres\Store\LogPoint;
use Tests\TestCase;

class LogPointRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Store\LogPointRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\LogPointRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $logPoints = factory(LogPoint::class, 3)->create();
        $logPointIds = $logPoints->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\LogPointRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\LogPointRepositoryInterface::class);
        $this->assertNotNull($repository);

        $logPointsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(LogPoint::class, $logPointsCheck[0]);

        $logPointsCheck = $repository->getByIds($logPointIds);
        $this->assertEquals(3, count($logPointsCheck));
    }

    public function testFind()
    {
        $logPoints = factory(LogPoint::class, 3)->create();
        $logPointIds = $logPoints->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\LogPointRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\LogPointRepositoryInterface::class);
        $this->assertNotNull($repository);

        $logPointCheck = $repository->find($logPointIds[0]);
        $this->assertEquals($logPointIds[0], $logPointCheck->id);
    }

    public function testCreate()
    {
        $logPointData = factory(LogPoint::class)->make();

        /** @var  \App\Repositories\Postgres\Store\LogPointRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\LogPointRepositoryInterface::class);
        $this->assertNotNull($repository);

        $logPointCheck = $repository->create($logPointData->toFillableArray());
        $this->assertNotNull($logPointCheck);
    }

    public function testUpdate()
    {
        $logPointData = factory(LogPoint::class)->create();

        /** @var  \App\Repositories\Postgres\Store\LogPointRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\LogPointRepositoryInterface::class);
        $this->assertNotNull($repository);

        $logPointCheck = $repository->update($logPointData, $logPointData->toFillableArray());
        $this->assertNotNull($logPointCheck);
    }

    public function testDelete()
    {
        $logPointData = factory(LogPoint::class)->create();

        /** @var  \App\Repositories\Postgres\Store\LogPointRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\LogPointRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($logPointData);

        $logPointCheck = $repository->find($logPointData->id);
        $this->assertNull($logPointCheck);
    }

}
