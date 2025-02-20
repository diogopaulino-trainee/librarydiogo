<?php

namespace App\Mail;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $review;
    public $reviewLink;
    public $manageReviewsLink;

    /**
     * Create a new message instance.
     */
    public function __construct(Review $review, $reviewLink, $manageReviewsLink)
    {
        $this->review = $review;
        $this->reviewLink = $reviewLink;
        $this->manageReviewsLink = $manageReviewsLink;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Book Review Submitted')
                    ->view('emails.review_notification')
                    ->with([
                        'userName' => $this->review->user->name,
                        'bookTitle' => $this->review->book->title,
                        'reviewContent' => $this->review->comment,
                        'reviewLink' => route('admin.reviews.index'),
                    ]);
    }
}
