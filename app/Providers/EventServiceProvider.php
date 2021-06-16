<?php

namespace App\Providers;


use App\Events\SendEmailDeviceEvent;
use App\Events\SendEmailOrderEvent;
use App\Events\SendEmailRegistrationAccountEvent;
use App\Events\SendEmailResetPasswordEvent;
use App\Listeners\SendEmailDeviceListener;
use App\Listeners\SendEmailOrderListener;
use App\Listeners\SendEmailRegistrationAccountListener;
use App\Listeners\SendEmailResetpasswordListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        SendEmailRegistrationAccountEvent::class => [
            SendEmailRegistrationAccountListener::class,
        ],

        SendEmailResetPasswordEvent::class => [
            SendEmailResetpasswordListener::class,
        ],

        SendEmailDeviceEvent::class => [
            SendEmailDeviceListener::class
        ],

        SendEmailOrderEvent::class => [
            SendEmailOrderListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
