<?php namespace Tests\Models\Postgres\Admin;

use App\Models\Postgres\Province;
use Tests\TestCase;

class ProvinceTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Province $province */
        $province = new Province();
        $this->assertNotNull($province);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Province $province */
        $provinceModel = new Province();

        $provinceData = factory(Province::class)->make();
        foreach( $provinceData->toFillableArray() as $key => $value ) {
            $provinceModel->$key = $value;
        }
        $provinceModel->save();

        $this->assertNotNull(Province::find($provinceModel->id));
    }

}
