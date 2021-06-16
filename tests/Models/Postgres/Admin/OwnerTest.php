<?php namespace tests\models\Postgres\Admin;

use App\Models\Postgres\Admin\Owner;
use Tests\TestCase;

class OwnerTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Admin\Owner $owner */
        $owner = new Owner();
        $this->assertNotNull($owner);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Admin\Owner $owner */
        $ownerModel = new Owner();

        $ownerData = factory(Owner::class)->make();
        foreach( $ownerData->toFillableArray() as $key => $value ) {
            $ownerModel->$key = $value;
        }
        $ownerModel->save();

        $this->assertNotNull(Owner::find($ownerModel->id));
    }

}
