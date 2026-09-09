<?php

namespace App\Http\Controllers;

use App\Exports\VisitorsExport;
use App\Models\PageView;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class AnalyticsController extends Controller
{
    // public function visitors()
    // {
    //     $visitors = Visitor::latest('last_visit')->get();

    //     return view('admin.analytics.visitors', compact('visitors'));
    // }

    public function visitors(Request $request)
    {
        $selectedMonth = $request->month ?? now()->format('Y-m');

        $date = Carbon::createFromFormat('Y-m', $selectedMonth);

        $visitors = Visitor::withCount('pageViews')
            ->whereYear('first_visit', $date->year)
            ->whereMonth('first_visit', $date->month)
            ->latest('last_visit')
            ->get();

        // return $visitors;

        $months = [];

        for ($i = 0; $i < 12; $i++) {

            $month = Carbon::now()->subMonths($i);

            $months[] = [
                'label' => $month->format('F Y'),
                'value' => $month->format('Y-m')
            ];
        }

        $totalVisitors = Visitor::when($selectedMonth, function ($query) use ($date) {
            $query->whereYear('first_visit', $date->year)
                ->whereMonth('first_visit', $date->month);
        })
            ->count();

        $countryStats = Visitor::select(
            'country',
            DB::raw('MAX(state) as state'),
            DB::raw('MAX(city) as city'),
            DB::raw('count(*) as total')
        )
            ->when($selectedMonth, function ($query) use ($date) {
                $query->whereYear('first_visit', $date->year)
                    ->whereMonth('first_visit', $date->month);
            })
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->groupBy('country')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item) use ($totalVisitors) {

                $item->percentage = $totalVisitors > 0
                    ? round(($item->total / $totalVisitors) * 100, 2)
                    : 0;

                return $item;
            });

        $deviceStats = Visitor::select(
            'device',
            DB::raw('count(*) as total')
        )
            ->when($selectedMonth, function ($query) use ($date) {
                $query->whereYear('first_visit', $date->year)
                    ->whereMonth('first_visit', $date->month);
            })
            ->groupBy('device')
            ->orderByDesc('total')
            ->get();

        $topPages = PageView::select(
            'page_title',
            DB::raw('MAX(page_url) as page_url'),
            DB::raw('count(*) as total')
        )
            ->when($selectedMonth, function ($query) use ($date) {
                $query->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month);
            })
            ->whereNotNull('page_title')
            ->where('page_title', '!=', '')
            ->groupBy('page_title')
            ->orderByDesc('total')
            ->get();

        $notFoundPages = PageView::with('visitor')
            ->when($selectedMonth, function ($query) use ($date) {
                $query->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month);
            })
            ->where('page_title', 'not-found')
            ->orderByDesc('id')
            ->get();

        //   $products = DB::connection('marketplace')
        //         ->table('products')
        //         ->limit(10)
        //         ->get();

        //     return $products;

        return view('admin.analytics.visitors', compact(
            'visitors',
            'months',
            'selectedMonth',
            'totalVisitors',
            'countryStats',
            'deviceStats',
            'topPages',
            'notFoundPages'
        ));
    }

    public function exportVisitors(Request $request)
    {
        return Excel::download(
            new VisitorsExport($request->month),
            'visitors-' . $request->month . '.xlsx'
        );
    }

    public function pageViews(Visitor $visitor)
    {
        $pageViews = $visitor->pageViews()
            ->select([
                'page_title',
                'page_url',
                'referrer',
            ])
            ->latest()
            ->get();

        return response()->json([
            'visitor' => [
                'entry_point' => $visitor->referrer,
                'campaign' => $visitor->utm_campaign,
                'device' => $visitor->device,
                'browser' => $visitor->browser,
                // 'country' => $visitor->country,
                // 'visit_count' => $visitor->visit_count,
            ],
            'page_views' => $pageViews,
        ]);
    }


    public function visitorsData(Request $request)
    {
        $selectedMonth = $request->month ?? now()->format('Y-m');

        $date = Carbon::createFromFormat('Y-m', $selectedMonth);

        $query = Visitor::query()
            ->withCount('pageViews')
            ->whereBetween('first_visit', [
                $date->copy()->startOfMonth(),
                $date->copy()->endOfMonth()
            ]);
            // ->latest('last_visit');

        // return $query;

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('country', fn($row) => $row->country ?: '-')
            ->editColumn('state', fn($row) => $row->state ?: '-')
            ->editColumn('city', fn($row) => $row->city ?: '-')
            ->addColumn('status', function ($row) {
                return $row->last_visit >= now()->subMinutes(5)
                    ? '<span class="badge badge-success">Active</span>'
                    : '<span class="badge badge-secondary">Offline</span>';
            })
            ->addColumn('visitor_db_id', function ($row) {
                return $row->id;
            })
            ->rawColumns(['status'])
            ->orderColumn('last_visit', 'last_visit $1')
            ->make(true);
    }
}
