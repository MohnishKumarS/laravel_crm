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
        Schema::create('affiliate_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // seed sane defaults
        $now = now();
        Schema::getConnection()->table('affiliate_settings')->insert([
            ['key' => 'default_commission_rate', 'value' => '10', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'cookie_duration_days', 'value' => '30', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'refund_hold_days', 'value' => '14', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'min_payout_amount', 'value' => '50', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'auto_approve_affiliates', 'value' => '0', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'self_referral_block', 'value' => '1', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('affiliate_settings');
    }
};
