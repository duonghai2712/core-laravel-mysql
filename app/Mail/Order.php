<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Order extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $email;
    protected $type;
    protected $title;
    protected $order_code;
    protected $username;
    protected $url;

    public function __construct($email, $username, $type, $title, $order_code, $url)
    {
        $this->email = $email;
        $this->username = $username;
        $this->type = $type;
        $this->title = $title;
        $this->order_code = $order_code;
        $this->url = $url;
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
        $type = $this->type;
        $title = $this->title;
        $order_code = $this->order_code;
        $url = $this->url;

        return $this->view('mail.notification', ['email' => $email, 'username' => $username, 'type' => $type, 'title' => $title, 'order_code' => $order_code, 'url' => $url])->subject($title);
    }
}
