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
        Schema::table('affiliate_payouts', function (Blueprint $table) {
            $table->string('method', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliate_payouts', function (Blueprint $table) {
            $table->enum('method', [
                'paypal',
                'bank_transfer',
                'other',
            ])->default('paypal')->change();
        });
    }
};
