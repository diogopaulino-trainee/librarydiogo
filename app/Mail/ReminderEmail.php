<?php

namespace App\Mail;

use App\Models\Request;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function build()
    {
        $coverImagePath = public_path('images/' . $this->request->book->cover_image);
        $coverImageUrl = asset('images/' . $this->request->book->cover_image);

        return $this->subject('Reminder: Book Return Due Tomorrow 📅')
                    ->markdown('emails.reminder')
                    ->with([
                        'name' => $this->request->user->name,
                        'bookTitle' => $this->request->book->title,
                        'expectedReturnDate' => Carbon::parse($this->request->expected_return_date)->format('Y-m-d'),
                        'coverImage' => $coverImageUrl,
                    ])
                    ->attach($coverImagePath, [
                        'as' => 'book_cover.jpg',
                        'mime' => 'image/jpeg',
                    ]);
    }
}
