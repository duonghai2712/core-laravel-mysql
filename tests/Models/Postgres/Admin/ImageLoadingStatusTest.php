<?php namespace tests\models\Postgres\Admin;

use App\Models\Postgres\Admin\ImageLoadingStatus;
use Tests\TestCase;

class ImageLoadingStatusTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Admin\ImageLoadingStatus $imageLoadingStatus */
        $imageLoadingStatus = new ImageLoadingStatus();
        $this->assertNotNull($imageLoadingStatus);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Admin\ImageLoadingStatus $imageLoadingStatus */
        $imageLoadingStatusModel = new ImageLoadingStatus();

        $imageLoadingStatusData = factory(ImageLoadingStatus::class)->make();
        foreach( $imageLoadingStatusData->toFillableArray() as $key => $value ) {
            $imageLoadingStatusModel->$key = $value;
        }
        $imageLoadingStatusModel->save();

        $this->assertNotNull(ImageLoadingStatus::find($imageLoadingStatusModel->id));
    }

}
