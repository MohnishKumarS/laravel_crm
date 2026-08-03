<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AffiliateSelectedProduct;
use Illuminate\Http\Request;

class ProductSelectionController extends Controller
{
    /**
     * GET /api/affiliate/products
     * List the logged-in affiliate's selected products, each with its
     * promo URL ready to copy/share.
     */
    public function index(Request $request)
    {
        $affiliate = $this->affiliateOrFail($request);

        $products = $affiliate->selectedProducts()->latest()->get()->map(function ($p) {
            return [
                'id'            => $p->id,
                'product_id'    => $p->product_id,
                'product_name'  => $p->product_name,
                'product_image' => $p->product_image,
                'product_price' => (float) $p->product_price,
                'promo_url'     => $p->promoUrl(),
            ];
        });

        return response()->json(['products' => $products]);
    }

    /**
     * POST /api/affiliate/products
     * Body: { "product_id": 123, "product_name": "...", "product_image": "...", "product_price": 499.00 }
     *
     * product_name/image/price are cached from whatever your Next.js
     * frontend already has loaded for that product (it already talks to
     * the marketplace catalog directly) - sent here so Laravel doesn't
     * need a live cross-database lookup just to render "My Products."
     */
    public function store(Request $request)
    {
        $affiliate = $this->affiliateOrFail($request);

        $request->validate([
            'product_id'    => ['required', 'integer'],
            'product_name'  => ['nullable', 'string', 'max:255'],
            'product_image' => ['nullable', 'string', 'max:500'],
            'product_price' => ['nullable', 'numeric'],
        ]);

        $selection = AffiliateSelectedProduct::firstOrCreate(
            ['affiliate_id' => $affiliate->id, 'product_id' => $request->product_id],
            [
                'product_name'  => $request->product_name,
                'product_image' => $request->product_image,
                'product_price' => $request->product_price,
            ]
        );

        return response()->json([
            'product' => [
                'id'         => $selection->id,
                'product_id' => $selection->product_id,
                'promo_url'  => $selection->promoUrl(),
            ],
        ], 201);
    }

    /**
     * DELETE /api/affiliate/products/{id}
     * {id} is the affiliate_selected_products row id, not the product_id.
     */
    public function destroy(Request $request, int $id)
    {
        $affiliate = $this->affiliateOrFail($request);

        $deleted = $affiliate->selectedProducts()->where('id', $id)->delete();

        return response()->json(['deleted' => (bool) $deleted]);
    }

    private function affiliateOrFail(Request $request)
    {
        $affiliate = $request->user()->affiliate;
        abort_if(!$affiliate, 404, 'Not an affiliate.');
        abort_if(!$affiliate->isApproved(), 403, 'Affiliate account not yet approved.');

        return $affiliate;
    }
}
