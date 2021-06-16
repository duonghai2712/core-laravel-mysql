<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Device extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $title;
    protected $type;
    protected $device_name;
    protected $status;

    public function __construct($title, $type, $device_name, $status)
    {
        $this->device_name = $device_name;
        $this->type = $type;
        $this->title = $title;
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $type = $this->type;
        $title = $this->title;
        $device_name = $this->device_name;
        $status = $this->status;

        return $this->view('mail.notification-device', ['type' => $type, 'title' => $title, 'device_name' => $device_name, 'status' => $status])->subject($title);
    }
}
