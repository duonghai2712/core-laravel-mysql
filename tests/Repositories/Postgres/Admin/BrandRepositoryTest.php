<?php namespace Tests\Repositories\Postgres\Admin;

use App\Models\Postgres\Admin\Brand;
use Tests\TestCase;

class BrandRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Admin\BrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BrandRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $brands = factory(Brand::class, 3)->create();
        $brandIds = $brands->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\BrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $brandsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(Brand::class, $brandsCheck[0]);

        $brandsCheck = $repository->getByIds($brandIds);
        $this->assertEquals(3, count($brandsCheck));
    }

    public function testFind()
    {
        $brands = factory(Brand::class, 3)->create();
        $brandIds = $brands->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\BrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $brandCheck = $repository->find($brandIds[0]);
        $this->assertEquals($brandIds[0], $brandCheck->id);
    }

    public function testCreate()
    {
        $brandData = factory(Brand::class)->make();

        /** @var  \App\Repositories\Postgres\Admin\BrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $brandCheck = $repository->create($brandData->toFillableArray());
        $this->assertNotNull($brandCheck);
    }

    public function testUpdate()
    {
        $brandData = factory(Brand::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\BrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $brandCheck = $repository->update($brandData, $brandData->toFillableArray());
        $this->assertNotNull($brandCheck);
    }

    public function testDelete()
    {
        $brandData = factory(Brand::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\BrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\BrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($brandData);

        $brandCheck = $repository->find($brandData->id);
        $this->assertNull($brandCheck);
    }

}
