<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReferralAttributionService;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function __construct(private ReferralAttributionService $attribution) {}

    /**
     * POST /api/referral/track
     * Called by the Next.js middleware/API route as soon as a ?ref= param
     * (or /r/{slug}) lands on the storefront. Sets the httpOnly cookie
     * server-side so it can't be read or forged by client JS.
     * Body: { "ref": "JOHN10", "landing_url": "https://site.com/..." }
     */
    public function track(Request $request)
    {
        $request->validate(['ref' => ['required', 'string']]);

        $token = $this->attribution->trackLinkClick($request, $request->ref);

        if (!$token) {
            return response()->json(['tracked' => false], 200);
        }

        return response()->json(['tracked' => true]);
    }

    /**
     * POST /api/referral/apply-code
     * Called from the checkout page's "Referral code" field.
     * Always overrides any existing cookie (code wins).
     */
    public function applyCode(Request $request)
    {
        $request->validate(['code' => ['required', 'string']]);

        $affiliate = $this->attribution->applyCode($request, $request->code);

        if (!$affiliate) {
            return response()->json(['valid' => false, 'message' => 'Invalid or inactive code.'], 422);
        }

        return response()->json([
            'valid'   => true,
            'message' => "Code applied — supporting {$affiliate->user->name}.",
        ]);
    }
    public function current(Request $request)
   {
    $affiliate = $this->attribution->currentAttributedAffiliate($request);

    return response()->json([
        'affiliate_code' => $affiliate?->affiliate_code,
    ]);
   }
}
