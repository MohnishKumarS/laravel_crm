<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Affiliate\AffiliateSocialSubmission;
use Illuminate\Http\Request;

class AffiliateSocialSubmissionController extends Controller
{
    public function index(Request $request)
    {
        // print_r('sf');exit;
         // Debugging line to print request parameters
        $submissions = AffiliateSocialSubmission::with('affiliate.user')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.affiliates.social-submissions', compact('submissions'));
    }

    public function review(Request $request, AffiliateSocialSubmission $submission)
    {
        $request->validate([
            'status'     => ['required', 'in:approved,rejected'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $submission->update([
            'status'      => $request->status,
            'admin_note'  => $request->admin_note,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', "Submission #{$submission->id} marked as {$request->status}.");
    }
}
