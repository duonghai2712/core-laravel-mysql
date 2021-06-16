<?php namespace tests\models\Postgres\Store;

use App\Models\Postgres\Store\GroupStoreAccount;
use Tests\TestCase;

class GroupStoreAccountTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Store\GroupStoreAccount $groupStoreAccount */
        $groupStoreAccount = new GroupStoreAccount();
        $this->assertNotNull($groupStoreAccount);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Store\GroupStoreAccount $groupStoreAccount */
        $groupStoreAccountModel = new GroupStoreAccount();

        $groupStoreAccountData = factory(GroupStoreAccount::class)->make();
        foreach( $groupStoreAccountData->toFillableArray() as $key => $value ) {
            $groupStoreAccountModel->$key = $value;
        }
        $groupStoreAccountModel->save();

        $this->assertNotNull(GroupStoreAccount::find($groupStoreAccountModel->id));
    }

}
