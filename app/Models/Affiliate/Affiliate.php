<?php

namespace App\Models\Affiliate;

use App\Models\ReferralClick;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Affiliate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'affiliate_code', 'slug', 'commission_rate',
        'status', 'paypal_email', 'payout_notes',
        'lifetime_earnings', 'lifetime_paid', 'approved_at',
    ];

    protected $casts = [
        'commission_rate'   => 'decimal:2',
        'lifetime_earnings' => 'decimal:2',
        'lifetime_paid'     => 'decimal:2',
        'approved_at'       => 'datetime',
        'payout_notes'      => 'encrypted',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(ReferralClick::class);
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(AffiliateReferral::class);
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(AffiliateCommission::class);
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(AffiliatePayout::class);
    }

    public function unpaidBalance(): float
    {
        return (float) $this->commissions()->where('status', 'approved')->sum('commission_amount');
    }

    public function pendingBalance(): float
    {
        return (float) $this->commissions()->where('status', 'pending')->sum('commission_amount');
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function referralUrl(): string
    {
        return rtrim(config('app.frontend_url', config('app.url')), '/') . '/?ref=' . $this->affiliate_code;
    }
      public function affiliate()
  {
      return $this->hasOne(Affiliate::class);
  }
}
