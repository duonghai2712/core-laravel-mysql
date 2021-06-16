<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Models\Postgres\Store\StoreDeviceStatistic;
use Tests\TestCase;

class StoreDeviceStatisticRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Store\StoreDeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreDeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $storeDeviceStatistics = factory(StoreDeviceStatistic::class, 3)->create();
        $storeDeviceStatisticIds = $storeDeviceStatistics->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\StoreDeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreDeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeDeviceStatisticsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(StoreDeviceStatistic::class, $storeDeviceStatisticsCheck[0]);

        $storeDeviceStatisticsCheck = $repository->getByIds($storeDeviceStatisticIds);
        $this->assertEquals(3, count($storeDeviceStatisticsCheck));
    }

    public function testFind()
    {
        $storeDeviceStatistics = factory(StoreDeviceStatistic::class, 3)->create();
        $storeDeviceStatisticIds = $storeDeviceStatistics->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\StoreDeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreDeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeDeviceStatisticCheck = $repository->find($storeDeviceStatisticIds[0]);
        $this->assertEquals($storeDeviceStatisticIds[0], $storeDeviceStatisticCheck->id);
    }

    public function testCreate()
    {
        $storeDeviceStatisticData = factory(StoreDeviceStatistic::class)->make();

        /** @var  \App\Repositories\Postgres\Store\StoreDeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreDeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeDeviceStatisticCheck = $repository->create($storeDeviceStatisticData->toFillableArray());
        $this->assertNotNull($storeDeviceStatisticCheck);
    }

    public function testUpdate()
    {
        $storeDeviceStatisticData = factory(StoreDeviceStatistic::class)->create();

        /** @var  \App\Repositories\Postgres\Store\StoreDeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreDeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeDeviceStatisticCheck = $repository->update($storeDeviceStatisticData, $storeDeviceStatisticData->toFillableArray());
        $this->assertNotNull($storeDeviceStatisticCheck);
    }

    public function testDelete()
    {
        $storeDeviceStatisticData = factory(StoreDeviceStatistic::class)->create();

        /** @var  \App\Repositories\Postgres\Store\StoreDeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreDeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($storeDeviceStatisticData);

        $storeDeviceStatisticCheck = $repository->find($storeDeviceStatisticData->id);
        $this->assertNull($storeDeviceStatisticCheck);
    }

}
