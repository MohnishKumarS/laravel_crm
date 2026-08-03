<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Affiliate\AffiliateSelectedProduct;
use App\Models\Affiliate\AffiliateSocialSubmission;
use App\Models\Marketplace\Product;
use Illuminate\Http\Request;

/**
 * Self-service portal for logged-in affiliate-role users. Every method
 * pulls data through $request->user()->affiliate — there is no way for
 * an affiliate to view another affiliate's data, since nothing here
 * accepts an {affiliate} route parameter.
 */
class AffiliateSelfController extends Controller
{
    public function dashboard(Request $request)
    {
        $affiliate = $request->user()->affiliate;

        return view('admin.affiliate-portal.dashboard', compact('affiliate'));
    }

    public function commissions(Request $request)
    {
        $affiliate = $request->user()->affiliate;

        $commissions = $affiliate->commissions()
            ->with('order')
            ->latest()
            ->paginate(20);

        return view('admin.affiliate-portal.commissions', compact('affiliate', 'commissions'));
    }

    public function payouts(Request $request)
    {
        $affiliate = $request->user()->affiliate;

        $payouts = $affiliate->payouts()->latest()->paginate(20);

        return view('admin.affiliate-portal.payouts', compact('affiliate', 'payouts'));
    }


public function products(Request $request)
{
    $affiliate = $request->user()->affiliate;

    $selected = $affiliate->selectedProducts()->latest()->get();

    return view('admin.affiliate-portal.products', compact('affiliate', 'selected'));
}

public function browseProducts(Request $request)
{
    $affiliate = $request->user()->affiliate;

    $search = $request->get('search');

    $catalog = Product::when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
        ->orderBy('name')
        ->paginate(20);

    $selectedIds = $affiliate->selectedProducts()->pluck('product_id')->toArray();

    return view('admin.affiliate-portal.browse-products', compact('affiliate', 'catalog', 'selectedIds', 'search'));
}

public function storeProduct(Request $request)
{
    $affiliate = $request->user()->affiliate;

    $request->validate(['product_id' => ['required', 'integer']]);

    $product = Product::find($request->product_id);

    AffiliateSelectedProduct::firstOrCreate(
        ['affiliate_id' => $affiliate->id, 'product_id' => $request->product_id],
        [
            'product_name'  => $product->name ?? null,
            'product_image' => $product->image ?? null,
            'product_price' => $product->price ?? null,
        ]
    );

    return back()->with('success', 'Product added to your promotion list.');
}

public function destroyProduct(Request $request, int $id)
{
    $affiliate = $request->user()->affiliate;

    $affiliate->selectedProducts()->where('id', $id)->delete();

    return back()->with('success', 'Product removed.');
}

public function socialSubmissions(Request $request)
{
    $affiliate = $request->user()->affiliate;

    $submissions = $affiliate->socialSubmissions()->latest()->paginate(20);
    $products = $affiliate->selectedProducts()->get(); // for the dropdown in the submission form

    return view('admin.affiliate-portal.social-submissions', compact('affiliate', 'submissions', 'products'));
}

public function storeSocialSubmission(Request $request)
{
    $affiliate = $request->user()->affiliate;

    $request->validate([
        'post_url'   => ['required', 'url', 'max:500'],
        'platform'   => ['required', 'in:instagram,facebook,youtube,tiktok,twitter,other'],
        'product_id' => ['nullable', 'integer'],
    ]);

    AffiliateSocialSubmission::create([
        'affiliate_id' => $affiliate->id,
        'product_id'   => $request->product_id,
        'post_url'     => $request->post_url,
        'platform'     => $request->platform,
        'status'       => 'pending',
    ]);

    return back()->with('success', 'Your post was submitted for review.');
}
}