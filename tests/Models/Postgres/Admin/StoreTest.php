<?php namespace Tests\Models\Postgres\Admin;

use App\Models\Postgres\Admin\Store;
use Tests\TestCase;

class StoreTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Admin\Store $store */
        $store = new Store();
        $this->assertNotNull($store);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Admin\Store $store */
        $storeModel = new Store();

        $storeData = factory(Store::class)->make();
        foreach( $storeData->toFillableArray() as $key => $value ) {
            $storeModel->$key = $value;
        }
        $storeModel->save();

        $this->assertNotNull(Store::find($storeModel->id));
    }

}
