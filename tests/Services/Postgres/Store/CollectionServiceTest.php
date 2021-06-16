<?php namespace App\Services\Postgres\Store\Production;

use Tests\TestCase;

class CollectionServiceTest extends TestCase
{

    public function testGetInstance()
    {
        /** @var  \App\Services\Postgres\Store\CollectionServiceInterface $service */
        $service = \App::make(\App\Services\Postgres\Store\CollectionServiceInterface::class);
        $this->assertNotNull($service);
    }

}
