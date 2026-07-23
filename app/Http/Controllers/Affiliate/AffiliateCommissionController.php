<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\AffiliateCommission;
use Illuminate\Http\Request;

class AffiliateCommissionController extends Controller
{
    public function index(Request $request)
    {
        $commissions = AffiliateCommission::with(['affiliate.user', 'order'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('affiliate_id'), fn ($q) => $q->where('affiliate_id', $request->affiliate_id))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.affiliates.commissions', compact('commissions'));
    }

    public function bulkApprove(Request $request)
    {
        $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer']]);

        AffiliateCommission::whereIn('id', $request->ids)
            ->where('status', 'pending')
            ->update(['status' => 'approved', 'approved_at' => now()]);

        return back()->with('success', 'Selected commissions approved.');
    }
}
