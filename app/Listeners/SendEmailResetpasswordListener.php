<?php

namespace App\Listeners;

use App\Events\SendEmailResetpasswordEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\ResetPassword as MailResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class SendEmailResetpasswordListener implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  SendEmailResetpasswordEvent  $event
     * @return void
     */
    public function handle(SendEmailResetpasswordEvent $event)
    {
        $email = $event->params['email'];
        $token = $event->params['token'];
        $type = $event->params['type'];
        $username = $event->params['username'];
        if (!empty($email) && !empty($token) && !empty($type) && !empty($username)){
            Config::set('mail.from.address','hello@adtgroup.vn');
            Config::set('mail.from.name','ADT Creative');
            Mail::to($email)->send(new MailResetPassword($email, $username, $token, $type));
        }

    }
}
