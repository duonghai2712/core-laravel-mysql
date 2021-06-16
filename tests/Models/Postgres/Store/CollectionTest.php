<?php namespace tests\models\Postgres\Store;

use App\Models\Postgres\Store\Collection;
use Tests\TestCase;

class CollectionTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Store\Collection $collection */
        $collection = new Collection();
        $this->assertNotNull($collection);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Store\Collection $collection */
        $collectionModel = new Collection();

        $collectionData = factory(Collection::class)->make();
        foreach( $collectionData->toFillableArray() as $key => $value ) {
            $collectionModel->$key = $value;
        }
        $collectionModel->save();

        $this->assertNotNull(Collection::find($collectionModel->id));
    }

}
