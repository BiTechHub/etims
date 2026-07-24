<?php

namespace App\Mail;

use App\Models\ProgrammeManagement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProgrammePostponedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $programme;

    /**
     * Create a new message instance.
     */
    public function __construct(ProgrammeManagement $programme)
    {
        $this->programme = $programme;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Programme Postponed Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.postpond',
            with: [
                'programme' => $this->programme,
            ],
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
