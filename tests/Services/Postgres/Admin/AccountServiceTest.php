<?php namespace Tests\Services\Postgres\Admin;

use Tests\TestCase;

class AccountServiceTest extends TestCase
{

    public function testGetInstance()
    {
        /** @var  \App\Services\Postgres\Admin\AccountServiceInterface $service */
        $service = \App::make(\App\Services\Postgres\Admin\AccountServiceInterface::class);
        $this->assertNotNull($service);
    }

}
