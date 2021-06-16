<?php namespace tests\models\Postgres\Store;

use App\Models\Postgres\Store\ResetPasswordAccountStore;
use Tests\TestCase;

class ResetPasswordAccountStoreTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Store\ResetPasswordAccountStore $resetPasswordAccountStore */
        $resetPasswordAccountStore = new ResetPasswordAccountStore();
        $this->assertNotNull($resetPasswordAccountStore);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Store\ResetPasswordAccountStore $resetPasswordAccountStore */
        $resetPasswordAccountStoreModel = new ResetPasswordAccountStore();

        $resetPasswordAccountStoreData = factory(ResetPasswordAccountStore::class)->make();
        foreach( $resetPasswordAccountStoreData->toFillableArray() as $key => $value ) {
            $resetPasswordAccountStoreModel->$key = $value;
        }
        $resetPasswordAccountStoreModel->save();

        $this->assertNotNull(ResetPasswordAccountStore::find($resetPasswordAccountStoreModel->id));
    }

}
