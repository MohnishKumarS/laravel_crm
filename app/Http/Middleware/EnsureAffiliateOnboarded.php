<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Blocks access to product selection / social submission until the
 * affiliate has: an approved account (Phase 1's existing approval) +
 * approved KYC + completed training. Attach this to those specific
 * route groups only - NOT to dashboard/commissions/payouts, since an
 * affiliate should still be able to see their earnings and go through
 * onboarding even before they're fully cleared.
 */
class EnsureAffiliateOnboarded
{
    public function handle(Request $request, Closure $next)
    {
        $affiliate = $request->user()?->affiliate;

        if (!$affiliate) {
            abort(403, 'Not an affiliate.');
        }

        if (!$affiliate->isFullyOnboarded()) {
            return redirect()->route('affiliate.onboarding')
                ->with('status', 'warning')
                ->with('message', 'Complete KYC verification and training before accessing this feature.');
        }

        return $next($request);
    }
}
