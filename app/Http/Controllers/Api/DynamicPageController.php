<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\DynamicPage;
use Illuminate\Http\Request;

class DynamicPageController extends Controller
{
    public function index()
    {
        $pages = DynamicPage::where('status', '1')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Dynamic pages fetched successfully.',
            'data' => $pages,
        ]);
    }

    public function show(Request $request)
    {
        $request->validate([
            'page_url' => ['required', 'string',],
        ]);

        $pageUrl = trim($request->page_url, '/');

        // return $pageUrl;

        $page = DynamicPage::where('page_url', $pageUrl)->first();

        if (!$page) {

            return response()->json([
                'success' => false,
                'message' => 'Page not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Page fetched successfully.',
            'data' => $page,
        ]);
    }
}
