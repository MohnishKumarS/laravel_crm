<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateSelectedProduct extends Model
{
    protected $fillable = [
        'affiliate_id', 'product_id', 'product_name', 'product_image', 'product_price',
    ];

    protected $casts = [
        'product_price' => 'decimal:2',
    ];

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    /**
     * Promo page URL for this specific product, with the affiliate's
     * referral code embedded. Matches the Next.js route convention
     * /promo/{product_id}?ref=CODE - adjust the path if your actual
     * Next.js routing differs.
     */
    public function promoUrl(): string
    {
        $affiliate = $this->affiliate;
        $frontend = rtrim(config('app.frontend_url', config('app.url')), '/');

        return "{$frontend}/promo/{$this->product_id}?ref={$affiliate->affiliate_code}";
    }
}
