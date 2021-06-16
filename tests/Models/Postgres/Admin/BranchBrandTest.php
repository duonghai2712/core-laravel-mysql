<?php namespace Tests\Models\Postgres\Admin;

use App\Models\Postgres\Admin\BranchBrand;
use Tests\TestCase;

class BranchBrandTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Admin\BranchBrand $branchBrand */
        $branchBrand = new BranchBrand();
        $this->assertNotNull($branchBrand);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Admin\BranchBrand $branchBrand */
        $branchBrandModel = new BranchBrand();

        $branchBrandData = factory(BranchBrand::class)->make();
        foreach( $branchBrandData->toFillableArray() as $key => $value ) {
            $branchBrandModel->$key = $value;
        }
        $branchBrandModel->save();

        $this->assertNotNull(BranchBrand::find($branchBrandModel->id));
    }

}
