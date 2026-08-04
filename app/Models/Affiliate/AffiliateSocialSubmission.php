<?php

namespace App\Models\Affiliate;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AffiliateSocialSubmission extends Model
{
    protected $fillable = [
        'affiliate_id', 'product_id', 'post_url', 'platform',
        'status', 'admin_note', 'reviewed_by', 'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class); // same namespace, no import needed
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by'); // flat User model, needs the import above
    }
     public function messages(): HasMany
    {
     return $this->hasMany(AffiliateSubmissionMessage::class, 'submission_id')->orderBy('created_at');
    }
}
