<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $formsCount = Form::count();
        $postsCount = Post::count();
        $submissionsCount = FormSubmission::count();

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
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $key = $month->format('Y-m');
            $labels[] = $month->format('M Y');
            $data[] = $monthlyCounts[$key] ?? 0;
        }

        // Today's submissions count, for a "Daily" style stat if needed
        $todaySubmissions = FormSubmission::whereDate('created_at', today())->count();

        return view('admin.dashboard', [
            'formsCount'        => $formsCount,
            'postsCount'        => $postsCount,
            'submissionsCount'  => $submissionsCount,
            'todaySubmissions'  => $todaySubmissions,
            'chartLabels'       => $labels,
            'chartData'         => $data,
        ]);
    }
}
