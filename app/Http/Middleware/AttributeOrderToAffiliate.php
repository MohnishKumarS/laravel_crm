<?php

namespace App\Http\Middleware;

use App\Services\ReferralAttributionService;
use Closure;
use Illuminate\Http\Request;

/**
 * Attach this to the route(s) that CREATE an order (checkout submit),
 * not to every route. It reads the affiliate_ref cookie set by
 * ReferralAttributionService and stashes the resulting referral on the
 * request so your OrderController can attach it after the order is saved:
 *
 *   $order->update(['affiliate_id' => $request->attributes->get('affiliate_referral')?->affiliate_id]);
 */
class AttributeOrderToAffiliate
{
    public function __construct(private ReferralAttributionService $attribution) {}

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only attempt attribution if the order was actually created successfully.
        // Adjust this to however your OrderController exposes the new order id.
        $orderId = $request->attributes->get('created_order_id');
        $customerUserId = $request->user()?->id;

        if ($orderId) {
            $referral = $this->attribution->attributeOrder($request, $orderId, $customerUserId);
            $request->attributes->set('affiliate_referral', $referral);
        }

        return $response;
    }
}
