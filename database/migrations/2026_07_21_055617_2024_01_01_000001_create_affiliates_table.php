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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('affiliate_code')->unique();   // used in ?ref= and manual code entry
            $table->string('slug')->unique();              // used in /r/{slug} pretty links
            $table->decimal('commission_rate', 5, 2)->default(10.00); // flat %, per-affiliate override
            $table->enum('status', ['pending', 'approved', 'suspended', 'rejected'])->default('pending');
            $table->string('paypal_email')->nullable();
            $table->text('payout_notes')->nullable();       // bank details etc.
            $table->decimal('lifetime_earnings', 12, 2)->default(0);
            $table->decimal('lifetime_paid', 12, 2)->default(0);
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
