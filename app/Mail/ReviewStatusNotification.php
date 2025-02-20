<?php

namespace App\Mail;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewStatusNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $review;

    /**
     * Create a new message instance.
     */
    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Review Status Update')
                    ->view('emails.review_status')
                    ->with([
                        'userName' => $this->review->user->name,
                        'bookTitle' => $this->review->book->title,
                        'reviewStatus' => ucfirst($this->review->status),
                        'adminJustification' => $this->review->admin_justification ?? 'No justification provided.',
                        'reviewLink' => route('books.show', $this->review->book->id),
                    ]);
    }
}
