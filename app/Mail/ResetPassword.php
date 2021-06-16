<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $email;
    protected $username;
    protected $token;
    protected $type;
    public function __construct($email, $username, $token, $type)
    {
        $this->email = $email;
        $this->username = $username;
        $this->token = $token;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->email;
        $username = $this->username;
        $token = $this->token;
        $type = $this->type;
        return $this->view('mail.reset-password', ['token' => $token, 'username' => $username, 'email' => $email, 'type' => $type])->subject('Email thay đổi mật khẩu');
    }
}
