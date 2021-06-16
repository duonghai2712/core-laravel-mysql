<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use App\Models\Postgres\Admin\DeviceStatistic;
use Tests\TestCase;

class DeviceStatisticRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Admin\DeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\DeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $deviceStatistics = factory(DeviceStatistic::class, 3)->create();
        $deviceStatisticIds = $deviceStatistics->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\DeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\DeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $deviceStatisticsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(DeviceStatistic::class, $deviceStatisticsCheck[0]);

        $deviceStatisticsCheck = $repository->getByIds($deviceStatisticIds);
        $this->assertEquals(3, count($deviceStatisticsCheck));
    }

    public function testFind()
    {
        $deviceStatistics = factory(DeviceStatistic::class, 3)->create();
        $deviceStatisticIds = $deviceStatistics->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\DeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\DeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $deviceStatisticCheck = $repository->find($deviceStatisticIds[0]);
        $this->assertEquals($deviceStatisticIds[0], $deviceStatisticCheck->id);
    }

    public function testCreate()
    {
        $deviceStatisticData = factory(DeviceStatistic::class)->make();

        /** @var  \App\Repositories\Postgres\Admin\DeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\DeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $deviceStatisticCheck = $repository->create($deviceStatisticData->toFillableArray());
        $this->assertNotNull($deviceStatisticCheck);
    }

    public function testUpdate()
    {
        $deviceStatisticData = factory(DeviceStatistic::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\DeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\DeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $deviceStatisticCheck = $repository->update($deviceStatisticData, $deviceStatisticData->toFillableArray());
        $this->assertNotNull($deviceStatisticCheck);
    }

    public function testDelete()
    {
        $deviceStatisticData = factory(DeviceStatistic::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\DeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\DeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($deviceStatisticData);

        $deviceStatisticCheck = $repository->find($deviceStatisticData->id);
        $this->assertNull($deviceStatisticCheck);
    }

}
