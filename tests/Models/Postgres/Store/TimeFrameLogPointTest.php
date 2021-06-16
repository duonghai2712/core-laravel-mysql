<?php namespace tests\models\Postgres\Store;

use App\Models\Postgres\Store\TimeFrameLogPoint;
use Tests\TestCase;

class TimeFrameLogPointTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Store\TimeFrameLogPoint $timeFrameLogPoint */
        $timeFrameLogPoint = new TimeFrameLogPoint();
        $this->assertNotNull($timeFrameLogPoint);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Store\TimeFrameLogPoint $timeFrameLogPoint */
        $timeFrameLogPointModel = new TimeFrameLogPoint();

        $timeFrameLogPointData = factory(TimeFrameLogPoint::class)->make();
        foreach( $timeFrameLogPointData->toFillableArray() as $key => $value ) {
            $timeFrameLogPointModel->$key = $value;
        }
        $timeFrameLogPointModel->save();

        $this->assertNotNull(TimeFrameLogPoint::find($timeFrameLogPointModel->id));
    }

}
