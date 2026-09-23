<?php

namespace App\Mail;

use App\Models\ProgrammeManagement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProgrammePostponedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $programme;
    public $status;
    public $remark;
    public $filePaths;

    /**
     * Create a new message instance.
     */
    public function __construct(ProgrammeManagement $programme, string $status, ?string $remark = null, array $filePaths = [])
    {
        $this->programme  = $programme;
        $this->status     = $status;
        $this->remark     = $remark;
        $this->filePaths  = $filePaths;
    }

    /**
     * Get the message envelope.
     */
   public function envelope(): Envelope
{
    $subjectPrefix = match ($this->status) {
        'Canceled'   => 'Programme Cancelled',
        'Postpond'   => 'Programme Postponed',
        'Reschedule' => 'Programme Rescheduled',
        default      => 'Programme Update',
    };

    return new Envelope(
        subject: "{$subjectPrefix}: {$this->programme->title}",
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
                'status'    => $this->status,
                'remark'    => $this->remark,
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
        $attachments = [];

        foreach ($this->filePaths as $path) {
            $attachments[] = Attachment::fromStorageDisk('public', $path);
        }

        return $attachments;
    }
}