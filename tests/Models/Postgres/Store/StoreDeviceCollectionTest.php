<?php namespace tests\models\Postgres\Store;

use App\Models\Postgres\Store\StoreDeviceCollection;
use Tests\TestCase;

class StoreDeviceCollectionTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Store\StoreDeviceCollection $storeDeviceCollection */
        $storeDeviceCollection = new StoreDeviceCollection();
        $this->assertNotNull($storeDeviceCollection);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Store\StoreDeviceCollection $storeDeviceCollection */
        $storeDeviceCollectionModel = new StoreDeviceCollection();

        $storeDeviceCollectionData = factory(StoreDeviceCollection::class)->make();
        foreach( $storeDeviceCollectionData->toFillableArray() as $key => $value ) {
            $storeDeviceCollectionModel->$key = $value;
        }
        $storeDeviceCollectionModel->save();

        $this->assertNotNull(StoreDeviceCollection::find($storeDeviceCollectionModel->id));
    }

}
