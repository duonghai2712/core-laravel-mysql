<?php namespace tests\models\Postgres\Store;

use App\Models\Postgres\Store\OrderStore;
use Tests\TestCase;

class OrderStoreTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Store\OrderStore $orderStore */
        $orderStore = new OrderStore();
        $this->assertNotNull($orderStore);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Store\OrderStore $orderStore */
        $orderStoreModel = new OrderStore();

        $orderStoreData = factory(OrderStore::class)->make();
        foreach( $orderStoreData->toFillableArray() as $key => $value ) {
            $orderStoreModel->$key = $value;
        }
        $orderStoreModel->save();

        $this->assertNotNull(OrderStore::find($orderStoreModel->id));
    }

}
