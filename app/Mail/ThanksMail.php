<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ThanksMail extends Mailable
{
    use Queueable, SerializesModels;

    public $products;
    public $user;

    public function __construct($products, $user)
    {
        $this->products = $products;
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'ご購入ありがとうございました。',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.thanks',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
