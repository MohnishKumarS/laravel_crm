<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HomeHeroSection extends Model
{
    protected $fillable = [
        'is_active',
        'heading', 'heading_highlight', 'subtext',
        'badge_text',
        'image_main', 'image_secondary_1', 'image_secondary_2',
        'buttons', 'features',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'buttons'   => 'array',
        'features'  => 'array',
    ];

    /** The one section the homepage should render right now. */
    public static function active(): ?self
    {
        return static::where('is_active', true)->latest('id')->first();
    }

    public function getImageMainUrlAttribute(): ?string
    {
        return $this->image_main ? Storage::url($this->image_main) : null;
    }

    public function getImageSecondary1UrlAttribute(): ?string
    {
        return $this->image_secondary_1 ? Storage::url($this->image_secondary_1) : null;
    }

    public function getImageSecondary2UrlAttribute(): ?string
    {
        return $this->image_secondary_2 ? Storage::url($this->image_secondary_2) : null;
    }
}
