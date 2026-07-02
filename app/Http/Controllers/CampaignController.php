<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::orderByDesc('priority')->orderByDesc('start_at')->get();

        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('admin.campaigns.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        if ($request->hasFile('background_image')) {
            $data['background_image'] = $request->file('background_image')->store('campaigns', 'public');
        }

        $this->applySingleDefault($data);

        Campaign::create($data);

        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign created.');
    }

    public function edit(Campaign $campaign)
    {
        return view('admin.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $data = $this->validated($request, $campaign);
        // print_r($data);exit; // Debugging line to print the validated data

        $data['slug'] = $data['slug'] ?? $campaign->slug;

        if ($request->hasFile('background_image')) {
            if ($campaign->background_image) {
                Storage::disk('public')->delete($campaign->background_image);
            }
            $data['background_image'] = $request->file('background_image')->store('campaigns', 'public');
        }

        $this->applySingleDefault($data, $campaign->id);

        $campaign->update($data);

        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign updated.');
    }

    public function destroy(Campaign $campaign)
    {
        if ($campaign->background_image) {
            Storage::disk('public')->delete($campaign->background_image);
        }

        $campaign->delete();

        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign deleted.');
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

        // checkboxes don't send a value when unchecked
        $data['is_published']  = $request->boolean('is_published');
        $data['is_default']    = $request->boolean('is_default');
        $data['show_countdown']= $request->boolean('show_countdown');
        $data['priority']      = $data['priority'] ?? 0;

        return $data;
    }

    /** Only one campaign may be the evergreen default at a time. */
    private function applySingleDefault(array $data, ?int $exceptId = null): void
    {
        if (! empty($data['is_default'])) {
            Campaign::when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))
                ->update(['is_default' => false]);
        }
    }
}
