<?php namespace Tests\Models\Postgres\Admin;

use App\Models\Postgres\Admin\Image;
use Tests\TestCase;

class ImageTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Admin\Image $image */
        $image = new Image();
        $this->assertNotNull($image);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Admin\Image $image */
        $imageModel = new Image();

        $imageData = factory(Image::class)->make();
        foreach( $imageData->toFillableArray() as $key => $value ) {
            $imageModel->$key = $value;
        }
        $imageModel->save();

        $this->assertNotNull(Image::find($imageModel->id));
    }

}
