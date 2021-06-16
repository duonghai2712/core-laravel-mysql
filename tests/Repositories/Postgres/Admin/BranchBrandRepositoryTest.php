<?php namespace Tests\Repositories\Postgres\Admin;

use App\Models\Postgres\Admin\BranchBrand;
use Tests\TestCase;

class BranchBrandRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Admin\BranchBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BranchBrandRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $branchBrands = factory(BranchBrand::class, 3)->create();
        $branchBrandIds = $branchBrands->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\BranchBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BranchBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $branchBrandsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(BranchBrand::class, $branchBrandsCheck[0]);

        $branchBrandsCheck = $repository->getByIds($branchBrandIds);
        $this->assertEquals(3, count($branchBrandsCheck));
    }

    public function testFind()
    {
        $branchBrands = factory(BranchBrand::class, 3)->create();
        $branchBrandIds = $branchBrands->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\BranchBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BranchBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $branchBrandCheck = $repository->find($branchBrandIds[0]);
        $this->assertEquals($branchBrandIds[0], $branchBrandCheck->id);
    }

    public function testCreate()
    {
        $branchBrandData = factory(BranchBrand::class)->make();

        /** @var  \App\Repositories\Postgres\Admin\BranchBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BranchBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $branchBrandCheck = $repository->create($branchBrandData->toFillableArray());
        $this->assertNotNull($branchBrandCheck);
    }

    public function testUpdate()
    {
        $branchBrandData = factory(BranchBrand::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\BranchBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BranchBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $branchBrandCheck = $repository->update($branchBrandData, $branchBrandData->toFillableArray());
        $this->assertNotNull($branchBrandCheck);
    }

    public function testDelete()
    {
        $branchBrandData = factory(BranchBrand::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\BranchBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BranchBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($branchBrandData);

        $branchBrandCheck = $repository->find($branchBrandData->id);
        $this->assertNull($branchBrandCheck);
    }

}
