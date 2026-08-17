<?php

namespace App\Console\Commands;

use App\Jobs\SendBulkEmailJob;
use App\Models\Tool\EmailCampaign;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('emails:process-scheduled')]
#[Description('Process scheduled email campaigns')]
class ProcessScheduledEmailCampaigns extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Scheduled Email Campaign Scheduler Started at ' . now()->format('Y-m-d H:i:s') );
        // $this->info('Current time: ' . now()->format('Y-m-d H:i:s'));

        $campaigns = EmailCampaign::query()
            ->where('status', 'scheduled')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->get();

        if ($campaigns->isEmpty()) {
            $this->info('No scheduled campaigns found.');
            return self::SUCCESS;
        }

        foreach ($campaigns as $campaign) {

            $this->info(
                "Processing Campaign #{$campaign->id} - " .
                    "Scheduled at: {$campaign->scheduled_at}"
            );

            $campaign->update([
                'status' => 'processing',
                'started_at' => now(),
            ]);

            $campaign->recipients()
                ->where('status', 'queued')
                ->select('id')
                ->chunkById(100, function ($recipients) use ($campaign) {

                    foreach ($recipients as $recipient) {

                        SendBulkEmailJob::dispatch($recipient->id)
                            ->onQueue('emails');

                        $this->info(
                            "Dispatched recipient #{$recipient->email} " .
                                "for campaign #{$campaign->id}"
                        );
                    }
                });

            // $this->info("Campaign #{$campaign->id} dispatched successfully.");
        }

        $this->info('Scheduler completed at: ' . now()->format('Y-m-d H:i:s'));

        return self::SUCCESS;
    }
}
