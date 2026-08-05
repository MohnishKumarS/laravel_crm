<?php

namespace App\Models\Affiliate;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateLessonProgress extends Model
{
    protected $fillable = ['affiliate_id', 'lesson_id', 'watched_at'];

    protected $casts = ['watched_at' => 'datetime'];

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(TrainingLesson::class, 'lesson_id');
    }
}
