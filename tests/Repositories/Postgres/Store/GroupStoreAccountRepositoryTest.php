<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Models\Postgres\Store\GroupStoreAccount;
use Tests\TestCase;

class GroupStoreAccountRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Store\GroupStoreAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\GroupStoreAccountRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $groupStoreAccounts = factory(GroupStoreAccount::class, 3)->create();
        $groupStoreAccountIds = $groupStoreAccounts->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\GroupStoreAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\GroupStoreAccountRepositoryInterface::class);
        $this->assertNotNull($repository);

        $groupStoreAccountsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(GroupStoreAccount::class, $groupStoreAccountsCheck[0]);

        $groupStoreAccountsCheck = $repository->getByIds($groupStoreAccountIds);
        $this->assertEquals(3, count($groupStoreAccountsCheck));
    }

    public function testFind()
    {
        $groupStoreAccounts = factory(GroupStoreAccount::class, 3)->create();
        $groupStoreAccountIds = $groupStoreAccounts->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\GroupStoreAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\GroupStoreAccountRepositoryInterface::class);
        $this->assertNotNull($repository);

        $groupStoreAccountCheck = $repository->find($groupStoreAccountIds[0]);
        $this->assertEquals($groupStoreAccountIds[0], $groupStoreAccountCheck->id);
    }

    public function testCreate()
    {
        $groupStoreAccountData = factory(GroupStoreAccount::class)->make();

        /** @var  \App\Repositories\Postgres\Store\GroupStoreAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\GroupStoreAccountRepositoryInterface::class);
        $this->assertNotNull($repository);

        $groupStoreAccountCheck = $repository->create($groupStoreAccountData->toFillableArray());
        $this->assertNotNull($groupStoreAccountCheck);
    }

    public function testUpdate()
    {
        $groupStoreAccountData = factory(GroupStoreAccount::class)->create();

        /** @var  \App\Repositories\Postgres\Store\GroupStoreAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\GroupStoreAccountRepositoryInterface::class);
        $this->assertNotNull($repository);

        $groupStoreAccountCheck = $repository->update($groupStoreAccountData, $groupStoreAccountData->toFillableArray());
        $this->assertNotNull($groupStoreAccountCheck);
    }

    public function testDelete()
    {
        $groupStoreAccountData = factory(GroupStoreAccount::class)->create();

        /** @var  \App\Repositories\Postgres\Store\GroupStoreAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\GroupStoreAccountRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($groupStoreAccountData);

        $groupStoreAccountCheck = $repository->find($groupStoreAccountData->id);
        $this->assertNull($groupStoreAccountCheck);
    }

}
