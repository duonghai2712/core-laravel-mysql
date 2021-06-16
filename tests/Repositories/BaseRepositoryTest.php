<?php namespace Tests\Repositories;

use App\Models\Base;
use Tests\TestCase;

class BaseRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\BaseRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\BaseRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $bases = factory(Base::class, 3)->create();
        $baseIds = $bases->pluck('id')->toArray();

        /** @var  \App\Repositories\BaseRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\BaseRepositoryInterface::class);
        $this->assertNotNull($repository);

        $basesCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(Base::class, $basesCheck[0]);

        $basesCheck = $repository->getByIds($baseIds);
        $this->assertEquals(3, count($basesCheck));
    }

    public function testFind()
    {
        $bases = factory(Base::class, 3)->create();
        $baseIds = $bases->pluck('id')->toArray();

        /** @var  \App\Repositories\BaseRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\BaseRepositoryInterface::class);
        $this->assertNotNull($repository);

        $baseCheck = $repository->find($baseIds[0]);
        $this->assertEquals($baseIds[0], $baseCheck->id);
    }

    public function testCreate()
    {
        $baseData = factory(Base::class)->make();

        /** @var  \App\Repositories\BaseRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\BaseRepositoryInterface::class);
        $this->assertNotNull($repository);

        $baseCheck = $repository->create($baseData->toFillableArray());
        $this->assertNotNull($baseCheck);
    }

    public function testUpdate()
    {
        $baseData = factory(Base::class)->create();

        /** @var  \App\Repositories\BaseRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\BaseRepositoryInterface::class);
        $this->assertNotNull($repository);

        $baseCheck = $repository->update($baseData, $baseData->toFillableArray());
        $this->assertNotNull($baseCheck);
    }

    public function testDelete()
    {
        $baseData = factory(Base::class)->create();

        /** @var  \App\Repositories\BaseRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\BaseRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($baseData);

        $baseCheck = $repository->find($baseData->id);
        $this->assertNull($baseCheck);
    }

}
