<?php namespace tests\models\Postgres\Admin;

use App\Models\Postgres\Admin\DeviceStatistic;
use Tests\TestCase;

class DeviceStatisticTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Admin\DeviceStatistic $deviceStatistic */
        $deviceStatistic = new DeviceStatistic();
        $this->assertNotNull($deviceStatistic);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Admin\DeviceStatistic $deviceStatistic */
        $deviceStatisticModel = new DeviceStatistic();

        $deviceStatisticData = factory(DeviceStatistic::class)->make();
        foreach( $deviceStatisticData->toFillableArray() as $key => $value ) {
            $deviceStatisticModel->$key = $value;
        }
        $deviceStatisticModel->save();

        $this->assertNotNull(DeviceStatistic::find($deviceStatisticModel->id));
    }

}
