<?php

namespace App\Http\Controllers\Marketplace;

use App\Exports\ShopVisitorsExport;
use App\Http\Controllers\Controller;
use App\Models\Marketplace\VisitorLogs;
use App\Models\Marketplace\VisitorViews;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class AnalyticsController extends Controller
{

    public function index()
    {
        $totalVisitors = VisitorLogs::count();
        $todayVisitors = VisitorLogs::whereDate('last_visit', today())->count();
        $activeVisitors = VisitorLogs::where('last_visit', '>=', now()->subMinutes(5))->count();


        // Build a full 12-month series, filling in zeros for months with no submissions
        $labels = [];
        $visitorData = [];
        // $pageViewsData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $key = $month->format('Y-m');
            $labels[] = $month->format('M y');
            $submissionData[] = $monthlyCounts[$key] ?? 0;
            $visitorData[] = VisitorLogs::whereYear('first_visit', $month->year)
                ->whereMonth('first_visit', $month->month)
                ->count();
        }

        $months = [];

        for ($i = 0; $i < 12; $i++) {

            $date = Carbon::now()->subMonths($i);

            $months[] = [
                'label' => $date->format('F Y'),
                'value' => $date->format('Y-m'),
                'selected' => $i == 0
            ];
        }

        // ============== devices count
        $deviceStats = VisitorLogs::select(
            DB::raw("COALESCE(device, 'Unknown') as device"),
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('device')
            ->get();

        $deviceLabels = $deviceStats->pluck('device')->toArray();
        $deviceCounts = $deviceStats->pluck('total')->toArray();


        // ================  TOP Page views
        $topPages = VisitorViews::select(
            'page_title',
            'page_url',
            DB::raw('COUNT(*) as total_views')
        )
            ->whereNotNull('page_title')
            ->where('page_title', '!=', '')
            ->groupBy('page_title', 'page_url')
            ->orderByDesc('total_views')
            ->limit(10)
            ->get();
        // return $topPages;

        $topPageLabels = $topPages->pluck('page_title')
            ->map(fn($title) => Str::limit($title, 30, '...'))
            ->toArray();

        $topPageViews = $topPages->pluck('total_views')->toArray();


        // ============== country count
        $countryStats = VisitorLogs::select(
            'country',
            DB::raw('COUNT(*) as total')
        )
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



        $countryCodes = [
            'India' => 'in',
            'United States' => 'us',
            'USA' => 'us',
            'China' => 'cn',
            'Singapore' => 'sg',
            'South Korea' => 'kr',
            'The Netherlands' => 'nl',
            'Brazil' => 'br',
            'Australia' => 'au',
            'Paraguay' => 'py',
            'Germany' => 'de',
            'Colombia' => 'co',
            'Venezuela' => 've',
            'France' => 'fr',
            'Ukraine' => 'ua',
            'Nicaragua' => 'ni',
            'Indonesia' => 'id',
            'Nigeria' => 'ng',
            'Türkiye' => 'tr',
            'Turkey' => 'tr',
            'Argentina' => 'ar',
            'Kenya' => 'ke',
            'Iraq' => 'iq',
            'Canada' => 'ca',
            'Russia' => 'ru',
            'Japan' => 'jp',
            'Italy' => 'it',
            'Malaysia' => 'my',
            'Sri Lanka' => 'lk',
            'Pakistan' => 'pk',
            'Nepal' => 'np',
            'Bangladesh' => 'bd',
            'United Kingdom' => 'gb',
            'UK' => 'gb',
        ];

        $countryCoordinates = [
            'India' => [20.5937, 78.9629],
            'United States' => [37.0902, -95.7129],
            'USA' => [37.0902, -95.7129],
            'China' => [35.8617, 104.1954],
            'Singapore' => [1.3521, 103.8198],
            'South Korea' => [35.9078, 127.7669],
            'The Netherlands' => [52.1326, 5.2913],
            'Brazil' => [-14.2350, -51.9253],
            'Australia' => [-25.2744, 133.7751],
            'Paraguay' => [-23.4425, -58.4438],
            'Germany' => [51.1657, 10.4515],
            'Colombia' => [4.5709, -74.2973],
            'Venezuela' => [6.4238, -66.5897],
            'France' => [46.2276, 2.2137],
            'Ukraine' => [48.3794, 31.1656],
            'Nicaragua' => [12.8654, -85.2072],
            'Indonesia' => [-0.7893, 113.9213],
            'Nigeria' => [9.0820, 8.6753],
            'Türkiye' => [38.9637, 35.2433],
            'Turkey' => [38.9637, 35.2433], // Optional alias
            'Argentina' => [-38.4161, -63.6167],
            'Kenya' => [-0.0236, 37.9062],
            'Iraq' => [33.2232, 43.6793],
            'Canada' => [56.1304, -106.3468],
            'Russia' => [61.5240, 105.3188],
            'Japan' => [36.2048, 138.2529],
            'Italy' => [41.8719, 12.5674],
            'Malaysia' => [4.2105, 101.9758],
            'Sri Lanka' => [7.8731, 80.7718],
            'Pakistan' => [30.3753, 69.3451],
            'Nepal' => [28.3949, 84.1240],
            'Bangladesh' => [23.6850, 90.3563],
            'United Kingdom' => [55.3781, -3.4360],
            'UK' => [55.3781, -3.4360],
        ];

        $markers = [];
        $colors = [
            '#1572E8',
            '#31CE36',
            '#FFAD46',
            '#F25961',
            '#6F42C1',
            '#20C997',
            '#FD7E14',
            '#8b23c7',
        ];

        $i = 0;

        foreach ($countryStats as $country) {

            if (!isset($countryCoordinates[$country->country])) {
                continue;
            }
            if ($country->total >= 100) {
                $color = '#1572E8';      // Blue
            } elseif ($country->total >= 50) {
                $color = '#31CE36';      // Green
            } elseif ($country->total >= 25) {
                $color = '#20C997';      // Orange
            } elseif ($country->total >= 10) {
                $color = '#FFAD46';      // Orange
            } else {
                $color = '#F25961';      // Red
            }

            $markers[] = [
                'name' => $country->country . ' (' . $country->total . ')',
                'coords' => $countryCoordinates[$country->country],
                'style' => [
                    'fill' => $color,
                ]
            ];
        }



        $data = [
            'totalVisitors'     => $totalVisitors,
            'todayVisitors'     => $todayVisitors,
            'activeVisitors'    => $activeVisitors,
            'months'            => $months,
            'chartLabels'       => $labels,
            'visitorData'       => $visitorData,
            'deviceLabels'      => $deviceLabels,
            'deviceCounts'      => $deviceCounts,
            'topPageLabels'     => $topPageLabels,
            'topPageViews'      => $topPageViews,
            'countryStats'      => $countryStats,
            'markers'           => $markers,
            'countryCodes'      => $countryCodes,
        ];

        return view('admin.shop.home', $data);
    }

    public function visitorsPerDay(Request $request)
    {
        $selectedMonth = $request->month ?? now()->format('Y-m');

        $date = Carbon::createFromFormat('Y-m', $selectedMonth);
        // return $date->month;

        $labels = [];
        $data = [];

        for ($day = 1; $day <= $date->daysInMonth; $day++) {

            $currentDate = Carbon::create(
                $date->year,
                $date->month,
                $day
            );

            $labels[] = $currentDate->format('d M');

            $data[] = VisitorLogs::whereDate('first_visit', $currentDate)
                ->count();
        }

        return response()->json([
            'status' => true,
            'labels' => $labels,
            'data'   => $data
        ]);
    }
    public function shopVisitors(Request $request)
    {
        $selectedMonth = $request->month ?? now()->format('Y-m');
        // return $selectedMonth;

        $date = Carbon::createFromFormat('Y-m', $selectedMonth);

        $months = [];

        for ($i = 0; $i < 12; $i++) {

            $month = Carbon::now()->subMonths($i);

            $months[] = [
                'label' => $month->format('F Y'),
                'value' => $month->format('Y-m')
            ];
        }


        $visitorQuery = VisitorLogs::query()
            ->when($selectedMonth, function ($query) use ($date) {
                $query->whereYear('first_visit', $date->year)
                    ->whereMonth('first_visit', $date->month);
            });

        $pageViewQuery = VisitorViews::query()
            ->when($selectedMonth, function ($query) use ($date) {
                $query->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month);
            });

        $visitors = (clone $visitorQuery)->withCount('pageViews')->get();
        $totalVisitors = $visitors->count();

        // return $visitors->count();

        $countryStats = (clone $visitorQuery)
            ->select(
                'country',
                DB::raw('MAX(state) as state'),
                DB::raw('MAX(city) as city'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('country')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item) use ($totalVisitors) {

                $item->percentage = $totalVisitors > 0
                    ? round(($item->total / $totalVisitors) * 100, 2)
                    : 0;

                return $item;
            });

        $deviceStats = (clone $visitorQuery)
            ->select(
                'device',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('device')
            ->orderByDesc('total')
            ->get();

        $topPages = (clone $pageViewQuery)
            ->select(
                'page_title',
                'page_url',
                DB::raw('COUNT(*) as total')
            )
            ->whereNotNull('page_title')
            ->where('page_title', '!=', '')
            ->groupBy('page_title', 'page_url')
            ->orderByDesc('total')
            ->get();

        $notFoundPages = (clone $pageViewQuery)
            ->with('visitor')
            ->where('page_title', 'not-found')
            ->latest()
            ->get();

        // return $countryStats;

        return view('admin.analytics.shop', compact(
            // 'visitors',
            'months',
            'selectedMonth',
            'visitors',
            'countryStats',
            'deviceStats',
            'topPages',
            'notFoundPages'
        ));
    }


    public function exportShopVisitors(Request $request)
    {
        return Excel::download(
            new ShopVisitorsExport($request->month),
            'marketplace-visitors-' . $request->month . '.xlsx'
        );
    }
}
