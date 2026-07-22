<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // CHANGE THIS to match the connection name in your config/database.php
    // for the database that holds `orders` / `order_details`.
       private string $ordersConnection = 'marketplace'; // was 'orders'

    public function up(): void
    {
        Schema::connection($this->ordersConnection)->table('sales', function (Blueprint $table) {
            // Plain nullable column, NOT a foreign key: `affiliates` lives in
            // a different database than `orders`, so it can't be constrained
            // at the DB level. CommissionCalculator enforces the relationship
            // in application code instead.
            $table->unsignedBigInteger('affiliate_id')->nullable()->after('id');
            $table->index('affiliate_id');
        });
    }

    public function down(): void
    {
        Schema::connection($this->ordersConnection)->table('sales', function (Blueprint $table) {
            $table->dropIndex(['affiliate_id']);
            $table->dropColumn('affiliate_id');
        });
    }
};
