<?php

namespace App\Notifications;

use App\Models\Affiliate\AffiliateSubmissionMessage;
use Illuminate\Notifications\Notification;

/**
 * Fires when either side (affiliate or admin) sends a message on a
 * social submission thread. Database channel only - reuses the same
 * `notifications` table already powering your dashboard's bell icon,
 * so no new UI is needed for the notification itself, just this class.
 */
class NewAffiliateSubmissionMessage extends Notification
{
    public function __construct(private AffiliateSubmissionMessage $message) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $submission = $this->message->submission;

        return [
            'type'          => 'affiliate_submission_message',
            'submission_id' => $submission->id,
            'sender_role'   => $this->message->sender_role,
            'sender_name'   => $this->message->sender->name,
            'preview'       => \Illuminate\Support\Str::limit($this->message->message, 80),
            'url'           => $this->message->sender_role === 'affiliate'
                ? route('affiliates.social-submissions') . '?open=' . $submission->id
                : route('affiliate.social-submissions') . '?open=' . $submission->id,
        ];
    }
}
