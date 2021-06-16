<?php

namespace App\Listeners;

use App\Events\SendEmailRegistrationAccountEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Mail\RegistrationAccount as MailRegistrationAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class SendEmailRegistrationAccountListener implements ShouldQueue
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
     * @param  SendEmailRegistrationAccountEvent  $event
     * @return void
     */
    public function handle(SendEmailRegistrationAccountEvent $event)
    {
        $account = $event->params['account'];
        $type = $event->params['type'];
        if (!empty($account) && !empty($type) && !empty($account['email'])){
            Config::set('mail.from.address','hello@adtgroup.vn');
            Config::set('mail.from.name','ADT Creative');
            Mail::to($account['email'])->send(new MailRegistrationAccount($account, $type));
        }
    }
}
