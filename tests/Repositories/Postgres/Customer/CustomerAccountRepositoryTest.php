<?php namespace App\Repositories\Postgres\Customer\Eloquent;

use App\Models\Postgres\Customer\CustomerAccount;
use Tests\TestCase;

class CustomerAccountRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Customer\CustomerAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Customer\CustomerAccountRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $customerAccounts = factory(CustomerAccount::class, 3)->create();
        $customerAccountIds = $customerAccounts->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Customer\CustomerAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Customer\CustomerAccountRepositoryInterface::class);
        $this->assertNotNull($repository);

        $customerAccountsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(CustomerAccount::class, $customerAccountsCheck[0]);

        $customerAccountsCheck = $repository->getByIds($customerAccountIds);
        $this->assertEquals(3, count($customerAccountsCheck));
    }

    public function testFind()
    {
        $customerAccounts = factory(CustomerAccount::class, 3)->create();
        $customerAccountIds = $customerAccounts->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Customer\CustomerAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Customer\CustomerAccountRepositoryInterface::class);
        $this->assertNotNull($repository);

        $customerAccountCheck = $repository->find($customerAccountIds[0]);
        $this->assertEquals($customerAccountIds[0], $customerAccountCheck->id);
    }

    public function testCreate()
    {
        $customerAccountData = factory(CustomerAccount::class)->make();

        /** @var  \App\Repositories\Postgres\Customer\CustomerAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Customer\CustomerAccountRepositoryInterface::class);
        $this->assertNotNull($repository);

        $customerAccountCheck = $repository->create($customerAccountData->toFillableArray());
        $this->assertNotNull($customerAccountCheck);
    }

    public function testUpdate()
    {
        $customerAccountData = factory(CustomerAccount::class)->create();

        /** @var  \App\Repositories\Postgres\Customer\CustomerAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Customer\CustomerAccountRepositoryInterface::class);
        $this->assertNotNull($repository);

        $customerAccountCheck = $repository->update($customerAccountData, $customerAccountData->toFillableArray());
        $this->assertNotNull($customerAccountCheck);
    }

    public function testDelete()
    {
        $customerAccountData = factory(CustomerAccount::class)->create();

        /** @var  \App\Repositories\Postgres\Customer\CustomerAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Customer\CustomerAccountRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($customerAccountData);

        $customerAccountCheck = $repository->find($customerAccountData->id);
        $this->assertNull($customerAccountCheck);
    }

}
