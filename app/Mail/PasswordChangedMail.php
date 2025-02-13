<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $resetUrl;

    public function __construct($user)
    {
        $this->user = $user;
        $this->resetUrl = url('http://librarydiogo.test/forgot-password');
    }

    public function build()
    {
        return $this->subject('Your Password Has Been Changed')
                    ->view('emails.password_changed')
                    ->with([
                        'name' => $this->user->name,
                        'resetUrl' => $this->resetUrl,
                    ]);
    }
}
