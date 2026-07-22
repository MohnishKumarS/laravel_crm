<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\AffiliateCommission;
use App\Services\CommissionCalculator;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    /**
     * POST /api/affiliate/webhooks/order-paid
     * Called from CodeIgniter's paymentCaptured() right after
     * updateStatus($inv->id, 'completed').
     * Body: { "sale_id": 123, "affiliate_code": "JANEAFFILI", "grand_total": 150.00 }
     */
    public function orderPaid(Request $request, CommissionCalculator $calculator)
    {
        $request->validate([
            'sale_id'      => ['required', 'integer'],
            'grand_total'  => ['required', 'numeric'],
            'affiliate_code' => ['nullable', 'string'],
        ]);

        if (empty($request->affiliate_code)) {
            return response()->json(['status' => true, 'message' => 'No affiliate attribution for this sale.']);
        }

        if (AffiliateCommission::where('order_id', $request->sale_id)->exists()) {
            return response()->json(['status' => true, 'message' => 'Commission already recorded.']);
        }

        $affiliate = Affiliate::where('affiliate_code', $request->affiliate_code)->first();

        if (!$affiliate || !$affiliate->isApproved()) {
            return response()->json(['status' => true, 'message' => 'Affiliate not found or not approved.']);
        }

        $rate = (float) $affiliate->commission_rate;
        $amount = round(((float) $request->grand_total) * ($rate / 100), 2);

        AffiliateCommission::create([
            'affiliate_id'      => $affiliate->id,
            'order_id'          => $request->sale_id,
            'order_total'       => $request->grand_total,
            'commission_rate'   => $rate,
            'commission_amount' => $amount,
            'status'            => 'pending',
        ]);

        $affiliate->increment('lifetime_earnings', $amount);

        return response()->json(['status' => true, 'message' => 'Commission recorded.']);
    }

    /**
     * POST /api/affiliate/webhooks/order-refunded
     * Body: { "sale_id": 123 }
     */
    public function orderRefunded(Request $request, CommissionCalculator $calculator)
    {
        $request->validate(['sale_id' => ['required', 'integer']]);

        $calculator->reverseForOrder($request->sale_id);

        return response()->json(['status' => true]);
    }
}