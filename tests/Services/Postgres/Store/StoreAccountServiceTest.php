<?php namespace App\Services\Postgres\Store\Production;

use Tests\TestCase;

class StoreAccountServiceTest extends TestCase
{

    public function testGetInstance()
    {
        /** @var  \App\Services\Postgres\Store\StoreAccountServiceInterface $service */
        $service = \App::make(\App\Services\Postgres\Store\StoreAccountServiceInterface::class);
        $this->assertNotNull($service);
    }

}
