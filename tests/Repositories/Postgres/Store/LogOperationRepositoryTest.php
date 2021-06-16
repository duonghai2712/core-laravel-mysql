<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Models\Postgres\Store\LogOperation;
use Tests\TestCase;

class LogOperationRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Store\LogOperationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\LogOperationRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $logOperations = factory(LogOperation::class, 3)->create();
        $logOperationIds = $logOperations->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\LogOperationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\LogOperationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $logOperationsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(LogOperation::class, $logOperationsCheck[0]);

        $logOperationsCheck = $repository->getByIds($logOperationIds);
        $this->assertEquals(3, count($logOperationsCheck));
    }

    public function testFind()
    {
        $logOperations = factory(LogOperation::class, 3)->create();
        $logOperationIds = $logOperations->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\LogOperationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\LogOperationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $logOperationCheck = $repository->find($logOperationIds[0]);
        $this->assertEquals($logOperationIds[0], $logOperationCheck->id);
    }

    public function testCreate()
    {
        $logOperationData = factory(LogOperation::class)->make();

        /** @var  \App\Repositories\Postgres\Store\LogOperationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\LogOperationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $logOperationCheck = $repository->create($logOperationData->toFillableArray());
        $this->assertNotNull($logOperationCheck);
    }

    public function testUpdate()
    {
        $logOperationData = factory(LogOperation::class)->create();

        /** @var  \App\Repositories\Postgres\Store\LogOperationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\LogOperationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $logOperationCheck = $repository->update($logOperationData, $logOperationData->toFillableArray());
        $this->assertNotNull($logOperationCheck);
    }

    public function testDelete()
    {
        $logOperationData = factory(LogOperation::class)->create();

        /** @var  \App\Repositories\Postgres\Store\LogOperationRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\LogOperationRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($logOperationData);

        $logOperationCheck = $repository->find($logOperationData->id);
        $this->assertNull($logOperationCheck);
    }

}
