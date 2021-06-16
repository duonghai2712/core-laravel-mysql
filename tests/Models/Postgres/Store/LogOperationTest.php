<?php namespace tests\models\Postgres\Store;

use App\Models\Postgres\Store\LogOperation;
use Tests\TestCase;

class LogOperationTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Store\LogOperation $logOperation */
        $logOperation = new LogOperation();
        $this->assertNotNull($logOperation);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Store\LogOperation $logOperation */
        $logOperationModel = new LogOperation();

        $logOperationData = factory(LogOperation::class)->make();
        foreach( $logOperationData->toFillableArray() as $key => $value ) {
            $logOperationModel->$key = $value;
        }
        $logOperationModel->save();

        $this->assertNotNull(LogOperation::find($logOperationModel->id));
    }

}
