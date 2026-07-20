<?php

namespace App\Http\Controllers;

use App\Exports\VisitorsExport;
use App\Models\PageView;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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
            'page_url',
            DB::raw('count(*) as total')
        )
            ->when($selectedMonth, function ($query) use ($date) {
                $query->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month);
            })
            ->whereNotNull('page_title')
            ->where('page_title', '!=', '')
            ->groupBy('page_title', 'page_url')
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

    public function index()
    {
        return view('upload-test');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $file = $request->file('image');

        $fileName = 'prod_' . $file->getClientOriginalName();


        // Change this path to your CodeIgniter uploads folder
        $destination = '/var/www/sttyyl/assets/uploads';

        if (!file_exists($destination)) {
            mkdir($destination, 0775, true);
        }

        $file->move($destination, $fileName);

        return back()->with([
            'success' => 'Image uploaded successfully!',
            'filename' => $fileName,
        ]);
    }
}
