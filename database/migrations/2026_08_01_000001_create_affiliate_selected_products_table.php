<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliate_selected_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_id')->constrained()->cascadeOnDelete();

            // product_id refers to a row in marketplace_new.sma_products,
            // a separate database/connection - no FK constraint, same
            // reasoning as affiliate_commissions.order_id.
            $table->unsignedBigInteger('product_id');

            // Cached at selection time so the affiliate dashboard and
            // promo page don't need a live cross-database join on every
            // page load. Refresh these if the product changes significantly.
            $table->string('product_name')->nullable();
            $table->string('product_image')->nullable();
            $table->decimal('product_price', 10, 2)->nullable();

            $table->timestamps();

            $table->unique(['affiliate_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_selected_products');
    }
};
