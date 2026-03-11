<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Registration $registration;

    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎟 New Free Ticket Registration — ' . $this->registration->full_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-registration',
            with: [
                'registration' => $this->registration,
            ],
        );
    }
}
