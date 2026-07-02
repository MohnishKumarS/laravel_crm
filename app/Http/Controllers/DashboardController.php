<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\PageView;
use App\Models\Post;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // Daily Traffic Overview
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

        // TOP Page views
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

        $topPageLabels = $topPages->pluck('page_title')->toArray();

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

        // return $mostViewedPage;

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

    
}
