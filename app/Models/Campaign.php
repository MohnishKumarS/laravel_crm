<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Campaign extends Model
{
    protected $fillable = [
        'name', 'slug',
        'start_at', 'end_at', 'is_published', 'is_default', 'priority',
        'theme_bg_start', 'theme_bg_end', 'accent_color', 'accent_text_color', 'eyebrow_color',
        'background_image',
        'eyebrow', 'badge_text', 'heading', 'heading_highlight', 'subtext',
        'cta1_text', 'cta1_url', 'cta2_text', 'cta2_url',
        'announcement_text', 'announcement_link_text', 'announcement_link_url',
        'show_countdown', 'countdown_end_at',
    ];
    // protected $guarded = [];

    protected $casts = [
        'start_at'          => 'datetime',
        'end_at'            => 'datetime',
        'countdown_end_at'  => 'datetime',
        'is_published'      => 'boolean',
        'is_default'        => 'boolean',
        'show_countdown'    => 'boolean',
    ];

    /**
     * Published AND inside its date window (or has no window).
     */
    public function scopeActive(Builder $query): Builder
    {
        $now = Carbon::now();

        return $query->where('is_published', true)
            ->where(function (Builder $q) use ($now) {
                $q->whereNull('start_at')->orWhere('start_at', '<=', $now);
            })
            ->where(function (Builder $q) use ($now) {
                $q->whereNull('end_at')->orWhere('end_at', '>=', $now);
            });
    }

    /**
     * The single campaign that should render on the site right now.
     * Highest-priority active, date-matched campaign wins; falls back
     * to the evergreen "is_default" campaign; falls back to null.
     */
    public static function current(): ?self
    {
        return static::active()
                ->orderByDesc('priority')
                ->orderByDesc('start_at')
                ->first()
            ?? static::where('is_default', true)->where('is_published', true)->first();
    }

    /** Human status for the admin list (computed, not stored). */
    public function getStatusLabelAttribute(): string
    {
        if (! $this->is_published) {
            return 'Draft';
        }

        $now = Carbon::now();

        if ($this->start_at && $this->start_at->isFuture()) {
            return 'Scheduled';
        }

        if ($this->end_at && $this->end_at->isPast()) {
            return 'Ended';
        }

        return $this->is_default ? 'Active · Default' : 'Active';
    }

    public function getBackgroundImageUrlAttribute(): ?string
    {
        return $this->background_image ? Storage::url($this->background_image) : null;
    }
}
