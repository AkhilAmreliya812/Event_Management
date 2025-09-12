<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisterConfirmation extends Mailable
{
    use Queueable, SerializesModels;
    public $name, $event_title, $start_date, $end_date, $registration_id;
    /**
     * Create a new message instance.
     */
    public function __construct($name, $event_title, $start_date, $end_date, $registration_id)
    {
        $this->name = $name;
        $this->event_title = $event_title;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->registration_id = $registration_id;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Registration for '. $this->event_title .' is Confirmed!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'user.email.register_confirmation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
