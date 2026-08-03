<?php

namespace App\Jobs;

use App\Mail\BulkMarketingMail;
use App\Models\Tool\EmailCampaignRecipient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendBulkEmailJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;


    /**
     * Number of attempts.
     */
    public int $tries = 3;
    // public $timeout = 120;


    /**
     * Retry delay.
     */
    public function backoff(): array
    {
        return [30, 60, 120];
    }


    public function __construct(
        public int $recipientId
    ) {}

    /**
     * Execute job.
     */
    public function handle(): void
    {
        $recipient = EmailCampaignRecipient::with('campaign.template')
            ->find($this->recipientId);

        if (!$recipient) {
            return;
        }

        /*
    |--------------------------------------------------------------------------
    | Do not send again if already sent
    |--------------------------------------------------------------------------
    */
        if ($recipient->status === 'sent') {
            return;
        }

        /*
    |--------------------------------------------------------------------------
    | Mark as sending
    |--------------------------------------------------------------------------
    */
        $recipient->update([
            'status' => 'sending',
            'attempts' => $recipient->attempts + 1,
        ]);

        try {
            Mail::to($recipient->email)->send(new BulkMarketingMail($recipient));

            /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

            Log::info('Bulk email sent successfully.', [
                'campaign_id' => $recipient->campaign_id,
                'recipient_id' => $recipient->id,
                'email' => $recipient->email,
                'attempt' => $recipient->attempts,
            ]);

            $recipient->update([
                'status' => 'sent',
                'sent_at' => now(),
                'error_message' => null,
            ]);

            $campaign = $recipient->campaign;

            $campaign->increment('sent_count');
            if ($campaign->queued_count > 0) {
                $campaign->decrement('queued_count');
            }
            // $campaign->decrement('queued_count');

            $this->updateCampaignStatus($campaign);
        } catch (Throwable $exception) {

            Log::error('Bulk email sending failed.', [
                'campaign_id' => $recipient->campaign_id,
                'recipient_id' => $recipient->id,
                'email' => $recipient->email,
                'attempt' => $recipient->attempts,
                'queue' => 'emails',
                'error' => $exception->getMessage(),
            ]);

            $recipient->update([
                'status' => 'queued',
                'error_message' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    /**
     * Called after all retries fail.
     */
    public function failed(Throwable $exception): void
    {
        $recipient = EmailCampaignRecipient::find($this->recipientId);

        if (!$recipient) {
            return;
        }

        Log::error('Bulk email failed function.', [
            'campaign_id' => $recipient->campaign_id,
            // 'recipient_id' => $recipient->id,
            // 'email' => $recipient->email,
            // 'attempt' => $recipient->attempts,
            // 'queue' => 'emails',
            'error' => $exception->getMessage(),
        ]);

        $recipient->update([
            'status' => 'failed',
            'error_message' => $exception->getMessage(),
        ]);

        $campaign = $recipient->campaign;

        // $campaign->decrement('queued_count');
        if ($campaign->queued_count > 0) {
            $campaign->decrement('queued_count');
        }
        $campaign->increment('failed_count');

        $this->updateCampaignStatus($campaign);
    }


    /**
     * Update campaign status.
     */
    private function updateCampaignStatus($campaign): void
    {
        $finished = $campaign->sent_count + $campaign->failed_count;

        if ($finished >= $campaign->total_recipients) {
            $campaign->update([
                'status' => $campaign->failed_count > 0
                    ? 'partially_failed'
                    : 'completed',
                'completed_at' => now(),
            ]);

            return;
        }

        $campaign->update([
            'status' => 'processing',
        ]);
    }
}
