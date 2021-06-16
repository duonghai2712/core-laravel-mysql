<?php namespace Tests\Models\Postgres\Admin;

use App\Models\Postgres\Admin\Branch;
use Tests\TestCase;

class BranchTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Admin\Branch $branch */
        $branch = new Branch();
        $this->assertNotNull($branch);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Admin\Branch $branch */
        $branchModel = new Branch();

        $branchData = factory(Branch::class)->make();
        foreach( $branchData->toFillableArray() as $key => $value ) {
            $branchModel->$key = $value;
        }
        $branchModel->save();

        $this->assertNotNull(Branch::find($branchModel->id));
    }

}
