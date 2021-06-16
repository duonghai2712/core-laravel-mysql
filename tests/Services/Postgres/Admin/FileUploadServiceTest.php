<?php namespace Tests\Services\Postgres\Admin;


use Tests\TestCase;

class FileUploadServiceTest extends TestCase
{

    public function testGetInstance()
    {
        /** @var  \App\Services\Postgres\Admin\FileUploadServiceInterface $service */
        $service = \App::make(\App\Services\Postgres\Admin\FileUploadServiceInterface::class);
        $this->assertNotNull($service);
    }

}
