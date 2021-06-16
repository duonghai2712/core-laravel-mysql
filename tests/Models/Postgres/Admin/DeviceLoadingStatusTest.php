<?php namespace tests\models\Postgres\Admin;

use App\Models\Postgres\Admin\DeviceLoadingStatus;
use Tests\TestCase;

class DeviceLoadingStatusTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Admin\DeviceLoadingStatus $deviceLoadingStatus */
        $deviceLoadingStatus = new DeviceLoadingStatus();
        $this->assertNotNull($deviceLoadingStatus);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Admin\DeviceLoadingStatus $deviceLoadingStatus */
        $deviceLoadingStatusModel = new DeviceLoadingStatus();

        $deviceLoadingStatusData = factory(DeviceLoadingStatus::class)->make();
        foreach( $deviceLoadingStatusData->toFillableArray() as $key => $value ) {
            $deviceLoadingStatusModel->$key = $value;
        }
        $deviceLoadingStatusModel->save();

        $this->assertNotNull(DeviceLoadingStatus::find($deviceLoadingStatusModel->id));
    }

}
