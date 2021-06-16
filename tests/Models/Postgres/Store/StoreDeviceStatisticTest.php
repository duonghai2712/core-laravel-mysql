<?php namespace tests\models\Postgres\Store;

use App\Models\Postgres\Store\StoreDeviceStatistic;
use Tests\TestCase;

class StoreDeviceStatisticTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Store\StoreDeviceStatistic $storeDeviceStatistic */
        $storeDeviceStatistic = new StoreDeviceStatistic();
        $this->assertNotNull($storeDeviceStatistic);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Store\StoreDeviceStatistic $storeDeviceStatistic */
        $storeDeviceStatisticModel = new StoreDeviceStatistic();

        $storeDeviceStatisticData = factory(StoreDeviceStatistic::class)->make();
        foreach( $storeDeviceStatisticData->toFillableArray() as $key => $value ) {
            $storeDeviceStatisticModel->$key = $value;
        }
        $storeDeviceStatisticModel->save();

        $this->assertNotNull(StoreDeviceStatistic::find($storeDeviceStatisticModel->id));
    }

}
