<?php

namespace App\Services;

use App\Models\Affiliate\AffiliateSetting;
use Illuminate\Support\Facades\Cache;

class AffiliateSettingsService
{
    private const CACHE_KEY = 'affiliate_settings_map';

    public function all(): array
    {
        return Cache::remember(self::CACHE_KEY, 3600, function () {
            return AffiliateSetting::pluck('value', 'key')->toArray();
        });
    }

    public function get(string $key, $default = null)
    {
        return $this->all()[$key] ?? $default;
    }

    public function set(string $key, $value): void
    {
        AffiliateSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget(self::CACHE_KEY);
    }

    public function cookieDurationDays(): int
    {
        return (int) $this->get('cookie_duration_days', 30);
    }

    public function refundHoldDays(): int
    {
        return (int) $this->get('refund_hold_days', 14);
    }

    public function minPayoutAmount(): float
    {
        return (float) $this->get('min_payout_amount', 50);
    }

    public function defaultCommissionRate(): float
    {
        return (float) $this->get('default_commission_rate', 10);
    }

    public function autoApproveAffiliates(): bool
    {
        return (bool) $this->get('auto_approve_affiliates', false);
    }

    public function selfReferralBlock(): bool
    {
        return (bool) $this->get('self_referral_block', true);
    }
}
