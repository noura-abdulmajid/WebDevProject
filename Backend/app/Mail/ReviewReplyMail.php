<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $review;
    public $reply;

    /**
     * Create a new message instance.
     */
    public function __construct($review, $reply)
    {
        $this->review = $review;
        $this->reply = $reply;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Review Reply')
            ->view('emails.review_reply')
            ->with([
                'review' => $this->review,
                'reply' => $this->reply,
            ]);
    }
}