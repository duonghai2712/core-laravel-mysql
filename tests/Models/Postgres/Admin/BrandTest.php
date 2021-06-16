<?php namespace Tests\Models\Postgres\Admin;

use App\Models\Postgres\Admin\Brand;
use Tests\TestCase;

class BrandTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Admin\Brand $brand */
        $brand = new Brand();
        $this->assertNotNull($brand);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Admin\Brand $brand */
        $brandModel = new Brand();

        $brandData = factory(Brand::class)->make();
        foreach( $brandData->toFillableArray() as $key => $value ) {
            $brandModel->$key = $value;
        }
        $brandModel->save();

        $this->assertNotNull(Brand::find($brandModel->id));
    }

}
