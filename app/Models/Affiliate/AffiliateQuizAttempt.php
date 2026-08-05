<?php

namespace App\Models\Affiliate;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateQuizAttempt extends Model
{
    protected $fillable = ['affiliate_id', 'score', 'total_questions', 'passed','answers', 'attempted_at'];

    protected $casts = [
        'passed'       => 'boolean',
        'attempted_at' => 'datetime',
        'answers'      => 'array',
    ];

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }
}
