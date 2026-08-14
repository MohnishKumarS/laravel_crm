<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Affiliate\Affiliate;
use App\Models\User;
use App\Services\AffiliateSettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AffiliateController extends Controller
{
    public function __construct(private AffiliateSettingsService $settings) {}
    public function index(Request $request)
    {
        $affiliates = Affiliate::with('user')
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('affiliate_code', 'like', "%{$request->search}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$request->search}%"));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.affiliates.index', compact('affiliates'));
    }

     public function show(Affiliate $affiliate)
    {
        $affiliate->load([
            'commissions.order', 'payouts',
            'clicks' => fn ($q) => $q->latest()->limit(50),
            'quizAttempts' => fn ($q) => $q->latest('attempted_at'),
            'lessonProgress.lesson',
        ]);

        $allLessons = \App\Models\Affiliate\TrainingLesson::where('is_active', true)->orderBy('sort_order')->get();
        $allQuestions = \App\Models\Affiliate\TrainingQuestion::where('is_active', true)->get()->keyBy('id');

        return view('admin.affiliates.show', compact('affiliate', 'allLessons', 'allQuestions'));
    }

    public function approve(Affiliate $affiliate)
    {
        $affiliate->update(['status' => 'approved', 'approved_at' => now()]);

        return back()->with('success', "{$affiliate->affiliate_code} approved.");
    }

    public function suspend(Affiliate $affiliate)
    {
        $affiliate->update(['status' => 'suspended']);

        return back()->with('success', "{$affiliate->affiliate_code} suspended.");
    }

    public function reject(Affiliate $affiliate)
    {
        $affiliate->update(['status' => 'rejected']);

        return back()->with('success', "{$affiliate->affiliate_code} rejected.");
    }

    public function updateRate(Request $request, Affiliate $affiliate)
    {
        $request->validate([
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $affiliate->update(['commission_rate' => $request->commission_rate]);

        return back()->with('success', 'Commission rate updated.');
    }
    public function create()
    {
        // Only users who don't already have an affiliate account
        $users = User::whereDoesntHave('affiliate')->orderBy('name')->get(['id', 'name', 'email']);
        // Debugging line to check the users being retrieved
        return view('admin.affiliates.create', compact('users'));
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
            'product_image' => $product->image ?? null,
            'product_price' => $product->price ?? null,
            'product_slug'  => $product->slug ?? null,
        ]
    );

    if ($request->wantsJson()) {
        return response()->json(['status' => true, 'message' => 'Added to your promotion list.']);
    }

    return back()->with('success', 'Product added to your promotion list.');
}

    private function generateUniqueCode(string $seed): string
    {
        for ($i = 0; $i < 15; $i++) {
            $code = strtoupper(Str::slug($seed, '')) . rand(100, 999);
            $code = substr($code, 0, 10);
            if (!Affiliate::where('affiliate_code', $code)->exists()) {
                return $code;
            }
        }

        do {
            $code = strtoupper(Str::random(8));
        } while (Affiliate::where('affiliate_code', $code)->exists());

        return $code;
    }
    
}
