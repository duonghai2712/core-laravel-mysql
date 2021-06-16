<?php namespace Tests\Models\Postgres\Admin;

use App\Models\Postgres\Admin\Account;
use Tests\TestCase;

class AccountTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Admin\Account $account */
        $account = new Account();
        $this->assertNotNull($account);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Admin\Account $account */
        $accountModel = new Account();

        $accountData = factory(Account::class)->make();
        foreach( $accountData->toFillableArray() as $key => $value ) {
            $accountModel->$key = $value;
        }
        $accountModel->save();

        $this->assertNotNull(Account::find($accountModel->id));
    }

}
