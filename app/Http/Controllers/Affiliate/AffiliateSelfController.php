<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
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
}