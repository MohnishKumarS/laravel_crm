<?php

namespace App\Jobs;

use App\Services\AffiliateSettingsService;
use App\Services\CommissionCalculator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Schedule this daily in App\Console\Kernel (or routes/console.php on Laravel 11):
 *   $schedule->job(new ApproveEligibleAffiliateCommissions)->daily();
 */
class ApproveEligibleAffiliateCommissions implements ShouldQueue
{
    use Dispatchable;

    public function handle(CommissionCalculator $calculator, AffiliateSettingsService $settings): void
    {
        $calculator->approveEligible($settings);
    }
}
