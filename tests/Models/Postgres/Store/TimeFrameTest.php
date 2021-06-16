<?php namespace tests\models\Postgres\Store;

use App\Models\Postgres\Store\TimeFrame;
use Tests\TestCase;

class TimeFrameTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Store\TimeFrame $timeFrame */
        $timeFrame = new TimeFrame();
        $this->assertNotNull($timeFrame);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Store\TimeFrame $timeFrame */
        $timeFrameModel = new TimeFrame();

        $timeFrameData = factory(TimeFrame::class)->make();
        foreach( $timeFrameData->toFillableArray() as $key => $value ) {
            $timeFrameModel->$key = $value;
        }
        $timeFrameModel->save();

        $this->assertNotNull(TimeFrame::find($timeFrameModel->id));
    }

}
