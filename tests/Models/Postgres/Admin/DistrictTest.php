<?php namespace Tests\Models\Postgres\Admin;

use App\Models\Postgres\District;
use Tests\TestCase;

class DistrictTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\District $district */
        $district = new District();
        $this->assertNotNull($district);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\District $district */
        $districtModel = new District();

        $districtData = factory(District::class)->make();
        foreach( $districtData->toFillableArray() as $key => $value ) {
            $districtModel->$key = $value;
        }
        $districtModel->save();

        $this->assertNotNull(District::find($districtModel->id));
    }

}
