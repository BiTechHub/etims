<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;

class AnnouncementLetter extends Mailable
{
    use Queueable, SerializesModels;

    public $programme;
    public $filename;

    /**
     * Create a new message instance.
     */
    public function __construct($programme, $filename)
    {
        $this->programme = $programme;
        $this->filename = $filename;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Programme Announcement Letter'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'announcement', // Ensure this Blade file exists
            with: [
                'programme' => $this->programme
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
 public function attachments(): array
{
    return [
        Attachment::fromPath(public_path('announcements/' . $this->filename))
                 ->as('Announcement_Letter.pdf')
                 ->withMime('application/pdf'),
    ];
}
}
