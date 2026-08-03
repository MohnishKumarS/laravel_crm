<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AffiliateSocialSubmission;
use Illuminate\Http\Request;

class SocialSubmissionController extends Controller
{
    /**
     * GET /api/affiliate/social-submissions
     * The affiliate's own submission history and review status.
     */
    public function index(Request $request)
    {
        $affiliate = $this->affiliateOrFail($request);

        $submissions = $affiliate->socialSubmissions()->latest()->paginate(20);

        return response()->json($submissions);
    }

    /**
     * POST /api/affiliate/social-submissions
     * Body: { "post_url": "https://instagram.com/p/...", "platform": "instagram", "product_id": 123 }
     */
    public function store(Request $request)
    {
        $affiliate = $this->affiliateOrFail($request);

        $request->validate([
            'post_url'   => ['required', 'url', 'max:500'],
            'platform'   => ['required', 'in:instagram,facebook,youtube,tiktok,twitter,other'],
            'product_id' => ['nullable', 'integer'],
        ]);

        $submission = AffiliateSocialSubmission::create([
            'affiliate_id' => $affiliate->id,
            'product_id'   => $request->product_id,
            'post_url'     => $request->post_url,
            'platform'     => $request->platform,
            'status'       => 'pending',
        ]);

        return response()->json(['submission' => $submission], 201);
    }

    private function affiliateOrFail(Request $request)
    {
        $affiliate = $request->user()->affiliate;
        abort_if(!$affiliate, 404, 'Not an affiliate.');
        abort_if(!$affiliate->isApproved(), 403, 'Affiliate account not yet approved.');

        return $affiliate;
    }
}
