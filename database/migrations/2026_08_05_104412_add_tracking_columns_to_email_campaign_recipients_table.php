<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('email_campaign_recipients', function (Blueprint $table) {
            $table->timestamp('opened_at')->nullable();
            $table->unsignedInteger('open_count')->default(0);

            $table->timestamp('clicked_at')->nullable();
            $table->unsignedInteger('click_count')->default(0);

            $table->string('tracking_token')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_campaign_recipients', function (Blueprint $table) {
            //
        });
    }
};
