<?php namespace tests\models\Postgres\Store;

use App\Models\Postgres\Store\StoreAccount;
use Tests\TestCase;

class StoreAccountTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Store\StoreAccount $storeAccount */
        $storeAccount = new StoreAccount();
        $this->assertNotNull($storeAccount);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Store\StoreAccount $storeAccount */
        $storeAccountModel = new StoreAccount();

        $storeAccountData = factory(StoreAccount::class)->make();
        foreach( $storeAccountData->toFillableArray() as $key => $value ) {
            $storeAccountModel->$key = $value;
        }
        $storeAccountModel->save();

        $this->assertNotNull(StoreAccount::find($storeAccountModel->id));
    }

}
