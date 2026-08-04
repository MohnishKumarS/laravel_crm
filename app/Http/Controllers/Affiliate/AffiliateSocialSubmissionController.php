<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Affiliate\AffiliateSocialSubmission;
use App\Models\Affiliate\AffiliateSubmissionMessage;
use App\Notifications\NewAffiliateSubmissionMessage;
use Illuminate\Http\Request;

class AffiliateSocialSubmissionController extends Controller
{
 public function index(Request $request)
{
    $submissions = AffiliateSocialSubmission::with(['affiliate.user', 'messages'])
        ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
        ->latest()
        ->paginate(20)
        ->withQueryString();

    // unread = affiliate-sent messages admin hasn't read yet
    $submissions->getCollection()->transform(function ($s) {
        $s->unread_count = $s->messages->where('sender_role', 'affiliate')->whereNull('read_at')->count();
        return $s;
    });

    return view('admin.affiliates.social-submissions', compact('submissions'));
}

    public function review(Request $request, AffiliateSocialSubmission $submission)
    {
        $request->validate([
            'status'     => ['required', 'in:approved,rejected'],
            // 'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $submission->update([
            'status'      => $request->status,
            // 'admin_note'  => $request->admin_note,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', "Submission #{$submission->id} marked as {$request->status}.");
    }

    public function sendMessage(Request $request, AffiliateSocialSubmission $submission)
{
    $request->validate(['message' => ['required', 'string', 'max:2000']]);

    $message = AffiliateSubmissionMessage::create([
        'submission_id'   => $submission->id,
        'sender_user_id'  => auth()->id(),
        'sender_role'     => 'admin',
        'message'         => $request->message,
    ]);

    // Notify the affiliate who owns this submission
    $submission->affiliate->user?->notify(new NewAffiliateSubmissionMessage($message));

    return back()->with('success', 'Message sent.');
}

public function markThreadRead(AffiliateSocialSubmission $submission)
{
    // Mark all affiliate-sent messages on this thread as read by admin
    AffiliateSubmissionMessage::where('submission_id', $submission->id)
        ->where('sender_role', 'affiliate')
        ->whereNull('read_at')
        ->update(['read_at' => now()]);

    return response()->json(['status' => true]);
}
    
}
