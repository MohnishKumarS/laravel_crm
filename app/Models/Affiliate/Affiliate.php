<?php

namespace App\Models\Affiliate;

use App\Models\Affiliate\AffiliateSelectedProduct;
use App\Models\Affiliate\AffiliateSocialSubmission;
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
        'kyc_status', 'kyc_submitted_at', 'kyc_reviewed_at', 'training_completed_at',
        'bank_account_holder', 'bank_account_number', 'bank_name', 'bank_ifsc'
    ];

    protected $casts = [
        'commission_rate'   => 'decimal:2',
        'lifetime_earnings' => 'decimal:2',
        'lifetime_paid'     => 'decimal:2',
        'approved_at'       => 'datetime',
        'payout_notes'      => 'encrypted',
        'training_completed_at' => 'datetime',
        'bank_account_number' => 'encrypted',
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
    public function selectedProducts(): HasMany
   {
       return $this->hasMany(AffiliateSelectedProduct::class);
   }
   public function socialSubmissions(): HasMany
   {
       return $this->hasMany(AffiliateSocialSubmission::class);
   }
   public function storeUrl(): string
{
    $frontend = rtrim(config('app.frontend_url', config('app.url')), '/');

    return "{$frontend}/partner-store/{$this->affiliate_code}";
}
public function kycDocuments(): HasMany
{
    return $this->hasMany(AffiliateKycDocument::class);
}

public function quizAttempts(): HasMany
{
    return $this->hasMany(AffiliateQuizAttempt::class);
}

public function isKycApproved(): bool
{
    return $this->kyc_status === 'approved';
}

public function isTrainingCompleted(): bool
{
    return !is_null($this->training_completed_at);
}

// Gate used by the EnsureAffiliateOnboarded middleware. Change this
// single method if you ever want to loosen/tighten what counts as
// "fully onboarded" - everything else reads through this one place.
public function isFullyOnboarded(): bool
{
    return $this->isApproved() && $this->isKycApproved() && $this->isTrainingCompleted();
}
public function lessonProgress(): HasMany
{
    return $this->hasMany(AffiliateLessonProgress::class);
}
public function maskedBankAccountNumber(): ?string
{
        if (!$this->bank_account_number) {
            return null;
        }

        $number = $this->bank_account_number;
        return str_repeat('*', max(0, strlen($number) - 4)) . substr($number, -4);
}
}
