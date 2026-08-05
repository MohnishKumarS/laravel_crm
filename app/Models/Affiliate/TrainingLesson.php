<?php

namespace App\Models\Affiliate;

use Illuminate\Database\Eloquent\Model;

class TrainingLesson extends Model
{
    protected $fillable = ['title', 'video_url', 'description', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];
}
