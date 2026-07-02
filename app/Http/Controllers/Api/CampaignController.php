<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    /**
     * GET /api/campaigns
     * Admin: list every campaign (for the Next.js dashboard table).
     */
    public function index()
    {
        $campaigns = Campaign::orderByDesc('priority')
            ->orderByDesc('start_at')
            ->get()
            ->map(fn ($campaign) => $this->formatCampaign($campaign));

        return response()->json($campaigns);
    }

    /**
     * GET /api/campaigns/current
     * Public: the single campaign the storefront should render right now.
     */
    public function current()
    {
        $campaign = Campaign::current();

        if (! $campaign) {
            return response()->json(['data' => null]);
        }

        return response()->json($this->formatCampaign($campaign));
    }

    /**
     * GET /api/campaigns/{campaign}
     * Admin: single campaign (for the edit form).
     */
    public function show(Campaign $campaign)
    {
        return response()->json($this->formatCampaign($campaign));
    }

    /**
     * POST /api/campaigns
     * Admin: create. Accepts multipart/form-data if background_image is included.
     */
    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        if ($request->hasFile('background_image')) {
            $data['background_image'] = $request->file('background_image')->store('campaigns', 'public');
        }

        $this->applySingleDefault($data);

        $campaign = Campaign::create($data);

        return response()->json($this->formatCampaign($campaign), 201);
    }

    /**
     * PUT/PATCH /api/campaigns/{campaign}
     * Admin: update. Send as multipart/form-data with a `_method=PUT` field
     * if uploading a new background_image (browsers can't send PUT with files).
     */
    public function update(Request $request, Campaign $campaign)
    {
        $data = $this->validated($request, $campaign);
        $data['slug'] = $data['slug'] ?? $campaign->slug;

        if ($request->hasFile('background_image')) {
            if ($campaign->background_image) {
                Storage::disk('public')->delete($campaign->background_image);
            }
            $data['background_image'] = $request->file('background_image')->store('campaigns', 'public');
        }

        $this->applySingleDefault($data, $campaign->id);

        $campaign->update($data);

        return response()->json($this->formatCampaign($campaign->fresh()));
    }

    /**
     * DELETE /api/campaigns/{campaign}
     */
    public function destroy(Campaign $campaign)
    {
        if ($campaign->background_image) {
            Storage::disk('public')->delete($campaign->background_image);
        }

        $campaign->delete();

        return response()->json(['message' => 'Campaign deleted.']);
    }

    /**
     * Shapes a Campaign model into the JSON payload sent to the frontend.
     * Mirrors PostApiController::formatPost() for consistency.
     */
    protected function formatCampaign(Campaign $campaign): array
    {
        return [
            'id'    => $campaign->id,
            'name'  => $campaign->name,
            'slug'  => $campaign->slug,

            'status'        => $campaign->status_label,
            'is_published'  => $campaign->is_published,
            'is_default'    => $campaign->is_default,
            'priority'      => $campaign->priority,
            'start_at'      => optional($campaign->start_at)->toIso8601String(),
            'end_at'        => optional($campaign->end_at)->toIso8601String(),

            'theme' => [
                'bg_start'          => $campaign->theme_bg_start,
                'bg_end'            => $campaign->theme_bg_end,
                'accent_color'      => $campaign->accent_color,
                'accent_text_color' => $campaign->accent_text_color,
                'eyebrow_color'     => $campaign->eyebrow_color,
            ],

            'background_image' => $campaign->background_image_url,

            'copy' => [
                'eyebrow'           => $campaign->eyebrow,
                'badge_text'        => $campaign->badge_text,
                'heading'           => $campaign->heading,
                'heading_highlight' => $campaign->heading_highlight,
                'subtext'           => $campaign->subtext,
                'cta1_text'         => $campaign->cta1_text,
                'cta1_url'          => $campaign->cta1_url,
                'cta2_text'         => $campaign->cta2_text,
                'cta2_url'          => $campaign->cta2_url,
            ],

            'announcement' => $campaign->announcement_text ? [
                'text'      => $campaign->announcement_text,
                'link_text' => $campaign->announcement_link_text,
                'link_url'  => $campaign->announcement_link_url,
            ] : null,

            'countdown' => $campaign->show_countdown && $campaign->countdown_end_at ? [
                'end_at' => $campaign->countdown_end_at->toIso8601String(),
            ] : null,

            'created_at' => $campaign->created_at?->toIso8601String(),
            'updated_at' => $campaign->updated_at?->toIso8601String(),
        ];
    }

    private function validated(Request $request, ?Campaign $campaign = null): array
    {
        $data = $request->validate([
            'name'                    => 'required|string|max:255',
            'slug'                     => 'nullable|string|max:255|unique:campaigns,slug' . ($campaign ? ",{$campaign->id}" : ''),
            'start_at'                 => 'nullable|date',
            'end_at'                   => 'nullable|date|after_or_equal:start_at',
            'is_published'             => 'boolean',
            'is_default'               => 'boolean',
            'priority'                 => 'nullable|integer|min:0',

            'theme_bg_start'           => 'required|string|max:9',
            'theme_bg_end'             => 'required|string|max:9',
            'accent_color'             => 'required|string|max:9',
            'accent_text_color'        => 'required|string|max:9',
            'eyebrow_color'            => 'required|string|max:9',

            'background_image'        => 'nullable|image|max:4096',

            'eyebrow'                  => 'nullable|string|max:255',
            'badge_text'               => 'nullable|string|max:255',
            'heading'                  => 'required|string|max:255',
            'heading_highlight'        => 'nullable|string|max:255',
            'subtext'                  => 'nullable|string',
            'cta1_text'                => 'nullable|string|max:255',
            'cta1_url'                 => 'nullable|string|max:255',
            'cta2_text'                => 'nullable|string|max:255',
            'cta2_url'                 => 'nullable|string|max:255',

            'announcement_text'        => 'nullable|string|max:255',
            'announcement_link_text'   => 'nullable|string|max:255',
            'announcement_link_url'    => 'nullable|string|max:255',

            'show_countdown'           => 'boolean',
            'countdown_end_at'         => 'nullable|date',
        ]);

        $data['is_published']   = $request->boolean('is_published');
        $data['is_default']     = $request->boolean('is_default');
        $data['show_countdown'] = $request->boolean('show_countdown');
        $data['priority']       = $data['priority'] ?? 0;

        return $data;
    }

    private function applySingleDefault(array $data, ?int $exceptId = null): void
    {
        if (! empty($data['is_default'])) {
            Campaign::when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))
                ->update(['is_default' => false]);
        }
    }
}
