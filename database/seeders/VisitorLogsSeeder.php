<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VisitorLogsSeeder extends Seeder
{
    /**
     * VisitorLogs::$connection is 'marketplace', so every query here must
     * go through that connection explicitly rather than the default one.
     */
    protected string $connection = 'marketplace';

    protected int $totalVisitors = 11000;
    protected int $chunkSize = 500;

    public function run(): void
    {
        $faker = Factory::create();
        $db = DB::connection($this->connection);

        // Clear existing data
        // $db->table('visitor_views')->truncate();
        // $db->table('visitor_logs')->truncate();

        $pages = [
            '/', '/about', '/contact', '/services', '/products',
            '/pricing', '/blog', '/faq', '/login', '/register',
            '/dashboard', '/analytics', '/profile', '/events', '/gallery',
        ];

        $titles = [
            'Home', 'About Us', 'Contact', 'Services', 'Products',
            'Pricing', 'Blog', 'FAQ', 'Login', 'Register',
            'Dashboard', 'Analytics', 'Profile', 'Events', 'Gallery',
        ];

        $browsers   = ['Chrome', 'Firefox', 'Edge', 'Safari', 'Opera'];
        $os         = ['Windows', 'Linux', 'Android', 'iOS', 'macOS'];
        $devices    = ['Desktop', 'Mobile', 'Tablet'];
        $languages  = ['en', 'en-IN', 'ta-IN', 'hi-IN'];
        $timezones  = ['Asia/Kolkata', 'Europe/London', 'America/New_York', 'Asia/Dubai'];
        $countries  = ['India', 'USA', 'UK', 'Canada', 'Australia'];
        $states     = ['Tamil Nadu', 'Karnataka', 'Kerala', 'Maharashtra', 'Delhi'];
        $cities     = ['Chennai', 'Bengaluru', 'Kochi', 'Mumbai', 'Delhi'];

        // Full-URL referrers, matching the shape in your sample row
        // (https://google.com), plus a chance of no referrer at all.
        $referrers = [
            'https://google.com',
            'https://facebook.com',
            'https://linkedin.com',
            'https://twitter.com',
            'https://bing.com',
            null,
        ];

        $campaigns = ['Summer Sale', 'Email Campaign', 'Facebook Ads', 'Google Ads', 'Organic', null];

        $this->command?->getOutput()->progressStart($this->totalVisitors);

        for ($start = 0; $start < $this->totalVisitors; $start += $this->chunkSize) {
            $count = min($this->chunkSize, $this->totalVisitors - $start);

            $visitorRows = [];
            $visitorUuids = [];

            for ($i = 0; $i < $count; $i++) {
                $firstVisit = $faker->dateTimeBetween('-30 days', '-2 days');
                $lastVisit  = $faker->dateTimeBetween($firstVisit, 'now');
                $uuid = (string) Str::uuid();
                $visitorUuids[] = $uuid;

                $visitorRows[] = [
                    'visitor_id'   => $uuid,
                    // ~15% of visitors have no captured IP, like your sample row.
                    'ip_address'   => $faker->boolean(85) ? $faker->ipv4() : null,

                    'country'      => $faker->randomElement($countries),
                    'state'        => $faker->randomElement($states),
                    'city'         => $faker->randomElement($cities),

                    'browser'      => $faker->randomElement($browsers),
                    'os'           => $faker->randomElement($os),
                    'device'       => $faker->randomElement($devices),

                    'language'     => $faker->randomElement($languages),
                    'timezone'     => $faker->randomElement($timezones),

                    'referrer'     => $faker->randomElement($referrers),
                    'utm_campaign' => $faker->randomElement($campaigns),

                    'first_visit'  => $firstVisit,
                    'last_visit'   => $lastVisit,
                    'visit_count'  => rand(1, 20),

                    'created_at'   => $firstVisit,
                    'updated_at'   => $lastVisit,
                ];
            }

            // Bulk insert this chunk of visitor_logs in one query.
            $db->table('visitor_logs')->insert($visitorRows);

            // insert() doesn't return ids for multi-row inserts, so pull
            // back the auto-increment ids we just created via the uuids.
            $idMap = $db->table('visitor_logs')
                ->whereIn('visitor_id', $visitorUuids)
                ->pluck('id', 'visitor_id');

            $viewRows = [];

            foreach ($visitorUuids as $uuid) {
                $logId = $idMap[$uuid] ?? null;
                if (! $logId) {
                    continue;
                }

                $viewsForThisVisitor = rand(1, 15);

                for ($v = 0; $v < $viewsForThisVisitor; $v++) {
                    $index = array_rand($pages);

                    $viewRows[] = [
                        // Matches VisitorLogs::pageViews() -> hasMany(VisitorViews::class, 'visitor_id', 'id')
                        'visitor_id'   => $logId,
                        'page_url'     => $pages[$index],
                        'page_title'   => $titles[$index],
                        'route'        => str_replace('/', '', $pages[$index]) ?: 'home',
                        'referrer'     => $faker->randomElement($referrers),
                        'time_on_page' => rand(5, 600),
                        'created_at'   => $faker->dateTimeBetween('-30 days', 'now'),
                        'updated_at'   => now(),
                    ];
                }
            }

            // Insert views in sub-chunks so no single query carries an
            // unreasonable number of rows.
            foreach (array_chunk($viewRows, 1000) as $subChunk) {
                $db->table('visitor_views')->insert($subChunk);
            }

            $this->command?->getOutput()->progressAdvance($count);
        }

        $this->command?->getOutput()->progressFinish();
    }
}