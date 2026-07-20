<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use App\Services\ShopAnalyticsService;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'visitor_id' => 'required|uuid',
            'page_url' => 'required',
        ]);

        // return $request->all();

        $data = app(AnalyticsService::class)->track($request->all());

        // return $data;
        if ($data) {
            return response()->json([
                'status' => true,
                'message' => 'Visitor updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'failed to updated'
            ]);
        }
    }

    public function shopVisitors(Request $request)
    {
        $request->validate([
            'visitor_id' => 'required|uuid',
            'page_url' => 'required',
        ]);

        // return $request->all();

        $data = app(ShopAnalyticsService::class)->track($request->all());

        // return $data;
        if ($data) {
            return response()->json([
                'status' => true,
                'message' => 'Visitor updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'failed to updated'
            ]);
        }
    }


}
