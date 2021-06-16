<?php namespace Tests\Repositories\Postgres\Admin;

use App\Models\Postgres\Admin\StoreBrand;
use Tests\TestCase;

class StoreBrandRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Admin\StoreBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\StoreBrandRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $storeBrands = factory(StoreBrand::class, 3)->create();
        $storeBrandIds = $storeBrands->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\StoreBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\StoreBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeBrandsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(StoreBrand::class, $storeBrandsCheck[0]);

        $storeBrandsCheck = $repository->getByIds($storeBrandIds);
        $this->assertEquals(3, count($storeBrandsCheck));
    }

    public function testFind()
    {
        $storeBrands = factory(StoreBrand::class, 3)->create();
        $storeBrandIds = $storeBrands->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\StoreBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\StoreBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeBrandCheck = $repository->find($storeBrandIds[0]);
        $this->assertEquals($storeBrandIds[0], $storeBrandCheck->id);
    }

    public function testCreate()
    {
        $storeBrandData = factory(StoreBrand::class)->make();

        /** @var  \App\Repositories\Postgres\Admin\StoreBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\StoreBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeBrandCheck = $repository->create($storeBrandData->toFillableArray());
        $this->assertNotNull($storeBrandCheck);
    }

    public function testUpdate()
    {
        $storeBrandData = factory(StoreBrand::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\StoreBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\StoreBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeBrandCheck = $repository->update($storeBrandData, $storeBrandData->toFillableArray());
        $this->assertNotNull($storeBrandCheck);
    }

    public function testDelete()
    {
        $storeBrandData = factory(StoreBrand::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\StoreBrandRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\StoreBrandRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($storeBrandData);

        $storeBrandCheck = $repository->find($storeBrandData->id);
        $this->assertNull($storeBrandCheck);
    }

}
