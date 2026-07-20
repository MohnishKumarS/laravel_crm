<?php

namespace App\Exports;

use App\Models\Marketplace\VisitorLogs;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ShopVisitorsExport implements FromCollection, WithHeadings, WithColumnWidths, WithMapping
{
    protected $month;

    public function __construct($month)
    {
        $this->month = $month;
    }

    public function collection()
    {
        $date = Carbon::createFromFormat('Y-m', $this->month);

        return VisitorLogs::withCount('pageViews')
            ->whereYear('first_visit', $date->year)
            ->whereMonth('first_visit', $date->month)
            ->get();
    }

    public function headings(): array
    {
        return [

            'Visitor ID',
            'Country',
            'State',
            'City',
            'Browser',
            'OS',
            'Device',
            'Language',
            'TimeZone',
            'Visit Count',
            'First Visit',
            'Last Visit',
            'Page Views'

        ];
    }

    public function map($visitor): array
    {
        return [
            $visitor->visitor_id,
            $visitor->country,
            $visitor->state,
            $visitor->city,
            $visitor->browser,
            $visitor->os,
            $visitor->device,
            $visitor->language,
            $visitor->timezone,
            $visitor->visit_count,
            $visitor->first_visit,
            $visitor->last_visit,
            $visitor->page_views_count, // Include if needed
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 35,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'I' => 20,
            'J' => 20,
            'K' => 25,
            'L' => 25,
            'M' => 25,
            'N' => 25,
        ];
    }
}
