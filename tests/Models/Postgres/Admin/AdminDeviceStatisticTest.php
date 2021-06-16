<?php namespace tests\models\Postgres\Admin;

use App\Models\Postgres\Admin\AdminDeviceStatistic;
use Tests\TestCase;

class AdminDeviceStatisticTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Admin\AdminDeviceStatistic $adminDeviceStatistic */
        $adminDeviceStatistic = new AdminDeviceStatistic();
        $this->assertNotNull($adminDeviceStatistic);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Admin\AdminDeviceStatistic $adminDeviceStatistic */
        $adminDeviceStatisticModel = new AdminDeviceStatistic();

        $adminDeviceStatisticData = factory(AdminDeviceStatistic::class)->make();
        foreach( $adminDeviceStatisticData->toFillableArray() as $key => $value ) {
            $adminDeviceStatisticModel->$key = $value;
        }
        $adminDeviceStatisticModel->save();

        $this->assertNotNull(AdminDeviceStatistic::find($adminDeviceStatisticModel->id));
    }

}
