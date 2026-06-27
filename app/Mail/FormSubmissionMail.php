<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FormSubmissionMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $emailSubject;
    public string $htmlBody;

    /**
     * @param string $subject     Final, already-placeholder-replaced subject
     * @param string $htmlBody    Final, already-placeholder-replaced HTML body
     */
    public function __construct(string $subject, string $htmlBody)
    {
        $this->emailSubject = $subject;
        $this->htmlBody = $htmlBody;
    }

    public function build()
    {
        return $this->subject($this->emailSubject)
            ->html($this->htmlBody);
    }
}
