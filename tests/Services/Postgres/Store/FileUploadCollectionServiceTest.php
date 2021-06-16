<?php namespace App\Services\Postgres\Store\Production;

use Tests\TestCase;

class FileUploadCollectionServiceTest extends TestCase
{

    public function testGetInstance()
    {
        /** @var  \App\Services\Postgres\Store\FileUploadCollectionServiceInterface $service */
        $service = \App::make(\App\Services\Postgres\Store\FileUploadCollectionServiceInterface::class);
        $this->assertNotNull($service);
    }

}
