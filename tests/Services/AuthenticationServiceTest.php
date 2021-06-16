<?php namespace Tests\Services;

use Tests\TestCase;

class AuthenticationServiceTest extends TestCase
{

    public function testGetInstance()
    {
        /** @var  \App\Services\AuthenticationServiceInterface $service */
        $service = \App::make(\App\Services\AuthenticationServiceInterface::class);
        $this->assertNotNull($service);
    }

}
