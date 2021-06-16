<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Models\Postgres\Store\StoreAccount;
use Tests\TestCase;

class StoreAccountRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Store\StoreAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreAccountRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $storeAccounts = factory(StoreAccount::class, 3)->create();
        $storeAccountIds = $storeAccounts->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\StoreAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreAccountRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeAccountsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(StoreAccount::class, $storeAccountsCheck[0]);

        $storeAccountsCheck = $repository->getByIds($storeAccountIds);
        $this->assertEquals(3, count($storeAccountsCheck));
    }

    public function testFind()
    {
        $storeAccounts = factory(StoreAccount::class, 3)->create();
        $storeAccountIds = $storeAccounts->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\StoreAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreAccountRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeAccountCheck = $repository->find($storeAccountIds[0]);
        $this->assertEquals($storeAccountIds[0], $storeAccountCheck->id);
    }

    public function testCreate()
    {
        $storeAccountData = factory(StoreAccount::class)->make();

        /** @var  \App\Repositories\Postgres\Store\StoreAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreAccountRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeAccountCheck = $repository->create($storeAccountData->toFillableArray());
        $this->assertNotNull($storeAccountCheck);
    }

    public function testUpdate()
    {
        $storeAccountData = factory(StoreAccount::class)->create();

        /** @var  \App\Repositories\Postgres\Store\StoreAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreAccountRepositoryInterface::class);
        $this->assertNotNull($repository);

        $storeAccountCheck = $repository->update($storeAccountData, $storeAccountData->toFillableArray());
        $this->assertNotNull($storeAccountCheck);
    }

    public function testDelete()
    {
        $storeAccountData = factory(StoreAccount::class)->create();

        /** @var  \App\Repositories\Postgres\Store\StoreAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\StoreAccountRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($storeAccountData);

        $storeAccountCheck = $repository->find($storeAccountData->id);
        $this->assertNull($storeAccountCheck);
    }

}
