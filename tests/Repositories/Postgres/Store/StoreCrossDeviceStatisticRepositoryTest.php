<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Models\Postgres\Store\StoreCrossDeviceStatistic;
use Tests\TestCase;

class StoreCrossDeviceStatisticRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Store\StoreCrossDeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreCrossDeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $storeCrossDeviceStatistics = factory(StoreCrossDeviceStatistic::class, 3)->create();
        $storeCrossDeviceStatisticIds = $storeCrossDeviceStatistics->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\StoreCrossDeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreCrossDeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeCrossDeviceStatisticsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(StoreCrossDeviceStatistic::class, $storeCrossDeviceStatisticsCheck[0]);

        $storeCrossDeviceStatisticsCheck = $repository->getByIds($storeCrossDeviceStatisticIds);
        $this->assertEquals(3, count($storeCrossDeviceStatisticsCheck));
    }

    public function testFind()
    {
        $storeCrossDeviceStatistics = factory(StoreCrossDeviceStatistic::class, 3)->create();
        $storeCrossDeviceStatisticIds = $storeCrossDeviceStatistics->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\StoreCrossDeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreCrossDeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeCrossDeviceStatisticCheck = $repository->find($storeCrossDeviceStatisticIds[0]);
        $this->assertEquals($storeCrossDeviceStatisticIds[0], $storeCrossDeviceStatisticCheck->id);
    }

    public function testCreate()
    {
        $storeCrossDeviceStatisticData = factory(StoreCrossDeviceStatistic::class)->make();

        /** @var  \App\Repositories\Postgres\Store\StoreCrossDeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreCrossDeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeCrossDeviceStatisticCheck = $repository->create($storeCrossDeviceStatisticData->toFillableArray());
        $this->assertNotNull($storeCrossDeviceStatisticCheck);
    }

    public function testUpdate()
    {
        $storeCrossDeviceStatisticData = factory(StoreCrossDeviceStatistic::class)->create();

        /** @var  \App\Repositories\Postgres\Store\StoreCrossDeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreCrossDeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeCrossDeviceStatisticCheck = $repository->update($storeCrossDeviceStatisticData, $storeCrossDeviceStatisticData->toFillableArray());
        $this->assertNotNull($storeCrossDeviceStatisticCheck);
    }

    public function testDelete()
    {
        $storeCrossDeviceStatisticData = factory(StoreCrossDeviceStatistic::class)->create();

        /** @var  \App\Repositories\Postgres\Store\StoreCrossDeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreCrossDeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($storeCrossDeviceStatisticData);

        $storeCrossDeviceStatisticCheck = $repository->find($storeCrossDeviceStatisticData->id);
        $this->assertNull($storeCrossDeviceStatisticCheck);
    }

}
