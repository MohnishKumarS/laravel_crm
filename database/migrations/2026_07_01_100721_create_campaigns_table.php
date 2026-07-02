<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();

            // Identity
            $table->string('name');                 // e.g. "Diwali Sale 2026"
            $table->string('slug')->unique();        // e.g. "diwali-2026"

            // Scheduling — this is what makes it "dynamic by date" instead of by code
            $table->dateTime('start_at')->nullable(); // null = can start immediately
            $table->dateTime('end_at')->nullable();   // null = never expires
            $table->boolean('is_published')->default(true);  // admin on/off switch
            $table->boolean('is_default')->default(false);   // the evergreen fallback campaign
            $table->unsignedInteger('priority')->default(0); // tie-breaker if 2 campaigns overlap

            // Theme (this is what re-skins the hero/announcement bar)
            $table->string('theme_bg_start', 9)->default('#2b2326');  // gradient/overlay start (hex, allows alpha)
            $table->string('theme_bg_end', 9)->default('#2b2326');    // gradient/overlay end
            $table->string('accent_color', 9)->default('#e6a23c');    // buttons / badge background
            $table->string('accent_text_color', 9)->default('#2b2326'); // text color on top of accent
            $table->string('eyebrow_color', 9)->default('#e6a23c');

            // Media
            $table->string('background_image')->nullable(); // storage path

            // Copy
            $table->string('eyebrow')->nullable();
            $table->string('badge_text')->nullable();     // null = no pill badge shown
            $table->string('heading');                    // main hero heading
            $table->string('heading_highlight')->nullable(); // italic/emphasised trailing phrase
            $table->text('subtext')->nullable();
            $table->string('cta1_text')->nullable();
            $table->string('cta1_url')->nullable();
            $table->string('cta2_text')->nullable();
            $table->string('cta2_url')->nullable();

            // Announcement bar
            $table->string('announcement_text')->nullable();     // null = bar hidden
            $table->string('announcement_link_text')->nullable();
            $table->string('announcement_link_url')->nullable();

            // Countdown (usually mirrors end_at, but kept separate in case the
            // "ends in" clock should stop before the campaign is actually removed)
            $table->boolean('show_countdown')->default(false);
            $table->dateTime('countdown_end_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
