<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Affiliate\AffiliateKycDocument;
use Illuminate\Http\Request;

class AffiliateKycController extends Controller
{
    public function index(Request $request)
    {
        $documents = AffiliateKycDocument::with('affiliate.user')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.affiliates.kyc', compact('documents'));
    }

    public function review(Request $request, AffiliateKycDocument $document)
    {
        $request->validate([
            'status'     => ['required', 'in:approved,rejected'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $document->update([
            'status'      => $request->status,
            'admin_note'  => $request->admin_note,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        // Sync the affiliate's overall kyc_status to match this document's outcome.
        // If you support multiple documents per affiliate and want ALL of them
        // approved before the affiliate counts as KYC-approved, adjust this to
        // check all documents instead of just this one.
        $document->affiliate->update([
            'kyc_status'       => $request->status,
            'kyc_reviewed_at'  => now(),
        ]);

        return back()->with('success', "KYC document marked as {$request->status}.");
    }

    public function download(AffiliateKycDocument $document)
    {
        // Private disk - only admins can reach this route (protected by
        // role:admin middleware on the group), so this is the one place
        // these identity documents are ever served from.
        return \Illuminate\Support\Facades\Storage::disk('private')->download($document->file_path);
    }
}
