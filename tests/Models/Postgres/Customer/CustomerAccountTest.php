<?php namespace tests\models\Postgres\Customer;

use App\Models\Postgres\Customer\CustomerAccount;
use Tests\TestCase;

class CustomerAccountTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Customer\CustomerAccount $customerAccount */
        $customerAccount = new CustomerAccount();
        $this->assertNotNull($customerAccount);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Customer\CustomerAccount $customerAccount */
        $customerAccountModel = new CustomerAccount();

        $customerAccountData = factory(CustomerAccount::class)->make();
        foreach( $customerAccountData->toFillableArray() as $key => $value ) {
            $customerAccountModel->$key = $value;
        }
        $customerAccountModel->save();

        $this->assertNotNull(CustomerAccount::find($customerAccountModel->id));
    }

}
