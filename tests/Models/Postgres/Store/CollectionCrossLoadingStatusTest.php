<?php namespace tests\models\Postgres\Store;

use App\Models\Postgres\Store\CollectionCrossLoadingStatus;
use Tests\TestCase;

class CollectionCrossLoadingStatusTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Store\CollectionCrossLoadingStatus $collectionCrossLoadingStatus */
        $collectionCrossLoadingStatus = new CollectionCrossLoadingStatus();
        $this->assertNotNull($collectionCrossLoadingStatus);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Store\CollectionCrossLoadingStatus $collectionCrossLoadingStatus */
        $collectionCrossLoadingStatusModel = new CollectionCrossLoadingStatus();

        $collectionCrossLoadingStatusData = factory(CollectionCrossLoadingStatus::class)->make();
        foreach( $collectionCrossLoadingStatusData->toFillableArray() as $key => $value ) {
            $collectionCrossLoadingStatusModel->$key = $value;
        }
        $collectionCrossLoadingStatusModel->save();

        $this->assertNotNull(CollectionCrossLoadingStatus::find($collectionCrossLoadingStatusModel->id));
    }

}
