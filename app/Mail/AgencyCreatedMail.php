<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AgencyCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $agencyName;
    public $userName;
    public $password;

    public function __construct($agencyName, $userName, $password)
    {
        $this->agencyName = $agencyName;
        $this->userName = $userName;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Agency Approval - Login Details')
                    ->view('emails.agency-created');
    }
}