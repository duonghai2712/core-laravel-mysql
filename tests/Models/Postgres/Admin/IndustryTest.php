<?php namespace Tests\Models\Postgres\Admin;

use App\Models\Postgres\Industry;
use Tests\TestCase;

class IndustryTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Industry $industry */
        $industry = new Industry();
        $this->assertNotNull($industry);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Industry $industry */
        $industryModel = new Industry();

        $industryData = factory(Industry::class)->make();
        foreach( $industryData->toFillableArray() as $key => $value ) {
            $industryModel->$key = $value;
        }
        $industryModel->save();

        $this->assertNotNull(Industry::find($industryModel->id));
    }

}
