<?php namespace Tests\Models\Postgres\Admin;

use App\Models\Postgres\Admin\BranchSubBrand;
use Tests\TestCase;

class BranchSubBrandTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Admin\BranchSubBrand $branchSubBrand */
        $branchSubBrand = new BranchSubBrand();
        $this->assertNotNull($branchSubBrand);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Admin\BranchSubBrand $branchSubBrand */
        $branchSubBrandModel = new BranchSubBrand();

        $branchSubBrandData = factory(BranchSubBrand::class)->make();
        foreach( $branchSubBrandData->toFillableArray() as $key => $value ) {
            $branchSubBrandModel->$key = $value;
        }
        $branchSubBrandModel->save();

        $this->assertNotNull(BranchSubBrand::find($branchSubBrandModel->id));
    }

}
