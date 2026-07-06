<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HomeHeroSection;

class HomeHeroController extends Controller
{
    /**
     * GET /api/home-hero
     * Public: the active hero section for the homepage.
     */
    public function active()
    {
        $section = HomeHeroSection::active();

        if (! $section) {
            return response()->json(['data' => null]);
        }

        return response()->json($this->formatSection($section));
    }

    protected function formatSection(HomeHeroSection $section): array
    {
        return [
            'id'                 => $section->id,
            'heading'            => $section->heading,
            'heading_highlight'  => $section->heading_highlight,
            'subtext'            => $section->subtext,
            'badge_text'         => $section->badge_text,

            'images' => [
                'main'        => $section->image_main_url,
                'secondary_1' => $section->image_secondary_1_url,
                'secondary_2' => $section->image_secondary_2_url,
            ],

            'buttons'  => $section->buttons ?? [],   // [{text, url, style}]
            'features' => $section->features ?? [],  // [{title, description}]
        ];
    }
}
