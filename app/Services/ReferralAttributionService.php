<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\AffiliateReferral;
use App\Models\ReferralClick;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

/**
 * Handles both tracking modes (link + code) and decides which affiliate
 * an order should be attributed to.
 *
 * Attribution rule: LAST TOUCH.
 * - A code entered at checkout always overrides an existing cookie.
 * - A link click sets/refreshes the cookie but never overrides a code
 *   entered in the same request.
 */

class ReferralAttributionService
{
    public const COOKIE_NAME = 'affiliate_ref';

    public function __construct(private AffiliateSettingsService $settings) {}

    /**
     * Record a click coming from a referral link (?ref=CODE or /r/{slug})
     * and set the attribution cookie. Returns the signed cookie payload.
     */
    public function trackLinkClick(Request $request, string $codeOrSlug): ?string
    {
        $affiliate = $this->resolveAffiliate($codeOrSlug);

        if (!$affiliate || !$affiliate->isApproved()) {
            return null;
        }

        ReferralClick::create([
            'affiliate_id' => $affiliate->id,
            'source_type'  => 'link',
            'landing_url'  => $request->input('landing_url', $request->headers->get('referer')),
            'ip_hash'      => hash('sha256', $request->ip()),
            'user_agent'   => substr((string) $request->userAgent(), 0, 255),
            'created_at'   => now(),
        ]);

        return $this->setAttributionCookie($affiliate, 'cookie');
    }

    /**
     * Handle a code manually entered at checkout. Always wins over an
     * existing cookie for this session.
     */
    public function applyCode(Request $request, string $code): ?Affiliate
    {
        $affiliate = Affiliate::where('affiliate_code', $code)->first();

        if (!$affiliate || !$affiliate->isApproved()) {
            return null;
        }

        ReferralClick::create([
            'affiliate_id' => $affiliate->id,
            'source_type'  => 'code',
            'landing_url'  => $request->input('landing_url'),
            'ip_hash'      => hash('sha256', $request->ip()),
            'user_agent'   => substr((string) $request->userAgent(), 0, 255),
            'created_at'   => now(),
        ]);

        $this->setAttributionCookie($affiliate, 'code_entered');

        return $affiliate;
    }

    /**
     * Called from the order-creation flow. Reads the cookie set above and
     * links the order to the correct affiliate + referral row.
     * Returns null if there is no active attribution or self-referral is blocked.
     */
    public function attributeOrder(Request $request, int $orderId, ?int $customerUserId): ?AffiliateReferral
    {
        $payload = $this->readAttributionCookie($request);

        if (!$payload) {
            return null;
        }

        $affiliate = Affiliate::find($payload['affiliate_id']);

        if (!$affiliate || !$affiliate->isApproved()) {
            return null;
        }

        if ($this->settings->selfReferralBlock() && $customerUserId && $affiliate->user_id === $customerUserId) {
            return null; // affiliate referring themselves
        }

        return AffiliateReferral::firstOrCreate(
            ['cookie_token' => $payload['token']],
            [
                'affiliate_id'        => $affiliate->id,
                'referred_user_id'    => $customerUserId,
                'attribution_source'  => $payload['source'],
                'first_click_at'      => now(),
                'converted_at'        => now(),
            ]
        );
    }

    private function resolveAffiliate(string $codeOrSlug): ?Affiliate
    {
        return Affiliate::where('affiliate_code', $codeOrSlug)
            ->orWhere('slug', $codeOrSlug)
            ->first();
    }

    private function setAttributionCookie(Affiliate $affiliate, string $source): string
    {
        $token = Str::uuid()->toString();

        $payload = json_encode([
            'affiliate_id' => $affiliate->id,
            'token'        => $token,
            'source'       => $source,
        ]);

        Cookie::queue(
            self::COOKIE_NAME,
            encrypt($payload),
            $this->settings->cookieDurationDays() * 24 * 60,
            null,
            null,
            app()->environment('production'), // secure only in production (HTTPS)
            true,   // httpOnly - not readable/forgeable from the Next.js client
            false,
            'Lax'
        );

        return $token;
    }

    private function readAttributionCookie(Request $request): ?array
    {
        $raw = $request->cookie(self::COOKIE_NAME);

        if (!$raw) {
            return null;
        }

        try {
            return json_decode(decrypt($raw), true);
        } catch (\Throwable $e) {
            return null;
        }
    }
    public function currentAttributedAffiliate(Request $request): ?Affiliate
{
    $payload = $this->readAttributionCookie($request);

    if (!$payload) {
        return null;
    }

    $affiliate = Affiliate::find($payload['affiliate_id']);

    return ($affiliate && $affiliate->isApproved()) ? $affiliate : null;
}
}
