<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateCommission extends Model
{
    protected $fillable = [
        'affiliate_id', 'order_id', 'order_total', 'commission_rate',
        'commission_amount', 'status', 'payout_id', 'approved_at', 'reversed_at',
    ];

    protected $casts = [
        'order_total'        => 'decimal:2',
        'commission_rate'    => 'decimal:2',
        'commission_amount'  => 'decimal:2',
        'approved_at'        => 'datetime',
        'reversed_at'        => 'datetime',
    ];

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    /**
     * `orders` lives in a separate database/connection from this table.
     * This still works fine — Eloquent relations run as independent queries,
     * not SQL joins, so crossing connections is safe here. Just make sure
     * your Order model declares its own `protected $connection = '...'`
     * pointing at the orders database.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function payout(): BelongsTo
    {
        return $this->belongsTo(AffiliatePayout::class, 'payout_id');
    }
}
