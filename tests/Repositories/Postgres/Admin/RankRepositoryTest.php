<?php namespace Tests\Repositories\Postgres\Admin;

use App\Models\Postgres\Admin\Rank;
use Tests\TestCase;

class RankRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Admin\RankRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\RankRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $ranks = factory(Rank::class, 3)->create();
        $rankIds = $ranks->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\RankRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\RankRepositoryInterface::class);
        $this->assertNotNull($repository);

        $ranksCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(Rank::class, $ranksCheck[0]);

        $ranksCheck = $repository->getByIds($rankIds);
        $this->assertEquals(3, count($ranksCheck));
    }

    public function testFind()
    {
        $ranks = factory(Rank::class, 3)->create();
        $rankIds = $ranks->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\RankRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\RankRepositoryInterface::class);
        $this->assertNotNull($repository);

        $rankCheck = $repository->find($rankIds[0]);
        $this->assertEquals($rankIds[0], $rankCheck->id);
    }

    public function testCreate()
    {
        $rankData = factory(Rank::class)->make();

        /** @var  \App\Repositories\Postgres\Admin\RankRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\RankRepositoryInterface::class);
        $this->assertNotNull($repository);

        $rankCheck = $repository->create($rankData->toFillableArray());
        $this->assertNotNull($rankCheck);
    }

    public function testUpdate()
    {
        $rankData = factory(Rank::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\RankRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\RankRepositoryInterface::class);
        $this->assertNotNull($repository);

        $rankCheck = $repository->update($rankData, $rankData->toFillableArray());
        $this->assertNotNull($rankCheck);
    }

    public function testDelete()
    {
        $rankData = factory(Rank::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\RankRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\RankRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($rankData);

        $rankCheck = $repository->find($rankData->id);
        $this->assertNull($rankCheck);
    }

}
