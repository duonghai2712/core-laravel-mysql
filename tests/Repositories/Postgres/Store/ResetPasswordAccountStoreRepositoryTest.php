<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Models\Postgres\Store\ResetPasswordAccountStore;
use Tests\TestCase;

class ResetPasswordAccountStoreRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Store\ResetPasswordAccountStoreRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\ResetPasswordAccountStoreRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $resetPasswordAccountStores = factory(ResetPasswordAccountStore::class, 3)->create();
        $resetPasswordAccountStoreIds = $resetPasswordAccountStores->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\ResetPasswordAccountStoreRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\ResetPasswordAccountStoreRepositoryInterface::class);
        $this->assertNotNull($repository);

        $resetPasswordAccountStoresCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(ResetPasswordAccountStore::class, $resetPasswordAccountStoresCheck[0]);

        $resetPasswordAccountStoresCheck = $repository->getByIds($resetPasswordAccountStoreIds);
        $this->assertEquals(3, count($resetPasswordAccountStoresCheck));
    }

    public function testFind()
    {
        $resetPasswordAccountStores = factory(ResetPasswordAccountStore::class, 3)->create();
        $resetPasswordAccountStoreIds = $resetPasswordAccountStores->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Store\ResetPasswordAccountStoreRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\ResetPasswordAccountStoreRepositoryInterface::class);
        $this->assertNotNull($repository);

        $resetPasswordAccountStoreCheck = $repository->find($resetPasswordAccountStoreIds[0]);
        $this->assertEquals($resetPasswordAccountStoreIds[0], $resetPasswordAccountStoreCheck->id);
    }

    public function testCreate()
    {
        $resetPasswordAccountStoreData = factory(ResetPasswordAccountStore::class)->make();

        /** @var  \App\Repositories\Postgres\Store\ResetPasswordAccountStoreRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\ResetPasswordAccountStoreRepositoryInterface::class);
        $this->assertNotNull($repository);

        $resetPasswordAccountStoreCheck = $repository->create($resetPasswordAccountStoreData->toFillableArray());
        $this->assertNotNull($resetPasswordAccountStoreCheck);
    }

    public function testUpdate()
    {
        $resetPasswordAccountStoreData = factory(ResetPasswordAccountStore::class)->create();

        /** @var  \App\Repositories\Postgres\Store\ResetPasswordAccountStoreRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\ResetPasswordAccountStoreRepositoryInterface::class);
        $this->assertNotNull($repository);

        $resetPasswordAccountStoreCheck = $repository->update($resetPasswordAccountStoreData, $resetPasswordAccountStoreData->toFillableArray());
        $this->assertNotNull($resetPasswordAccountStoreCheck);
    }

    public function testDelete()
    {
        $resetPasswordAccountStoreData = factory(ResetPasswordAccountStore::class)->create();

        /** @var  \App\Repositories\Postgres\Store\ResetPasswordAccountStoreRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Store\ResetPasswordAccountStoreRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($resetPasswordAccountStoreData);

        $resetPasswordAccountStoreCheck = $repository->find($resetPasswordAccountStoreData->id);
        $this->assertNull($resetPasswordAccountStoreCheck);
    }

}
