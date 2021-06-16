<?php namespace Tests\Services;

use Tests\TestCase;

class BaseServiceTest extends TestCase
{

    public function testGetInstance()
    {
        /** @var  \App\Services\BaseServiceInterface $service */
        $service = \App::make(\App\Services\BaseServiceInterface::class);
        $this->assertNotNull($service);
    }

}
