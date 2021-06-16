<?php namespace Tests\Repositories\Postgres\Admin;

use App\Models\Postgres\Admin\Branch;
use Tests\TestCase;

class BranchRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Admin\BranchRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BranchRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $branches = factory(Branch::class, 3)->create();
        $branchIds = $branches->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\BranchRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BranchRepositoryInterface::class);
        $this->assertNotNull($repository);

        $branchesCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(Branch::class, $branchesCheck[0]);

        $branchesCheck = $repository->getByIds($branchIds);
        $this->assertEquals(3, count($branchesCheck));
    }

    public function testFind()
    {
        $branches = factory(Branch::class, 3)->create();
        $branchIds = $branches->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\BranchRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BranchRepositoryInterface::class);
        $this->assertNotNull($repository);

        $branchCheck = $repository->find($branchIds[0]);
        $this->assertEquals($branchIds[0], $branchCheck->id);
    }

    public function testCreate()
    {
        $branchData = factory(Branch::class)->make();

        /** @var  \App\Repositories\Postgres\Admin\BranchRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BranchRepositoryInterface::class);
        $this->assertNotNull($repository);

        $branchCheck = $repository->create($branchData->toFillableArray());
        $this->assertNotNull($branchCheck);
    }

    public function testUpdate()
    {
        $branchData = factory(Branch::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\BranchRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BranchRepositoryInterface::class);
        $this->assertNotNull($repository);

        $branchCheck = $repository->update($branchData, $branchData->toFillableArray());
        $this->assertNotNull($branchCheck);
    }

    public function testDelete()
    {
        $branchData = factory(Branch::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\BranchRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BranchRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($branchData);

        $branchCheck = $repository->find($branchData->id);
        $this->assertNull($branchCheck);
    }

}
