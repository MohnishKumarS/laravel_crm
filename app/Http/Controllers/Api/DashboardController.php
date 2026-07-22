<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * GET /api/affiliate/dashboard
     * Summary stats card for the affiliate dashboard homepage.
     */
    public function stats(Request $request)
    {
        $affiliate = $this->affiliateOrFail($request);

        return response()->json([
            'clicks_total'      => $affiliate->clicks()->count(),
            'clicks_30d'        => $affiliate->clicks()->where('created_at', '>=', now()->subDays(30))->count(),
            'conversions_total' => $affiliate->referrals()->whereNotNull('converted_at')->count(),
            'pending_earnings'  => $affiliate->pendingBalance(),
            'approved_earnings' => $affiliate->unpaidBalance(),
            'lifetime_earnings' => (float) $affiliate->lifetime_earnings,
            'lifetime_paid'     => (float) $affiliate->lifetime_paid,
        ]);
    }

    /**
     * GET /api/affiliate/commissions
     */
    public function commissions(Request $request)
    {
        $affiliate = $this->affiliateOrFail($request);

        $commissions = $affiliate->commissions()
            ->with('order:id,order_number,total')
            ->latest()
            ->paginate(20);

        return response()->json($commissions);
    }

    /**
     * GET /api/affiliate/payouts
     */
    public function payouts(Request $request)
    {
        $affiliate = $this->affiliateOrFail($request);

        return response()->json($affiliate->payouts()->latest()->paginate(20));
    }

    /**
     * GET /api/affiliate/link
     */
    public function link(Request $request)
    {
        $affiliate = $this->affiliateOrFail($request);

        return response()->json([
            'affiliate_code' => $affiliate->affiliate_code,
            'referral_url'   => $affiliate->referralUrl(),
        ]);
    }

    private function affiliateOrFail(Request $request)
    {
        $affiliate = $request->user()->affiliate;
        abort_if(!$affiliate, 404, 'Not an affiliate.');
        abort_if(!$affiliate->isApproved(), 403, 'Affiliate account not yet approved.');

        return $affiliate;
    }
}
