<?php namespace Tests\Services\Postgres\Admin;

use Tests\TestCase;

class StoreServiceTest extends TestCase
{

    public function testGetInstance()
    {
        /** @var  \App\Services\Postgres\Admin\StoreServiceInterface $service */
        $service = \App::make(\App\Services\Postgres\Admin\StoreServiceInterface::class);
        $this->assertNotNull($service);
    }

}
