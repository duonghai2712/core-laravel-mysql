<?php namespace tests\models\Postgres\Store;

use App\Models\Postgres\Store\StoreCrossDeviceStatistic;
use Tests\TestCase;

class StoreCrossDeviceStatisticTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Store\StoreCrossDeviceStatistic $storeCrossDeviceStatistic */
        $storeCrossDeviceStatistic = new StoreCrossDeviceStatistic();
        $this->assertNotNull($storeCrossDeviceStatistic);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Store\StoreCrossDeviceStatistic $storeCrossDeviceStatistic */
        $storeCrossDeviceStatisticModel = new StoreCrossDeviceStatistic();

        $storeCrossDeviceStatisticData = factory(StoreCrossDeviceStatistic::class)->make();
        foreach( $storeCrossDeviceStatisticData->toFillableArray() as $key => $value ) {
            $storeCrossDeviceStatisticModel->$key = $value;
        }
        $storeCrossDeviceStatisticModel->save();

        $this->assertNotNull(StoreCrossDeviceStatistic::find($storeCrossDeviceStatisticModel->id));
    }

}
