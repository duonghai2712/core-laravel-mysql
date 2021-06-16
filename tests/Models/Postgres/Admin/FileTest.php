<?php namespace Tests\Models\Postgres\Admin;

use App\Models\Postgres\File;
use Tests\TestCase;

class FileTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\File $file */
        $file = new File();
        $this->assertNotNull($file);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\File $file */
        $fileModel = new File();

        $fileData = factory(File::class)->make();
        foreach( $fileData->toFillableArray() as $key => $value ) {
            $fileModel->$key = $value;
        }
        $fileModel->save();

        $this->assertNotNull(File::find($fileModel->id));
    }

}
