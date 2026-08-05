<?php

namespace App\Models\Affiliate;

use Illuminate\Database\Eloquent\Model;

class TrainingQuestion extends Model
{
    protected $fillable = [
        'question', 'option_a', 'option_b', 'option_c', 'option_d',
        'correct_option', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
