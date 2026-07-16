<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\PageView;
use App\Models\Post;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $formsCount = Form::count();
        $postsCount = Post::count();
        $submissionsCount = FormSubmission::count();

        // return Carbon::today()->subYear(3);

        // Submissions per month for the last 12 months
        $monthlyCounts = FormSubmission::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as ym"),
            DB::raw('count(*) as total')
        )
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('total', 'ym');

        // Build a full 12-month series, filling in zeros for months with no submissions
        $labels = [];
        $submissionData = [];
        $visitorData = [];
        $pageViewsData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $key = $month->format('Y-m');
            $labels[] = $month->format('M y');
            $submissionData[] = $monthlyCounts[$key] ?? 0;
            $visitorData[] = Visitor::whereYear('first_visit', $month->year)
                ->whereMonth('first_visit', $month->month)
                ->count();
            $pageViewsData[] = PageView::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        // ================ Daily Traffic Overview
        $chartPerDayLabels = [];
        $visitorPerDayData = [];

        for ($i = 29; $i >= 0; $i--) {

            $date = Carbon::today()->subDays($i);

            $chartPerDayLabels[] = $date->format('d M');

            $visitorPerDayData[] = Visitor::whereDate('first_visit', $date)
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

        // return $months;

        // ================  TOP Page views
        $topPages = PageView::select(
            'page_title',
            'page_url',
            DB::raw('COUNT(*) as total_views')
        )
            ->groupBy('page_title', 'page_url')
            ->orderByDesc('total_views')
            ->limit(10)
            ->get();
        // return $topPages;

        $topPageLabels = $topPages->pluck('page_title')
            ->map(fn($title) => Str::limit($title, 30, '...'))
            ->toArray();

        $topPageViews = $topPages->pluck('total_views')->toArray();
        // return $topPageViews;


        // Today's submissions count, for a "Daily" style stat if needed
        $todaySubmissions = FormSubmission::whereDate('created_at', today())->count();

        // Analytics
        $totalVisitors = Visitor::count();
        $todayVisitors = Visitor::whereDate('last_visit', today())->count();
        $activeVisitors = Visitor::where('last_visit', '>=', now()->subMinutes(5))->count();

        // Top viewed page
        $mostViewedPage = PageView::select('page_title', 'page_url', DB::raw('COUNT(*) as total_views'))
            ->groupBy('page_title', 'page_url')
            ->orderByDesc('total_views')
            ->first();

        // ============== devices count
        $deviceStats = Visitor::select(
            DB::raw("COALESCE(device, 'Unknown') as device"),
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('device')
            ->get();

        $deviceLabels = $deviceStats->pluck('device')->toArray();
        $deviceCounts = $deviceStats->pluck('total')->toArray();

        // country count
        $countryStats = Visitor::select(
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



        // return $countryStats;

        return view('admin.dashboard', [
            'formsCount'        => $formsCount,
            'postsCount'        => $postsCount,
            'submissionsCount'  => $submissionsCount,
            'todaySubmissions'  => $todaySubmissions,
            'chartLabels'       => $labels,
            'chartData'         => $submissionData,
            'visitorData'       => $visitorData,
            'chartPerDayLabels' => $chartPerDayLabels,
            'visitorPerDayData' => $visitorPerDayData,
            'topPageLabels'     => $topPageLabels,
            'topPageViews'      => $topPageViews,
            'months'            => $months,
            'pageViewsData'     => $pageViewsData,
            'deviceLabels'      => $deviceLabels,
            'deviceCounts'      => $deviceCounts,
            'countryStats'      => $countryStats,
            'markers'           => $markers,
            'countryCodes'      => $countryCodes,
            'totalVisitors'     => $totalVisitors,
            'todayVisitors'     => $todayVisitors,
            'activeVisitors'    => $activeVisitors,
            'mostViewedPage'    => $mostViewedPage
        ]);
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

            $data[] = Visitor::whereDate('first_visit', $currentDate)
                ->count();
        }

        return response()->json([
            'status' => true,
            'labels' => $labels,
            'data'   => $data
        ]);
    }


    public function markAllRead()
    {
        $notify =  Auth::user()
            ->unreadNotifications
            ->markAsRead();

        return back();
    }
}
