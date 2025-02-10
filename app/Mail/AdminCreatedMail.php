<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $admin;
    public $password;

    public function __construct(User $admin, $password)
    {
        $this->admin = $admin;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Your Admin Account Has Been Created')
                    ->view('emails.admin_created')
                    ->with([
                        'name' => $this->admin->name,
                        'email' => $this->admin->email,
                        'password' => $this->password,
                        'loginUrl' => route('login'),
                    ]);
    }
}
