<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Affiliate\Affiliate;

/**
 * Public, unauthenticated endpoint - a customer clicking an affiliate's
 * shared store link is not logged in, so this cannot sit behind Sanctum
 * like your other Api\Affiliate\* controllers.
 */
class AffiliateStoreController extends Controller
{
    /**
     * GET /api/affiliate-store/{code}
     * Called by the Next.js /affiliate-store/[code] page to render an
     * affiliate's shared storefront listing all their selected products.
     */
    public function show(string $code)
    {
        $affiliate = Affiliate::where('affiliate_code', $code)->first();

        if (!$affiliate || !$affiliate->isApproved()) {
            return response()->json(['message' => 'Store not found.'], 404);
        }

        $products = $affiliate->selectedProducts()->latest()->get()->map(function ($p) {
            return [
                'product_id'    => $p->product_id,
                'product_name'  => $p->product_name,
                'product_image' => $p->product_image,
                'product_price' => (float) $p->product_price,
            ];
        });

        return response()->json([
            'affiliate_code' => $affiliate->affiliate_code,
            'affiliate_name' => $affiliate->user->name ?? null,
            'products'       => $products,
        ]);
    }
}
