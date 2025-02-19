<?php

namespace App\Mail;

use App\Models\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function build()
    {
        $coverImagePath = public_path($this->request->book->cover_image);
        $coverImageUrl = asset($this->request->book->cover_image);

        $email = $this->subject('Book Request Confirmation')
                    ->markdown('emails.request_confirmation')
                    ->with([
                        'name' => optional($this->request->user)->name ?? 'Unknown User',
                        'bookTitle' => $this->request->book->title,
                        'requestDate' => $this->request->request_date->format('Y-m-d'),
                        'expectedReturnDate' => $this->request->expected_return_date->format('Y-m-d'),
                        'coverImage' => $coverImageUrl,
                        'resetUrl' => url('http://librarydiogo.test/forgot-password'),
                    ]);

        if (file_exists($coverImagePath)) {
            $email->attach($coverImagePath, [
                'as' => 'book_cover.jpg',
                'mime' => 'image/jpeg',
            ]);
        }

        return $email;
    }
}
