<?php

namespace Database\Seeders;

use App\Models\Campaign;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    /**
     * Seeds the three campaigns that were previously hardcoded in the
     * mockup's JS `CAMPAIGNS` object, so the admin panel starts populated.
     */
    public function run(): void
    {
        Campaign::updateOrCreate(['slug' => 'default'], [
            'name'               => 'Evergreen (Default)',
            'is_default'         => true,
            'is_published'       => true,
            'priority'           => 0,
            'theme_bg_start'     => '#2b2326',
            'theme_bg_end'       => '#2b2326',
            'accent_color'       => '#e6a23c',
            'accent_text_color'  => '#2b2326',
            'eyebrow_color'      => '#e6a23c',
            'eyebrow'            => 'Buy Better · Live Better · Build Better',
            'heading'            => 'The world celebrates brands. We celebrate',
            'heading_highlight'  => 'builders.',
            'subtext'            => 'Shop products, book services, and choose meaningful gifts created by women entrepreneurs. Every order helps a woman turn talent into income.',
            'cta1_text'          => 'Explore Marketplace →',
            'cta2_text'          => 'Become a Builder',
        ]);

        Campaign::updateOrCreate(['slug' => 'diwali-2026'], [
            'name'                     => 'Diwali Sale 2026',
            'is_default'               => false,
            'is_published'             => true,
            'priority'                 => 10,
            'start_at'                 => '2026-10-20 00:00:00',
            'end_at'                   => '2026-11-05 23:59:59',
            'theme_bg_start'           => '#4a1f38',
            'theme_bg_end'             => '#c0276a',
            'accent_color'             => '#e6a23c',
            'accent_text_color'        => '#6e1a2b',
            'eyebrow_color'            => '#e6a23c',
            'badge_text'               => 'DIWALI SALE · UP TO 40% OFF',
            'eyebrow'                  => 'Festival of Lights · Limited Time',
            'heading'                  => 'Gift handmade.',
            'heading_highlight'        => 'Gift with meaning.',
            'subtext'                  => 'Diwali hampers, brass & décor, millet treats and more — made by women artisans. Every order lights up a small business this festive season.',
            'cta1_text'                => 'Shop Diwali Gifts →',
            'cta2_text'                => 'Corporate Hampers',
            'announcement_text'        => '🪔 Diwali Sale is live — up to 40% off handcrafted gifts & décor.',
            'announcement_link_text'   => 'Shop the sale →',
            'show_countdown'           => true,
            'countdown_end_at'         => '2026-11-05 23:59:59',
        ]);

        Campaign::updateOrCreate(['slug' => 'womens-day-2026'], [
            'name'                     => "Women's Day Edit 2026",
            'is_default'               => false,
            'is_published'             => true,
            'priority'                 => 10,
            'start_at'                 => '2026-03-01 00:00:00',
            'end_at'                   => '2026-03-08 23:59:59',
            'theme_bg_start'           => '#3b2a8c',
            'theme_bg_end'             => '#882236',
            'accent_color'             => '#ffffff',
            'accent_text_color'        => '#3b2a8c',
            'eyebrow_color'            => '#ffd9a8',
            'badge_text'               => "WOMEN'S DAY EDIT · MAR 1–8",
            'eyebrow'                  => 'International Women\'s Day',
            'heading'                  => 'Back the women',
            'heading_highlight'        => 'who build.',
            'subtext'                  => 'A curated edit of products and services from women entrepreneurs across India. This week, every purchase is a vote for her independence.',
            'cta1_text'                => 'Shop the Edit →',
            'cta2_text'                => 'Mentor a Builder',
            'announcement_text'        => "✨ Women's Day Edit — celebrate the women who build.",
            'announcement_link_text'   => 'Discover the collection →',
            'show_countdown'           => true,
            'countdown_end_at'         => '2026-03-08 23:59:59',
        ]);
    }
}
