<?php namespace Tests\Repositories\Postgres\Admin;

use App\Models\Postgres\Industry;
use Tests\TestCase;

class IndustryRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\IndustryRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\IndustryRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $industries = factory(Industry::class, 3)->create();
        $industryIds = $industries->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\IndustryRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\IndustryRepositoryInterface::class);
        $this->assertNotNull($repository);

        $industriesCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(Industry::class, $industriesCheck[0]);

        $industriesCheck = $repository->getByIds($industryIds);
        $this->assertEquals(3, count($industriesCheck));
    }

    public function testFind()
    {
        $industries = factory(Industry::class, 3)->create();
        $industryIds = $industries->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\IndustryRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\IndustryRepositoryInterface::class);
        $this->assertNotNull($repository);

        $industryCheck = $repository->find($industryIds[0]);
        $this->assertEquals($industryIds[0], $industryCheck->id);
    }

    public function testCreate()
    {
        $industryData = factory(Industry::class)->make();

        /** @var  \App\Repositories\Postgres\IndustryRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\IndustryRepositoryInterface::class);
        $this->assertNotNull($repository);

        $industryCheck = $repository->create($industryData->toFillableArray());
        $this->assertNotNull($industryCheck);
    }

    public function testUpdate()
    {
        $industryData = factory(Industry::class)->create();

        /** @var  \App\Repositories\Postgres\IndustryRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\IndustryRepositoryInterface::class);
        $this->assertNotNull($repository);

        $industryCheck = $repository->update($industryData, $industryData->toFillableArray());
        $this->assertNotNull($industryCheck);
    }

    public function testDelete()
    {
        $industryData = factory(Industry::class)->create();

        /** @var  \App\Repositories\Postgres\IndustryRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\IndustryRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($industryData);

        $industryCheck = $repository->find($industryData->id);
        $this->assertNull($industryCheck);
    }

}
