<?php namespace Tests\Models\Postgres\Admin;

use App\Models\Postgres\Admin\Rank;
use Tests\TestCase;

class RankTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Admin\Rank $rank */
        $rank = new Rank();
        $this->assertNotNull($rank);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Admin\Rank $rank */
        $rankModel = new Rank();

        $rankData = factory(Rank::class)->make();
        foreach( $rankData->toFillableArray() as $key => $value ) {
            $rankModel->$key = $value;
        }
        $rankModel->save();

        $this->assertNotNull(Rank::find($rankModel->id));
    }

}
