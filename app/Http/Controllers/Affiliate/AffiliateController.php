<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\User;
use App\Services\AffiliateSettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AffiliateController extends Controller
{
    public function index(Request $request)
    {
        $affiliates = Affiliate::with('user')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('affiliate_code', 'like', "%{$request->search}%")
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$request->search}%"));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.affiliates.index', compact('affiliates'));
    }

    public function show(Affiliate $affiliate)
    {
        $affiliate->load(['commissions.order', 'payouts', 'clicks' => fn ($q) => $q->latest()->limit(50)]);

        return view('admin.affiliates.show', compact('affiliate'));
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

public function store(Request $request)
{
    $request->validate([
        'user_mode' => ['required', 'in:existing,new'],
        'user_id'   => ['required_if:user_mode,existing', 'nullable', 'exists:users,id', 'unique:affiliates,user_id'],
        'new_name'     => ['required_if:user_mode,new', 'nullable', 'string', 'max:255'],
        'new_email'    => ['required_if:user_mode,new', 'nullable', 'email', 'unique:users,email'],
        'new_password' => ['nullable', 'string', 'min:6'],
        'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
        'paypal_email'    => ['nullable', 'email'],
    ]);

    if ($request->user_mode === 'existing') {
        $user = User::findOrFail($request->user_id);
    } else {
        $user = User::create([
            'name'     => $request->new_name,
            'email'    => $request->new_email,
            'password' => Hash::make($request->new_password ?: Str::random(12)),
            'role'     => 'affiliate',
        ]);
    }

    Affiliate::create([
        'user_id'         => $user->id,
        'affiliate_code'  => $this->generateUniqueCode($user->name),
        'slug'            => Str::slug($user->name . '-' . Str::random(4)),
        'commission_rate' => $request->commission_rate ?? $this->settings->defaultCommissionRate(),
        'status'          => 'approved',
        'approved_at'     => now(),
        'paypal_email'    => $request->paypal_email,
    ]);
    return redirect()->route('affiliates.index')->with('success', "Affiliate account created for {$user->name}.");
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
