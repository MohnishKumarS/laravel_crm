<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Affiliate\Affiliate;
use App\Models\Marketplace\Product;

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

        $productModels = Product::with('productVariant')
            ->whereIn('id', $affiliate->selectedProducts->pluck('product_id'))
            ->get()
            ->keyBy('id');

        // return $productModels;

        $products = $affiliate->selectedProducts()->latest()->get()->map(function ($p)  use ($productModels) {

            $product = $productModels[$p->product_id] ?? null;
            return [
                'product_id'    => $p->product_id,
                'product_name'  => $p->product_name,
                'product_image' => $p->product_image,
                'product_price' => (float) $p->product_price,

                'promotion'     => $product?->promotion,
                'promo_price'   => $product?->promo_price,
                'start_date'    => $product?->start_date,
                'end_date'      => $product?->end_date,
                'offers'        => $product?->offers,
                'slug'          => $product?->slug,

                'variants'      => $product?->productVariant,
            ];
        });

        return response()->json([
            'affiliate_code' => $affiliate->affiliate_code,
            'affiliate_name' => $affiliate->user->name ?? null,
            'products'       => $products,
        ]);
    }
}
