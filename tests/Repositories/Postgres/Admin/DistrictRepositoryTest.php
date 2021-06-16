<?php namespace Tests\Repositories\Postgres\Admin;

use App\Models\Postgres\District;
use Tests\TestCase;

class DistrictRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\DistrictRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\DistrictRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $districts = factory(District::class, 3)->create();
        $districtIds = $districts->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\DistrictRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\DistrictRepositoryInterface::class);
        $this->assertNotNull($repository);

        $districtsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(District::class, $districtsCheck[0]);

        $districtsCheck = $repository->getByIds($districtIds);
        $this->assertEquals(3, count($districtsCheck));
    }

    public function testFind()
    {
        $districts = factory(District::class, 3)->create();
        $districtIds = $districts->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\DistrictRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\DistrictRepositoryInterface::class);
        $this->assertNotNull($repository);

        $districtCheck = $repository->find($districtIds[0]);
        $this->assertEquals($districtIds[0], $districtCheck->id);
    }

    public function testCreate()
    {
        $districtData = factory(District::class)->make();

        /** @var  \App\Repositories\Postgres\DistrictRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\DistrictRepositoryInterface::class);
        $this->assertNotNull($repository);

        $districtCheck = $repository->create($districtData->toFillableArray());
        $this->assertNotNull($districtCheck);
    }

    public function testUpdate()
    {
        $districtData = factory(District::class)->create();

        /** @var  \App\Repositories\Postgres\DistrictRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\DistrictRepositoryInterface::class);
        $this->assertNotNull($repository);

        $districtCheck = $repository->update($districtData, $districtData->toFillableArray());
        $this->assertNotNull($districtCheck);
    }

    public function testDelete()
    {
        $districtData = factory(District::class)->create();

        /** @var  \App\Repositories\Postgres\DistrictRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\DistrictRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($districtData);

        $districtCheck = $repository->find($districtData->id);
        $this->assertNull($districtCheck);
    }

}
