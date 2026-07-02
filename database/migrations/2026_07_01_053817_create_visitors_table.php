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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            // UUID stored in browser
            $table->uuid('visitor_id')->unique();

            $table->ipAddress('ip_address')->nullable();

            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();

            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('device')->nullable();

            $table->string('language')->nullable();

            $table->string('timezone')->nullable();

            $table->string('referrer')->nullable();
            $table->string('utm_campaign')->nullable();

            $table->timestamp('first_visit');

            $table->timestamp('last_visit');

            $table->integer('visit_count')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
