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
        Schema::create('email_campaign_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')
                ->constrained('email_campaigns')
                ->cascadeOnDelete();

            $table->string('recipient_type');
            // user
            // seller
            // custom

            $table->unsignedBigInteger('recipient_id')
                ->nullable();

            $table->string('name')->nullable();

            $table->string('email');

            $table->string('sender_email')->nullable();

            $table->string('status')->default('queued');
            // queued
            // sending
            // sent
            // failed

            $table->text('error_message')->nullable();

            $table->timestamp('sent_at')->nullable();

            $table->unsignedInteger('attempts')->default(0);

            $table->timestamps();

            $table->index([
                'campaign_id',
                'status'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_campaign_recipients');
    }
};
