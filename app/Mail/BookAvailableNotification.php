<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookAvailableNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $bookTitle;
    public $coverImage;
    public $coverImagePath;
    public $requestUrl;

    public function __construct($name, $bookTitle, $coverImage, $coverImagePath, $requestUrl)
    {
        $this->name = $name;
        $this->bookTitle = $bookTitle;
        $this->coverImage = $coverImage;
        $this->coverImagePath = $coverImagePath;
        $this->requestUrl = $requestUrl;
    }

    public function build()
    {
        return $this->view('emails.book_available')
            ->subject('Book Now Available: ' . $this->bookTitle)
            ->attach($this->coverImagePath, [
                'as' => 'book_cover.jpg',
                'mime' => 'image/jpeg',
            ]);
    }
}
