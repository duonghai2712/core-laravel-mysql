<?php

namespace App\Listeners;

use App\Events\SendEmailOrderEvent;
use App\Mail\Order as MailOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class SendEmailOrderListener implements ShouldQueue
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
     * @param  SendEmailOrderEvent  $event
     * @return void
     */
    public function handle(SendEmailOrderEvent $event)
    {
        $email = $event->params['email'];
        $type = $event->params['type'];
        $title = $event->params['title'];
        $order_code = $event->params['order_code'];
        $username = $event->params['username'];
        $url = $event->params['url'];
        if (!empty($email) && !empty($type) && !empty($title) && !empty($order_code) && !empty($url) && !empty($username)){
            Config::set('mail.from.address','hello@adtgroup.vn');
            Config::set('mail.from.name','ADT Creative');
            Mail::to($email)->send(new MailOrder($email, $username, $type, $title, $order_code, $url));
        }
    }
}
