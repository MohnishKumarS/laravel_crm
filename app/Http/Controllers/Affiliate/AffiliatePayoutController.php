<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Affiliate\Affiliate;
use App\Models\Affiliate\AffiliateCommission;
use App\Models\Affiliate\AffiliatePayout;
use App\Services\AffiliateSettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AffiliatePayoutController extends Controller
{
    public function __construct(private AffiliateSettingsService $settings) {}

    public function index(Request $request)
    {
        $payouts = AffiliatePayout::with('affiliate.user')
            ->latest()
            ->paginate(25);

        // affiliates with an approved balance ready to be paid out
       $eligible = Affiliate::whereHas('commissions', fn ($q) => $q->where('status', 'approved')->whereNull('payout_id'))
        ->get()
        ->map(fn ($a) => [
            'affiliate'         => $a,
            'balance'           => $a->commissions()->where('status', 'approved')->whereNull('payout_id')->sum('commission_amount'),
            'has_bank_details'  => (bool) $a->bank_account_number,
        ])
        ->filter(fn ($row) => $row['balance'] >= $this->settings->minPayoutAmount());

        return view('admin.affiliates.payouts', compact('payouts', 'eligible'));
    }

    /**
     * Step 1: bundle all approved commissions for an affiliate into a payout batch.
     * Admin still pays manually outside the system (PayPal/bank) after this.
     */
    public function createBatch(Request $request)
    {
        $request->validate(['affiliate_id' => ['required', 'exists:affiliates,id']]);

        return DB::transaction(function () use ($request) {
            $affiliate = Affiliate::findOrFail($request->affiliate_id);

            if (!$affiliate->bank_account_number) {
                return back()->with('error', "Cannot create payout - {$affiliate->affiliate_code} hasn't added bank details yet.");
            }

            $commissions = AffiliateCommission::where('affiliate_id', $affiliate->id)
                ->where('status', 'approved')
                ->whereNull('payout_id')
                ->lockForUpdate()
                ->get();

            // ... rest of the method stays exactly as it already is ...
        });
    }


    /**
     * Step 2: admin manually pays outside the system, then marks it paid here.
     */
    public function markPaid(Request $request, AffiliatePayout $payout)
    {
        $request->validate([
            'method'     => ['required', 'in:UPI,bank_transfer,other'],
            'reference'  => ['nullable', 'string', 'max:255'],
            'admin_note' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($request, $payout) {
            $payout->update([
                'method'         => $request->method,
                'reference'      => $request->reference,
                'admin_note'     => $request->admin_note,
                'marked_paid_by' => Auth::id(),
                'paid_at'        => now(),
            ]);

            $payout->commissions()->update(['status' => 'paid']);

            $payout->affiliate?->increment('lifetime_paid', $payout->amount);
        });

        return back()->with('success', "Payout #{$payout->id} marked as paid.");
    }
}
