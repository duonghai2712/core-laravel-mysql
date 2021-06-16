<?php

namespace App\Listeners;

use App\Events\SendEmailDeviceEvent;
use App\Mail\Device as MailDevice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class SendEmailDeviceListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function __construct()
    {
        //
    }
    /**
     * Handle the event.
     *
     * @param  SendEmailDeviceEvent  $event
     * @return void
     */
    public function handle(SendEmailDeviceEvent $event)
    {
        $title = $event->params['title'];
        $type = $event->params['type'];
        $device_name = $event->params['device_name'];
        $status = $event->params['status'];
        $email = $event->params['email'];
        if (!empty($title) && !empty($type) && !empty($device_name) && !empty($status) && !empty($email)){
            Config::set('mail.from.address','hello@adtgroup.vn');
            Config::set('mail.from.name','ADT Creative');
            Mail::to($email)->send(new MailDevice($title, $type, $device_name, $status));
        }
    }
}
