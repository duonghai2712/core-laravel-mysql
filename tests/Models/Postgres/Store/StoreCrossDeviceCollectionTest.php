<?php namespace tests\models\Postgres\Store;

use App\Models\Postgres\Store\StoreCrossDeviceCollection;
use Tests\TestCase;

class StoreCrossDeviceCollectionTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Store\StoreCrossDeviceCollection $storeCrossDeviceCollection */
        $storeCrossDeviceCollection = new StoreCrossDeviceCollection();
        $this->assertNotNull($storeCrossDeviceCollection);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Store\StoreCrossDeviceCollection $storeCrossDeviceCollection */
        $storeCrossDeviceCollectionModel = new StoreCrossDeviceCollection();

        $storeCrossDeviceCollectionData = factory(StoreCrossDeviceCollection::class)->make();
        foreach( $storeCrossDeviceCollectionData->toFillableArray() as $key => $value ) {
            $storeCrossDeviceCollectionModel->$key = $value;
        }
        $storeCrossDeviceCollectionModel->save();

        $this->assertNotNull(StoreCrossDeviceCollection::find($storeCrossDeviceCollectionModel->id));
    }

}
