<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookReturnConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $bookTitle;
    public $bookUrl;
    public $reviewUrl;

    public function __construct($name, $bookTitle, $bookUrl, $reviewUrl = null)
    {
        $this->name = $name;
        $this->bookTitle = $bookTitle;
        $this->bookUrl = $bookUrl;
        $this->reviewUrl = $reviewUrl;
    }

    public function build()
    {
        return $this->subject('Your Book Return is Confirmed')
            ->view('emails.book_return_confirmation');
    }
}
