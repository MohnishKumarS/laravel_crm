<?php

namespace App\Exports;

use App\Models\Marketplace\VisitorLogs;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ShopVisitorsExport implements FromQuery, WithHeadings, WithMapping, WithColumnWidths, WithChunkReading, ShouldAutoSize, ShouldQueue
{
    use Exportable;

    protected $month;

    public function __construct($month)
    {
        $this->month = $month;
    }

    public function query()
    {
        $date = Carbon::createFromFormat('Y-m', $this->month);

        // VisitorLogs::$connection is 'marketplace' — the query builder
        // picks that up automatically, no extra connection() call needed.
        return VisitorLogs::query()
            ->withCount('pageViews')
            ->whereYear('first_visit', $date->year)
            ->whereMonth('first_visit', $date->month)
            ->orderBy('first_visit');
    }

    /**
     * Rows pulled from the DB per batch, instead of loading the whole
     * month into memory at once via ->get(). This is what let 60k rows
     * work locally but choke around 20k on the server — see the timeout
     * checklist from earlier.
     */
    public function chunkSize(): int
    {
        return 1000;
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
            'Page Views',
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
            optional($visitor->first_visit)->format('Y-m-d H:i:s'),
            optional($visitor->last_visit)->format('Y-m-d H:i:s'),
            $visitor->page_views_count,
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
            'H' => 20,
            'I' => 20,
            'J' => 20,
            'K' => 25,
            'L' => 25,
            'M' => 15,
        ];
    }
}