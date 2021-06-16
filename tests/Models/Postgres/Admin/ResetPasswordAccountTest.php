<?php namespace tests\models\Postgres\Admin;

use App\Models\Postgres\Admin\ResetPasswordAccount;
use Tests\TestCase;

class ResetPasswordAccountTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Admin\ResetPasswordAccount $resetPasswordAccount */
        $resetPasswordAccount = new ResetPasswordAccount();
        $this->assertNotNull($resetPasswordAccount);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Admin\ResetPasswordAccount $resetPasswordAccount */
        $resetPasswordAccountModel = new ResetPasswordAccount();

        $resetPasswordAccountData = factory(ResetPasswordAccount::class)->make();
        foreach( $resetPasswordAccountData->toFillableArray() as $key => $value ) {
            $resetPasswordAccountModel->$key = $value;
        }
        $resetPasswordAccountModel->save();

        $this->assertNotNull(ResetPasswordAccount::find($resetPasswordAccountModel->id));
    }

}
