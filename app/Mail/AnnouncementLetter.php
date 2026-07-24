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
    public $filenames;

    /**
     * Create a new message instance.
     */
    public function __construct($programme, $filenames , $subject='Programme Announcement Letter')
    {
        $this->programme = $programme;
        $this->filenames = $filenames;
		$this->subject=$subject;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject:$this->subject,
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
//  public function attachments(): array
// {
//     return [
//         Attachment::fromPath(public_path('announcements/' . $this->filename))
//                  ->as('Announcement_Letter.pdf')
//                  ->withMime('application/pdf'),
//     ];
// }


  public function attachments(): array
  {
    $attachments = [];

    if (!empty($this->filenames)) {
        foreach ($this->filenames as $file) {

            $attachments[] = Attachment::fromPath(
                public_path('announcements/' . $file)
            )
            ->as($file) // original filename
            ->withMime(mime_content_type(public_path('announcements/' . $file)));
        }
    }

    return $attachments;
  }
}
