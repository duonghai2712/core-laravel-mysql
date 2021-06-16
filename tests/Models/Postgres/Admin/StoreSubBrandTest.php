<?php namespace Tests\Models\Postgres\Admin;

use App\Models\Postgres\Admin\StoreSubBrand;
use Tests\TestCase;

class StoreSubBrandTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Admin\StoreSubBrand $storeSubBrand */
        $storeSubBrand = new StoreSubBrand();
        $this->assertNotNull($storeSubBrand);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Admin\StoreSubBrand $storeSubBrand */
        $storeSubBrandModel = new StoreSubBrand();

        $storeSubBrandData = factory(StoreSubBrand::class)->make();
        foreach( $storeSubBrandData->toFillableArray() as $key => $value ) {
            $storeSubBrandModel->$key = $value;
        }
        $storeSubBrandModel->save();

        $this->assertNotNull(StoreSubBrand::find($storeSubBrandModel->id));
    }

}
