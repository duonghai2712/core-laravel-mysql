<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegistrationAccount extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $account;
    protected $type;
    public function __construct($account, $type)
    {
        $this->account = $account;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $account = $this->account;
        $type = $this->type;
        return $this->view('mail.registration-account', ['account' => $account, 'type' => $type])->subject('Email khi mới tạo tài khoản');
    }
}
