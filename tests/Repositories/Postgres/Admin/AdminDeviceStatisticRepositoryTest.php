<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use App\Models\Postgres\Admin\AdminDeviceStatistic;
use Tests\TestCase;

class AdminDeviceStatisticRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Admin\AdminDeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\AdminDeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $adminDeviceStatistics = factory(AdminDeviceStatistic::class, 3)->create();
        $adminDeviceStatisticIds = $adminDeviceStatistics->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\AdminDeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\AdminDeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $adminDeviceStatisticsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(AdminDeviceStatistic::class, $adminDeviceStatisticsCheck[0]);

        $adminDeviceStatisticsCheck = $repository->getByIds($adminDeviceStatisticIds);
        $this->assertEquals(3, count($adminDeviceStatisticsCheck));
    }

    public function testFind()
    {
        $adminDeviceStatistics = factory(AdminDeviceStatistic::class, 3)->create();
        $adminDeviceStatisticIds = $adminDeviceStatistics->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\AdminDeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\AdminDeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $adminDeviceStatisticCheck = $repository->find($adminDeviceStatisticIds[0]);
        $this->assertEquals($adminDeviceStatisticIds[0], $adminDeviceStatisticCheck->id);
    }

    public function testCreate()
    {
        $adminDeviceStatisticData = factory(AdminDeviceStatistic::class)->make();

        /** @var  \App\Repositories\Postgres\Admin\AdminDeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\AdminDeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $adminDeviceStatisticCheck = $repository->create($adminDeviceStatisticData->toFillableArray());
        $this->assertNotNull($adminDeviceStatisticCheck);
    }

    public function testUpdate()
    {
        $adminDeviceStatisticData = factory(AdminDeviceStatistic::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\AdminDeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\AdminDeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $adminDeviceStatisticCheck = $repository->update($adminDeviceStatisticData, $adminDeviceStatisticData->toFillableArray());
        $this->assertNotNull($adminDeviceStatisticCheck);
    }

    public function testDelete()
    {
        $adminDeviceStatisticData = factory(AdminDeviceStatistic::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\AdminDeviceStatisticRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\AdminDeviceStatisticRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($adminDeviceStatisticData);

        $adminDeviceStatisticCheck = $repository->find($adminDeviceStatisticData->id);
        $this->assertNull($adminDeviceStatisticCheck);
    }

}
