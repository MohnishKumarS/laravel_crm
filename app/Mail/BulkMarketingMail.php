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

        $pixel = sprintf(
            '<img src="%s" width="1" height="1" style="display:none;" alt="">',
            route('emails.track.open', $this->recipient->tracking_token)
        );

        $body = $template->body;

        $body = str_replace(
            [
                '{{name}}',
                '{{email}}',
                '{{company_name}}',
                '{{tracking_pixel}}',
            ],
            [
                $this->recipient->name ?? '',
                $this->recipient->email,
                config('app.name'),
                $pixel,
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
