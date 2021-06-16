<?php namespace tests\models\Postgres\Store;

use App\Models\Postgres\Store\LogPoint;
use Tests\TestCase;

class LogPointTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Store\LogPoint $logPoint */
        $logPoint = new LogPoint();
        $this->assertNotNull($logPoint);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Store\LogPoint $logPoint */
        $logPointModel = new LogPoint();

        $logPointData = factory(LogPoint::class)->make();
        foreach( $logPointData->toFillableArray() as $key => $value ) {
            $logPointModel->$key = $value;
        }
        $logPointModel->save();

        $this->assertNotNull(LogPoint::find($logPointModel->id));
    }

}
