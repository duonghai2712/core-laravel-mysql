<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Models\Postgres\Store\OrderBranch;
use Tests\TestCase;

class OrderBranchRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Store\OrderBranchRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderBranchRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $orderBranches = factory(OrderBranch::class, 3)->create();
        $orderBranchIds = $orderBranches->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\OrderBranchRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderBranchRepositoryInterface::class);
        $this->assertNotNull($repository);

        $orderBranchesCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(OrderBranch::class, $orderBranchesCheck[0]);

        $orderBranchesCheck = $repository->getByIds($orderBranchIds);
        $this->assertEquals(3, count($orderBranchesCheck));
    }

    public function testFind()
    {
        $orderBranches = factory(OrderBranch::class, 3)->create();
        $orderBranchIds = $orderBranches->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\OrderBranchRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderBranchRepositoryInterface::class);
        $this->assertNotNull($repository);

        $orderBranchCheck = $repository->find($orderBranchIds[0]);
        $this->assertEquals($orderBranchIds[0], $orderBranchCheck->id);
    }

    public function testCreate()
    {
        $orderBranchData = factory(OrderBranch::class)->make();

        /** @var  \App\Repositories\Postgres\Store\OrderBranchRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderBranchRepositoryInterface::class);
        $this->assertNotNull($repository);

        $orderBranchCheck = $repository->create($orderBranchData->toFillableArray());
        $this->assertNotNull($orderBranchCheck);
    }

    public function testUpdate()
    {
        $orderBranchData = factory(OrderBranch::class)->create();

        /** @var  \App\Repositories\Postgres\Store\OrderBranchRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderBranchRepositoryInterface::class);
        $this->assertNotNull($repository);

        $orderBranchCheck = $repository->update($orderBranchData, $orderBranchData->toFillableArray());
        $this->assertNotNull($orderBranchCheck);
    }

    public function testDelete()
    {
        $orderBranchData = factory(OrderBranch::class)->create();

        /** @var  \App\Repositories\Postgres\Store\OrderBranchRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\OrderBranchRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($orderBranchData);

        $orderBranchCheck = $repository->find($orderBranchData->id);
        $this->assertNull($orderBranchCheck);
    }

}
