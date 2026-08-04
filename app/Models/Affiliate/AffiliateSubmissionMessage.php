<?php

namespace App\Models\Affiliate;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateSubmissionMessage extends Model
{
    protected $fillable = [
        'submission_id', 'sender_user_id', 'sender_role', 'message', 'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(AffiliateSocialSubmission::class, 'submission_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }
}
