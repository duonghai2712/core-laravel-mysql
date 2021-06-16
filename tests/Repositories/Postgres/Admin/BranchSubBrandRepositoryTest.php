<?php namespace Tests\Repositories\Postgres\Admin;

use App\Models\Postgres\Admin\BranchSubBrand;
use Tests\TestCase;

class BranchSubBrandRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Admin\BranchSubBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BranchSubBrandRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $branchSubBrands = factory(BranchSubBrand::class, 3)->create();
        $branchSubBrandIds = $branchSubBrands->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\BranchSubBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BranchSubBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $branchSubBrandsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(BranchSubBrand::class, $branchSubBrandsCheck[0]);

        $branchSubBrandsCheck = $repository->getByIds($branchSubBrandIds);
        $this->assertEquals(3, count($branchSubBrandsCheck));
    }

    public function testFind()
    {
        $branchSubBrands = factory(BranchSubBrand::class, 3)->create();
        $branchSubBrandIds = $branchSubBrands->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\BranchSubBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BranchSubBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $branchSubBrandCheck = $repository->find($branchSubBrandIds[0]);
        $this->assertEquals($branchSubBrandIds[0], $branchSubBrandCheck->id);
    }

    public function testCreate()
    {
        $branchSubBrandData = factory(BranchSubBrand::class)->make();

        /** @var  \App\Repositories\Postgres\Admin\BranchSubBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BranchSubBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $branchSubBrandCheck = $repository->create($branchSubBrandData->toFillableArray());
        $this->assertNotNull($branchSubBrandCheck);
    }

    public function testUpdate()
    {
        $branchSubBrandData = factory(BranchSubBrand::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\BranchSubBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BranchSubBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $branchSubBrandCheck = $repository->update($branchSubBrandData, $branchSubBrandData->toFillableArray());
        $this->assertNotNull($branchSubBrandCheck);
    }

    public function testDelete()
    {
        $branchSubBrandData = factory(BranchSubBrand::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\BranchSubBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BranchSubBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($branchSubBrandData);

        $branchSubBrandCheck = $repository->find($branchSubBrandData->id);
        $this->assertNull($branchSubBrandCheck);
    }

}
