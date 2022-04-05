<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Settings;

class Notification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user = '')
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $settings = Settings::where('id', 1)->first();
        return $this
            ->from($settings->email_address, $settings->email_name)
            ->subject($settings->email_subject)
            ->view('email.notification', [
                'settings' => $settings,
                'user' => $this->user
            ]);
    }
}
