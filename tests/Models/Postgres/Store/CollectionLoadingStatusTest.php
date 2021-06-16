<?php namespace tests\models\Postgres\Store;

use App\Models\Postgres\Store\CollectionLoadingStatus;
use Tests\TestCase;

class CollectionLoadingStatusTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Store\CollectionLoadingStatus $collectionLoadingStatus */
        $collectionLoadingStatus = new CollectionLoadingStatus();
        $this->assertNotNull($collectionLoadingStatus);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Store\CollectionLoadingStatus $collectionLoadingStatus */
        $collectionLoadingStatusModel = new CollectionLoadingStatus();

        $collectionLoadingStatusData = factory(CollectionLoadingStatus::class)->make();
        foreach( $collectionLoadingStatusData->toFillableArray() as $key => $value ) {
            $collectionLoadingStatusModel->$key = $value;
        }
        $collectionLoadingStatusModel->save();

        $this->assertNotNull(CollectionLoadingStatus::find($collectionLoadingStatusModel->id));
    }

}
