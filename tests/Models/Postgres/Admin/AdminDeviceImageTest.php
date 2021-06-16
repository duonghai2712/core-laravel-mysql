<?php namespace tests\models\Postgres\Admin;

use App\Models\Postgres\Admin\AdminDeviceImage;
use Tests\TestCase;

class AdminDeviceImageTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Admin\AdminDeviceImage $adminDeviceImage */
        $adminDeviceImage = new AdminDeviceImage();
        $this->assertNotNull($adminDeviceImage);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Admin\AdminDeviceImage $adminDeviceImage */
        $adminDeviceImageModel = new AdminDeviceImage();

        $adminDeviceImageData = factory(AdminDeviceImage::class)->make();
        foreach( $adminDeviceImageData->toFillableArray() as $key => $value ) {
            $adminDeviceImageModel->$key = $value;
        }
        $adminDeviceImageModel->save();

        $this->assertNotNull(AdminDeviceImage::find($adminDeviceImageModel->id));
    }

}
