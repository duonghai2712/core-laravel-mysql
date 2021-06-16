<?php namespace tests\models\Postgres\Store;

use App\Models\Postgres\Store\OrderDevice;
use Tests\TestCase;

class OrderDeviceTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Store\OrderDevice $orderDevice */
        $orderDevice = new OrderDevice();
        $this->assertNotNull($orderDevice);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Store\OrderDevice $orderDevice */
        $orderDeviceModel = new OrderDevice();

        $orderDeviceData = factory(OrderDevice::class)->make();
        foreach( $orderDeviceData->toFillableArray() as $key => $value ) {
            $orderDeviceModel->$key = $value;
        }
        $orderDeviceModel->save();

        $this->assertNotNull(OrderDevice::find($orderDeviceModel->id));
    }

}
