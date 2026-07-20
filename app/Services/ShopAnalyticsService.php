<?php

namespace App\Services;

use App\Models\Marketplace\VisitorLogs;
use App\Models\Marketplace\VisitorViews;

class ShopAnalyticsService
{
    /**
     * Create a new class instance.
     */
    // public function __construct()
    // {
    //     //
    // }

    public function track(array $data)
    {

    // return $data;
        $visitor = VisitorLogs::where('visitor_id', $data['visitor_id'])->first();

        // return $visitor; exit;

        if (!$visitor) {

            $visitor = VisitorLogs::create([
                'visitor_id'   => $data['visitor_id'],
                'ip_address'   => $data['ip_address'] ?? null,
                'country'      => $data['country'] ?? null,
                'state'        => $data['state'] ?? null,
                'city'         => $data['city'] ?? null,
                'browser'      => $data['browser'] ?? null,
                'os'           => $data['os'] ?? null,
                'device'       => $data['device'] ?? null,
                'language'     => $data['language'] ?? null,
                'timezone'     => $data['timezone'] ?? null,
                'referrer'     => $data['referrer'] ?? null,
                'first_visit'  => now(),
                'last_visit'   => now(),
                'visit_count'  => 1,
            ]);
        } else {

            $visitor->update([
                'last_visit'  => now(),
                'visit_count' => $visitor->visit_count + 1,
            ]);
        }

        VisitorViews::create([
            'visitor_id'   => $visitor->id,
            'page_url'     => $data['page_url'],
            'page_title'   => $data['page_title'] ?? null,
            'route'        => $data['route'] ?? null,
            'referrer'     => $data['referrer'] ?? null,
            'time_on_page' => $data['time_on_page'] ?? null,
        ]);

        return true;
    }
}
