<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        // Clear existing data
        // DB::table('page_views')->truncate();
        // DB::table('visitors')->truncate();

        $pages = [
            '/',
            '/about',
            '/contact',
            '/services',
            '/products',
            '/pricing',
            '/blog',
            '/faq',
            '/login',
            '/register',
            '/dashboard',
            '/analytics',
            '/profile',
            '/events',
            '/gallery',
        ];

        $titles = [
            'Home',
            'About Us',
            'Contact',
            'Services',
            'Products',
            'Pricing',
            'Blog',
            'FAQ',
            'Login',
            'Register',
            'Dashboard',
            'Analytics',
            'Profile',
            'Events',
            'Gallery',
        ];

        $browsers = [
            'Chrome',
            'Firefox',
            'Edge',
            'Safari',
            'Opera',
        ];

        $os = [
            'Windows',
            'Linux',
            'Android',
            'iOS',
            'macOS',
        ];

        $devices = [
            'Desktop',
            'Mobile',
            'Tablet',
        ];

        $languages = [
            'en',
            'en-US',
            'ta-IN',
            'hi-IN',
        ];

        $timezones = [
            'Asia/Kolkata',
            'Europe/London',
            'America/New_York',
            'Asia/Dubai',
        ];

        $countries = [
            'India',
            'USA',
            'UK',
            'Canada',
            'Australia',
        ];

        $states = [
            'Tamil Nadu',
            'Karnataka',
            'Kerala',
            'Maharashtra',
            'Delhi',
        ];

        $cities = [
            'Chennai',
            'Bengaluru',
            'Kochi',
            'Mumbai',
            'Delhi',
        ];

        $visitorIds = [];

        // 50 Visitors
        for ($i = 1; $i <= 50; $i++) {

            $firstVisit = $faker->dateTimeBetween('-30 days', '-2 days');
            $lastVisit = $faker->dateTimeBetween($firstVisit, 'now');

            $visitorId = DB::table('visitors')->insertGetId([
                'visitor_id'   => Str::uuid(),
                'ip_address'   => $faker->ipv4(),

                'country'      => $faker->randomElement($countries),
                'state'        => $faker->randomElement($states),
                'city'         => $faker->randomElement($cities),

                'browser'      => $faker->randomElement($browsers),
                'os'           => $faker->randomElement($os),
                'device'       => $faker->randomElement($devices),

                'language'     => $faker->randomElement($languages),
                'timezone'     => $faker->randomElement($timezones),

                'referrer'     => $faker->randomElement([
                    'Google',
                    'Facebook',
                    'LinkedIn',
                    'Twitter',
                    'Direct',
                    'Bing',
                ]),

                'utm_campaign' => $faker->randomElement([
                    'Summer Sale',
                    'Email Campaign',
                    'Facebook Ads',
                    'Google Ads',
                    'Organic',
                    null,
                ]),

                'first_visit'  => $firstVisit,
                'last_visit'   => $lastVisit,
                'visit_count'  => rand(1, 20),

                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            $visitorIds[] = $visitorId;
        }

        // 100 Page Views
        for ($i = 1; $i <= 100; $i++) {

            $index = array_rand($pages);

            DB::table('page_views')->insert([
                'visitor_id' => $faker->randomElement($visitorIds),

                'page_url' => $pages[$index],

                'page_title' => $titles[$index],

                'route' => str_replace('/', '', $pages[$index]) ?: 'home',

                'referrer' => $faker->randomElement([
                    'Google',
                    'Facebook',
                    'LinkedIn',
                    'Direct',
                    'Twitter',
                ]),

                'time_on_page' => rand(5, 600),

                'created_at' => $faker->dateTimeBetween('-30 days', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}
