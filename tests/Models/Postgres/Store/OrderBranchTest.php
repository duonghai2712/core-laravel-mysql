<?php namespace tests\models\Postgres\Store;

use App\Models\Postgres\Store\OrderBranch;
use Tests\TestCase;

class OrderBranchTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Store\OrderBranch $orderBranch */
        $orderBranch = new OrderBranch();
        $this->assertNotNull($orderBranch);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Store\OrderBranch $orderBranch */
        $orderBranchModel = new OrderBranch();

        $orderBranchData = factory(OrderBranch::class)->make();
        foreach( $orderBranchData->toFillableArray() as $key => $value ) {
            $orderBranchModel->$key = $value;
        }
        $orderBranchModel->save();

        $this->assertNotNull(OrderBranch::find($orderBranchModel->id));
    }

}
