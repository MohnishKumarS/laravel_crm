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
        'affiliate' => $a,
        'balance'   => $a->commissions()->where('status', 'approved')->whereNull('payout_id')->sum('commission_amount'),
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

     $commissions = AffiliateCommission::where('affiliate_id', $affiliate->id)
    ->where('status', 'approved')
    ->whereNull('payout_id')   // <-- added: skip ones already batched
    ->lockForUpdate()
    ->get();

        $total = $commissions->sum('commission_amount');

        if ($total <= 0) {
            return back()->with('error', 'No approved commissions to pay out.');
        }

        $payout = AffiliatePayout::create([
            'affiliate_id' => $affiliate->id,
            'amount'       => $total,
            'method'       => 'paypal',
        ]);

        AffiliateCommission::whereIn('id', $commissions->pluck('id'))
            ->update(['payout_id' => $payout->id]);

        return back()->with('success', "Payout batch #{$payout->id} created for {$total}.");
    });
   }

    /**
     * Step 2: admin manually pays outside the system, then marks it paid here.
     */
    public function markPaid(Request $request, AffiliatePayout $payout)
    {
        $request->validate([
            'method'     => ['required', 'in:paypal,bank_transfer,other'],
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
