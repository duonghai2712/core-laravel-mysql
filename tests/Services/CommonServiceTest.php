<?php namespace App\Services\Production;

use Tests\TestCase;

class CommonServiceTest extends TestCase
{

    public function testGetInstance()
    {
        /** @var  \App\Services\CommonServiceInterface $service */
        $service = \App::make(\App\Services\CommonServiceInterface::class);
        $this->assertNotNull($service);
    }

}
