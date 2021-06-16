<?php namespace Tests\Services\Postgres\Admin;


use Tests\TestCase;

class ImageServiceTest extends TestCase
{

    public function testGetInstance()
    {
        /** @var  \App\Services\Postgres\Admin\ImageServiceInterface $service */
        $service = \App::make(\App\Services\Postgres\Admin\ImageServiceInterface::class);
        $this->assertNotNull($service);
    }

}
