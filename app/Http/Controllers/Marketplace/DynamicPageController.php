<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\DynamicPage;
use Illuminate\Http\Request;

class DynamicPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = DynamicPage::latest()->get();

        return view('admin.dynamic-pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.dynamic-pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request->all();
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'heading' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'page_url' => ['required', 'string', 'max:255', 'unique:dynamic_pages,page_url'],
            'products_id' => ['required', 'string', 'max:255'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],
            'status' => ['required', 'in:0,1'],
            'sort_order' => ['required', 'integer'],
        ]);


        $validated['page_url'] = trim($validated['page_url'], '/');

        // return $validated;


        if ($request->hasFile('banner_image')) {

            $image = $request->file('banner_image');

            $imageName = time() . '_' . $image->getClientOriginalName();

            $image->move(public_path('uploads'), $imageName);

            $validated['banner_image'] = 'uploads/' . $imageName;
        }


        DynamicPage::create($validated);

        return redirect()->route('shop.dynamic-pages.index')->with('message', 'Dynamic page created successfully.')->with('status', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DynamicPage $dynamicPage)
    {
        return view('admin.dynamic-pages.edit', compact('dynamicPage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  DynamicPage $dynamicPage)
    {
        // return $request->all();
        $request->merge([
            'page_url' => trim($request->page_url, '/'),
        ]);
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'heading' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'page_url' => ['required', 'string', 'max:255',  'unique:dynamic_pages,page_url,' . $dynamicPage->id,],
            'products_id' => ['required', 'string', 'max:255'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],
            'status' => ['required', 'in:0,1'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        // $validated['page_url'] = trim($validated['page_url'], '/');

        // return $validated;

        if ($request->hasFile('banner_image')) {

            $image = $request->file('banner_image');

            $imageName = time() . '_image.' . $image->getClientOriginalExtension();

            $image->move(public_path('uploads'), $imageName);

            $validated['banner_image'] = 'uploads/' . $imageName;
        }

        $dynamicPage->update($validated);

        return redirect()->route('shop.dynamic-pages.index')->with('message', 'Dynamic page updated successfully.')->with('status', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DynamicPage $dynamicPage)
    {
        $dynamicPage->delete();

        return redirect()->route('shop.dynamic-pages.index')->with('message', 'Dynamic page deleted successfully.')->with('status', 'success');
    }
}
