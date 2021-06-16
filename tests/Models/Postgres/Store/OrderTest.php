<?php namespace tests\models\Postgres\Store;

use App\Models\Postgres\Store\Order;
use Tests\TestCase;

class OrderTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Store\Order $order */
        $order = new Order();
        $this->assertNotNull($order);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Store\Order $order */
        $orderModel = new Order();

        $orderData = factory(Order::class)->make();
        foreach( $orderData->toFillableArray() as $key => $value ) {
            $orderModel->$key = $value;
        }
        $orderModel->save();

        $this->assertNotNull(Order::find($orderModel->id));
    }

}
