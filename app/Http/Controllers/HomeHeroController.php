<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HomeHeroSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeHeroController extends Controller
{
    public function index()
    {
        $sections = HomeHeroSection::latest('id')->get();

        return view('admin.home-hero.index', compact('sections'));
    }

    public function create()
    {
        return view('admin.home-hero.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data = $this->handleUploads($request, $data);
        $data['buttons'] = $this->collectRepeater($request, 'button_text', 'button_url', 'button_style');
        $data['features'] = $this->collectRepeater($request, 'feature_title', 'feature_description');

        $this->applySingleActive($data);

        HomeHeroSection::create($data);

        return redirect()->route('admin.home-hero.index')->with('success', 'Hero section created.');
    }

    public function edit(HomeHeroSection $homeHero)
    {
        return view('admin.home-hero.edit', ['section' => $homeHero]);
    }

    public function update(Request $request, HomeHeroSection $homeHero)
    {
        $data = $this->validated($request);
        $data = $this->handleUploads($request, $data, $homeHero);
        $data['buttons'] = $this->collectRepeater($request, 'button_text', 'button_url', 'button_style');
        $data['features'] = $this->collectRepeater($request, 'feature_title', 'feature_description');

        $this->applySingleActive($data, $homeHero->id);

        $homeHero->update($data);

        return redirect()->route('admin.home-hero.index')->with('success', 'Hero section updated.');
    }

    public function destroy(HomeHeroSection $homeHero)
    {
        foreach (['image_main', 'image_secondary_1', 'image_secondary_2'] as $field) {
            if ($homeHero->$field) {
                Storage::disk('public')->delete($homeHero->$field);
            }
        }

        $homeHero->delete();

        return redirect()->route('admin.home-hero.index')->with('success', 'Hero section deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'is_active'          => 'boolean',
            'heading'            => 'required|string|max:255',
            'heading_highlight'  => 'nullable|string|max:255',
            'subtext'            => 'nullable|string',
            'badge_text'         => 'nullable|string|max:255',
            'image_main'         => 'nullable|image|max:4096',
            'image_secondary_1'  => 'nullable|image|max:4096',
            'image_secondary_2'  => 'nullable|image|max:4096',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    private function handleUploads(Request $request, array $data, ?HomeHeroSection $existing = null): array
    {
        foreach (['image_main', 'image_secondary_1', 'image_secondary_2'] as $field) {
            if ($request->hasFile($field)) {
                if ($existing && $existing->$field) {
                    Storage::disk('public')->delete($existing->$field);
                }
                $data[$field] = $request->file($field)->store('home-hero', 'public');
            } else {
                unset($data[$field]); // keep existing value, don't overwrite with null
            }
        }

        return $data;
    }

    /**
     * Turns parallel arrays like button_text[], button_url[], button_style[]
     * (or feature_title[], feature_description[]) into a clean JSON-ready array,
     * dropping any fully-empty rows.
     */
    private function collectRepeater(Request $request, string ...$fields): array
    {
        $columns = array_map(fn ($f) => $request->input($f, []), $fields);
        $count = max(array_map('count', $columns) ?: [0]);

        $rows = [];
        for ($i = 0; $i < $count; $i++) {
            $row = [];
            foreach ($fields as $idx => $field) {
                $key = str_contains($field, 'button_') ? str_replace('button_', '', $field) : str_replace('feature_', '', $field);
                $row[$key] = $columns[$idx][$i] ?? null;
            }
            if (collect($row)->filter()->isNotEmpty()) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    private function applySingleActive(array $data, ?int $exceptId = null): void
    {
        if (! empty($data['is_active'])) {
            HomeHeroSection::when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))
                ->update(['is_active' => false]);
        }
    }
}
