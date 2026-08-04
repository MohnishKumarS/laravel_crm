<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Affiliate\AffiliateSelectedProduct;
use App\Models\Affiliate\AffiliateSocialSubmission;
use App\Models\Affiliate\AffiliateSubmissionMessage;
use App\Models\Marketplace\Product;
use App\Models\User;
use App\Notifications\NewAffiliateSubmissionMessage;
use DB;
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

    $catalog = Product::select('products.*')
        ->join('warehouses_products', 'warehouses_products.product_id', '=', 'products.id')
        ->join('warehouses', 'warehouses.id', '=', 'warehouses_products.warehouse_id')
        ->join('categories', 'categories.id', '=', 'products.category_id')
        ->join('users', 'users.email', '=', 'warehouses.email')
        ->where('warehouses.verify', 1)
        ->where('products.status', 1)
        ->where('products.site_approved', 1)
        ->where('products.own_hamper', '!=', 1)
        ->when($search, fn ($q) => $q->where('products.name', 'like', "%{$search}%"))
        ->groupBy('products.id')
        ->orderBy('products.name')
        ->paginate(20);

    $selectedIds = $affiliate->selectedProducts()->pluck('product_id')->toArray();

    return view('admin.affiliate-portal.browse-products', compact('affiliate', 'catalog', 'selectedIds', 'search'));
}

public function storeProduct(Request $request)
{
     $affiliate = $request->user()->affiliate;

    $request->validate(['product_id' => ['required', 'integer']]);

    $product = DB::connection('marketplace')->table('products')->find($request->product_id);

    \App\Models\Affiliate\AffiliateSelectedProduct::firstOrCreate(
        ['affiliate_id' => $affiliate->id, 'product_id' => $request->product_id],
        [
            'product_name'  => $product->name ?? null,
            'product_image' => $product->image ?? null, // filename only, prefix applied at display time
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

public function sendSubmissionMessage(Request $request, AffiliateSocialSubmission $submission)
{
    $affiliate = $request->user()->affiliate;

    // Guard: affiliates can only message on their own submissions
    abort_unless($submission->affiliate_id === $affiliate->id, 403);

    $request->validate(['message' => ['required', 'string', 'max:2000']]);

    $message = AffiliateSubmissionMessage::create([
        'submission_id'   => $submission->id,
        'sender_user_id'  => $request->user()->id,
        'sender_role'     => 'affiliate',
        'message'         => $request->message,
    ]);

    // Notify every admin user - adjust this query if you want a specific
    // admin instead of broadcasting to all of them
    User::where('role', 'admin')->get()->each(
        fn ($admin) => $admin->notify(new NewAffiliateSubmissionMessage($message))
    );

    return back()->with('success', 'Message sent to admin.');
}


public function socialSubmissions(Request $request)
{
    $affiliate = $request->user()->affiliate;

    $submissions = $affiliate->socialSubmissions()->with('messages.sender')->latest()->paginate(20);
    $products = $affiliate->selectedProducts()->get();

    // Mark admin-sent messages as read now that the affiliate is viewing this page
    AffiliateSubmissionMessage::whereIn('submission_id', $affiliate->socialSubmissions()->pluck('id'))
        ->where('sender_role', 'admin')
        ->whereNull('read_at')
        ->update(['read_at' => now()]);

    return view('admin.affiliate-portal.social-submissions', compact('affiliate', 'submissions', 'products'));
}
}