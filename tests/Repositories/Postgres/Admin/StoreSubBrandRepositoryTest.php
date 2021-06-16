<?php namespace Tests\Repositories\Postgres\Admin;

use App\Models\Postgres\Admin\StoreSubBrand;
use Tests\TestCase;

class StoreSubBrandRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Admin\StoreSubBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\StoreSubBrandRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $storeSubBrands = factory(StoreSubBrand::class, 3)->create();
        $storeSubBrandIds = $storeSubBrands->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\StoreSubBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\StoreSubBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeSubBrandsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(StoreSubBrand::class, $storeSubBrandsCheck[0]);

        $storeSubBrandsCheck = $repository->getByIds($storeSubBrandIds);
        $this->assertEquals(3, count($storeSubBrandsCheck));
    }

    public function testFind()
    {
        $storeSubBrands = factory(StoreSubBrand::class, 3)->create();
        $storeSubBrandIds = $storeSubBrands->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\StoreSubBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\StoreSubBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeSubBrandCheck = $repository->find($storeSubBrandIds[0]);
        $this->assertEquals($storeSubBrandIds[0], $storeSubBrandCheck->id);
    }

    public function testCreate()
    {
        $storeSubBrandData = factory(StoreSubBrand::class)->make();

        /** @var  \App\Repositories\Postgres\Admin\StoreSubBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\StoreSubBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeSubBrandCheck = $repository->create($storeSubBrandData->toFillableArray());
        $this->assertNotNull($storeSubBrandCheck);
    }

    public function testUpdate()
    {
        $storeSubBrandData = factory(StoreSubBrand::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\StoreSubBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\StoreSubBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeSubBrandCheck = $repository->update($storeSubBrandData, $storeSubBrandData->toFillableArray());
        $this->assertNotNull($storeSubBrandCheck);
    }

    public function testDelete()
    {
        $storeSubBrandData = factory(StoreSubBrand::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\StoreSubBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\StoreSubBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($storeSubBrandData);

        $storeSubBrandCheck = $repository->find($storeSubBrandData->id);
        $this->assertNull($storeSubBrandCheck);
    }

}
