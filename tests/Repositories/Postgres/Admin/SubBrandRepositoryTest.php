<?php namespace Tests\Repositories\Postgres\Admin;

use App\Models\Postgres\Admin\SubBrand;
use Tests\TestCase;

class SubBrandRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Admin\SubBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\SubBrandRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $subBrands = factory(SubBrand::class, 3)->create();
        $subBrandIds = $subBrands->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\SubBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\SubBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $subBrandsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(SubBrand::class, $subBrandsCheck[0]);

        $subBrandsCheck = $repository->getByIds($subBrandIds);
        $this->assertEquals(3, count($subBrandsCheck));
    }

    public function testFind()
    {
        $subBrands = factory(SubBrand::class, 3)->create();
        $subBrandIds = $subBrands->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\SubBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\SubBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $subBrandCheck = $repository->find($subBrandIds[0]);
        $this->assertEquals($subBrandIds[0], $subBrandCheck->id);
    }

    public function testCreate()
    {
        $subBrandData = factory(SubBrand::class)->make();

        /** @var  \App\Repositories\Postgres\Admin\SubBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\SubBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $subBrandCheck = $repository->create($subBrandData->toFillableArray());
        $this->assertNotNull($subBrandCheck);
    }

    public function testUpdate()
    {
        $subBrandData = factory(SubBrand::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\SubBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\SubBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $subBrandCheck = $repository->update($subBrandData, $subBrandData->toFillableArray());
        $this->assertNotNull($subBrandCheck);
    }

    public function testDelete()
    {
        $subBrandData = factory(SubBrand::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\SubBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\SubBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($subBrandData);

        $subBrandCheck = $repository->find($subBrandData->id);
        $this->assertNull($subBrandCheck);
    }

}
