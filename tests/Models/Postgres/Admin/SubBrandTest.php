<?php namespace Tests\Models\Postgres\Admin;

use App\Models\Postgres\Admin\SubBrand;
use Tests\TestCase;

class SubBrandTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Admin\SubBrand $subBrand */
        $subBrand = new SubBrand();
        $this->assertNotNull($subBrand);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Admin\SubBrand $subBrand */
        $subBrandModel = new SubBrand();

        $subBrandData = factory(SubBrand::class)->make();
        foreach( $subBrandData->toFillableArray() as $key => $value ) {
            $subBrandModel->$key = $value;
        }
        $subBrandModel->save();

        $this->assertNotNull(SubBrand::find($subBrandModel->id));
    }

}
