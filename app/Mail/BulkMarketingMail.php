<?php

namespace App\Mail;

use App\Models\Tool\EmailCampaignRecipient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BulkMarketingMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public EmailCampaignRecipient $recipient
    ) {}

    public function build()
    {
        $campaign = $this->recipient->campaign;
        $template = $campaign->template;

        /*
    |--------------------------------------------------------------------------
    | Template variables
    |--------------------------------------------------------------------------
    */

        $body = $template->body;

        $body = str_replace(
            [
                '{{name}}',
                '{{email}}',
                '{{company_name}}',
            ],
            [
                $this->recipient->name ?? '',
                $this->recipient->email,
                config('app.name'),
            ],
            $body
        );

        $subject = $template->subject;

        $subject = str_replace(
            [
                '{{name}}',
                '{{email}}',
            ],
            [
                $this->recipient->name ?? '',
                $this->recipient->email,
            ],
            $subject
        );

        return $this
            // ->from($this->recipient->sender_email,config('mail.from.name'))
            ->subject($subject)
            ->html($body);
    }
}
