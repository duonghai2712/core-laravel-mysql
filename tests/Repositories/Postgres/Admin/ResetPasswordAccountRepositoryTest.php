<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use App\Models\Postgres\Admin\ResetPasswordAccount;
use Tests\TestCase;

class ResetPasswordAccountRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Admin\ResetPasswordAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\ResetPasswordAccountRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $resetPasswordAccounts = factory(ResetPasswordAccount::class, 3)->create();
        $resetPasswordAccountIds = $resetPasswordAccounts->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\ResetPasswordAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\ResetPasswordAccountRepositoryInterface::class);
        $this->assertNotNull($repository);

        $resetPasswordAccountsCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(ResetPasswordAccount::class, $resetPasswordAccountsCheck[0]);

        $resetPasswordAccountsCheck = $repository->getByIds($resetPasswordAccountIds);
        $this->assertEquals(3, count($resetPasswordAccountsCheck));
    }

    public function testFind()
    {
        $resetPasswordAccounts = factory(ResetPasswordAccount::class, 3)->create();
        $resetPasswordAccountIds = $resetPasswordAccounts->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\ResetPasswordAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\ResetPasswordAccountRepositoryInterface::class);
        $this->assertNotNull($repository);

        $resetPasswordAccountCheck = $repository->find($resetPasswordAccountIds[0]);
        $this->assertEquals($resetPasswordAccountIds[0], $resetPasswordAccountCheck->id);
    }

    public function testCreate()
    {
        $resetPasswordAccountData = factory(ResetPasswordAccount::class)->make();

        /** @var  \App\Repositories\Postgres\Admin\ResetPasswordAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\ResetPasswordAccountRepositoryInterface::class);
        $this->assertNotNull($repository);

        $resetPasswordAccountCheck = $repository->create($resetPasswordAccountData->toFillableArray());
        $this->assertNotNull($resetPasswordAccountCheck);
    }

    public function testUpdate()
    {
        $resetPasswordAccountData = factory(ResetPasswordAccount::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\ResetPasswordAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\ResetPasswordAccountRepositoryInterface::class);
        $this->assertNotNull($repository);

        $resetPasswordAccountCheck = $repository->update($resetPasswordAccountData, $resetPasswordAccountData->toFillableArray());
        $this->assertNotNull($resetPasswordAccountCheck);
    }

    public function testDelete()
    {
        $resetPasswordAccountData = factory(ResetPasswordAccount::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\ResetPasswordAccountRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\ResetPasswordAccountRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($resetPasswordAccountData);

        $resetPasswordAccountCheck = $repository->find($resetPasswordAccountData->id);
        $this->assertNull($resetPasswordAccountCheck);
    }

}
