<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\AffiliateCommission;
use App\Models\AffiliateReferral;
use App\Models\Order;

/**
 * Creates and later matures/reverses commission records.
 * Wire the two public methods to your existing order lifecycle events:
 *   - createForPaidOrder(): on 'order.paid'
 *   - reverseForOrder():    on 'order.refunded' / 'order.cancelled'
 */
class CommissionCalculator
{
    /**
     * Call this once an order has actually been paid (not just placed).
     */
    public function createForPaidOrder(Order $order, AffiliateReferral $referral): ?AffiliateCommission
    {
        // idempotency guard - unique constraint on order_id also protects this
        if (AffiliateCommission::where('order_id', $order->id)->exists()) {
            return null;
        }

        $affiliate = $referral->affiliate;

        if (!$affiliate || !$affiliate->isApproved()) {
            return null;
        }

        $rate = (float) $affiliate->commission_rate;
        $amount = round(((float) $order->total) * ($rate / 100), 2);

        $commission = AffiliateCommission::create([
            'affiliate_id'       => $affiliate->id,
            'order_id'           => $order->id,
            'order_total'        => $order->total,
            'commission_rate'    => $rate,
            'commission_amount'  => $amount,
            'status'             => 'pending',
        ]);

        $affiliate->increment('lifetime_earnings', $amount);

        return $commission;
    }

    /**
     * Call this when an order tied to a commission is refunded or cancelled.
     */
    public function reverseForOrder(int $orderId): void
    {
        $commission = AffiliateCommission::where('order_id', $orderId)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if (!$commission) {
            return;
        }

        $commission->update([
            'status'       => 'reversed',
            'reversed_at'  => now(),
        ]);

        $commission->affiliate?->decrement('lifetime_earnings', $commission->commission_amount);
    }

    /**
     * Scheduled daily: flips pending -> approved once the refund hold window has passed.
     */
    public function approveEligible(AffiliateSettingsService $settings): int
    {
        $cutoff = now()->subDays($settings->refundHoldDays());

        return AffiliateCommission::where('status', 'pending')
            ->where('created_at', '<=', $cutoff)
            ->update(['status' => 'approved', 'approved_at' => now()]);
    }
}
