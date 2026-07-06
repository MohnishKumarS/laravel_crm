<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_hero_sections', function (Blueprint $table) {
            $table->id();

            $table->boolean('is_active')->default(true); // only one active at a time

            // Copy
            $table->string('heading');                 // "The world celebrates brands. We celebrate"
            $table->string('heading_highlight')->nullable(); // "builders." (rendered in gradient color)
            $table->text('subtext')->nullable();

            // Floating badge pill overlapping the images, e.g. "TALENT INTO INDEPENDENCE"
            $table->string('badge_text')->nullable();

            // The 3 images in the collage
            $table->string('image_main')->nullable();       // big left/center image
            $table->string('image_secondary_1')->nullable(); // top-right small image
            $table->string('image_secondary_2')->nullable(); // bottom-right small image

            // Buttons row, e.g. [{text, url, style: primary|outline}, ...]
            $table->json('buttons')->nullable();

            // "Shop / Connect / Grow" style feature list, e.g.
            // [{title: "Shop", description: "Products, gifts & services"}, ...]
            $table->json('features')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_hero_sections');
    }
};
