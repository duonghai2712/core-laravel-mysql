<?php namespace Tests\Models\Postgres\Admin;

use App\Models\Postgres\Admin\StoreBrand;
use Tests\TestCase;

class StoreBrandTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Admin\StoreBrand $storeBrand */
        $storeBrand = new StoreBrand();
        $this->assertNotNull($storeBrand);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Admin\StoreBrand $storeBrand */
        $storeBrandModel = new StoreBrand();

        $storeBrandData = factory(StoreBrand::class)->make();
        foreach( $storeBrandData->toFillableArray() as $key => $value ) {
            $storeBrandModel->$key = $value;
        }
        $storeBrandModel->save();

        $this->assertNotNull(StoreBrand::find($storeBrandModel->id));
    }

}
