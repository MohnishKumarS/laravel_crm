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
        Schema::create('affiliate_commissions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('affiliate_id')->constrained()->cascadeOnDelete();
        $table->unsignedBigInteger('order_id'); // no FK - order lives in marketplace_new
        $table->decimal('order_total', 10, 2);
        $table->decimal('commission_rate', 5, 2);
        $table->decimal('commission_amount', 10, 2);
        $table->enum('status', ['pending', 'approved', 'reversed', 'paid'])->default('pending');
        $table->foreignId('payout_id')->nullable()->constrained('affiliate_payouts')->nullOnDelete();
        $table->timestamp('approved_at')->nullable();
        $table->timestamp('reversed_at')->nullable();
        $table->timestamps();

        $table->unique('order_id');
        $table->index(['affiliate_id', 'status']);
       });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
         Schema::dropIfExists('affiliate_commissions');
    }
};
